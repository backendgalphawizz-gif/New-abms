<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\IsoStandard;
use App\Support\IsoChecklistTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Stancl\Tenancy\Jobs\MigrateDatabase;
use Throwable;

class EntityController extends Controller
{
    private const RESERVED_SUBDOMAINS = [
        'www', 'mail', 'ftp', 'admin', 'api', 'app', 'cdn', 'static', 'ns1', 'ns2',
    ];

    public function index()
    {
        $entities = Entity::query()->with('domains')->orderByDesc('created_at')->paginate(20);

        $tenantAdmins = [];
        foreach ($entities as $entity) {
            // VirtualColumn decodes JSON `data` onto the model (email, phone, …) and clears `data`.
            $tenantAdmins[$entity->id] = [
                'email' => $entity->email,
                'phone' => $entity->phone,
            ];
        }

        return view('super-admin.entities.index', compact('entities', 'tenantAdmins'));
    }

    public function create()
    {
        $standardRows = IsoStandard::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('code')
            ->get(['code', 'name']);

        $isoStandards = $standardRows->pluck('name', 'code')->toArray();
        $isoChecklistTemplates = [];
        foreach ($standardRows as $standardRow) {
            $template = IsoChecklistTemplates::forCode((string) $standardRow->code);
            if ($template === null) {
                continue;
            }

            $isoChecklistTemplates[(string) $standardRow->code] = $template;
        }

        return view('super-admin.entities.create', [
            'domainSuffix' => config('tenancy.entity_domain_suffix'),
            'isoStandards' => $isoStandards,
            'isoChecklistTemplates' => $isoChecklistTemplates,
        ]);
    }

    public function store(Request $request)
    {
        $suffix = config('tenancy.entity_domain_suffix');

        $validIsoCodes = IsoStandard::query()
            ->where('is_active', true)
            ->pluck('code')
            ->all();

        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => [
                'required',
                'string',
                'max:63',
                'regex:/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])$/',
                Rule::notIn(self::RESERVED_SUBDOMAINS),
                function ($attribute, $value, $fail) use ($suffix) {
                    $full = strtolower((string) $value).'.'.$suffix;
                    if (Domain::query()->where('domain', $full)->exists()) {
                        $fail(__('This subdomain is already taken.'));
                    }
                },
            ],
            'admin_name' => ['required', 'string', 'max:80'],
            'admin_email' => ['required', 'email', 'max:80'],
            'admin_password' => ['required', 'string', 'min:8', 'confirmed'],
            'admin_phone' => ['nullable', 'string', 'max:25'],
            'iso_certifications' => ['nullable', 'array'],
            'iso_certifications.*' => ['string', Rule::in($validIsoCodes)],
        ]);

        $fullDomain = strtolower($request->subdomain).'.'.$suffix;
        $selectedIso = array_values(array_intersect(
            $validIsoCodes,
            (array) $request->input('iso_certifications', [])
        ));

        // Save tenant + domain before provisioning. TenantCreated runs MigrateDatabase synchronously;
        // if migrations fail, the tenant row would already exist but domains()->create() would never run.
        $entity = Entity::withoutEvents(function () use ($request, $selectedIso) {
            return Entity::query()->create([
                'id' => (string) Str::uuid(),
                'name' => $request->name,
                'data' => [
                    'email' => $request->admin_email,
                    'phone' => $request->filled('admin_phone') ? $request->admin_phone : null,
                    'iso_certifications' => $selectedIso,
                ],
            ]);
        });

        $entity->domains()->create([
            'domain' => $fullDomain,
        ]);

        try {
            CreateDatabase::dispatchSync($entity);
            MigrateDatabase::dispatchSync($entity);

            $entity->run(function () use ($request, $selectedIso) {
                if (DB::table('admins')->where('email', $request->admin_email)->exists()) {
                    throw new \RuntimeException('duplicate_admin_email');
                }

                DB::table('admins')->insert([
                    'name' => $request->admin_name,
                    'email' => $request->admin_email,
                    'phone' => $request->filled('admin_phone') ? $request->admin_phone : null,
                    'admin_role_id' => 1,
                    'image' => 'def.png',
                    'password' => Hash::make($request->admin_password),
                    'remember_token' => Str::random(10),
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if (DB::getSchemaBuilder()->hasTable('business_settings')) {
                    DB::table('business_settings')->updateOrInsert(
                        ['type' => 'entity_iso_certifications'],
                        [
                            'value' => json_encode($selectedIso),
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }
            });
        } catch (Throwable $e) {
            report($e);
            $entity->delete();

            if ($e->getMessage() === 'duplicate_admin_email') {
                $message = __('An admin with this email already exists for this entity.');
            } elseif (config('app.debug')) {
                $message = __('Provisioning failed: :details', [
                    'details' => Str::limit($e->getMessage(), 800),
                ]);
            } else {
                $message = __('Could not finish setting up the entity. Please try again.');
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['provision' => $message]);
        }

        $loginUrl = $request->getScheme().'://'.$fullDomain.'/admin/auth/login';

        return redirect()->route('super-admin.entities.index')
            ->with('success', __('Entity created. The tenant admin can sign in at :url using email :email.', [
                'url' => $loginUrl,
                'email' => $request->admin_email,
            ]));
    }

    public function destroy(Entity $entity)
    {
        $entity->delete();

        return redirect()->route('super-admin.entities.index')
            ->with('success', __('Entity removed and tenant database deleted.'));
    }
}
