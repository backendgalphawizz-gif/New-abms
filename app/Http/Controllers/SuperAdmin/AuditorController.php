<?php

namespace App\Http\Controllers\SuperAdmin;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Assessor;
use App\Models\Entity;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AuditorController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $hasAssessorsTable = Schema::hasTable('assessors');
        $query = Admin::query()
            ->where('admin_role_id', 3)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                });
            })
            ->latest();

        if ($hasAssessorsTable) {
            $query->with('assessor');
        }

        $auditors = $query->paginate(20)->appends(['search' => $search]);

        return view('super-admin.auditors.index', compact('auditors', 'search', 'hasAssessorsTable'));
    }

    public function create()
    {
        return view('super-admin.auditors.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->auditorFormValidationRules());

        DB::beginTransaction();
        try {
            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->admin_role_id = 3;
            $admin->password = Hash::make($request->password);
            $admin->status = 1;
            if ($request->hasFile('image')) {
                $ext = strtolower((string) $request->file('image')->getClientOriginalExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                    $ext = 'png';
                }
                $admin->image = ImageManager::upload('auditor/', $ext, $request->file('image'));
            } else {
                $admin->image = 'def.png';
            }
            $admin->save();

            if (Schema::hasTable('assessors')) {
                $assessor = new Assessor();
                $assessor->assessor_id = $admin->id;
                if (Schema::hasColumn('assessors', 'profile_status')) {
                    $assessor->profile_status = 0;
                }
                if (Schema::hasColumn('assessors', 'remark')) {
                    $assessor->remark = 'Profile not updated';
                }
                $this->fillAssessorProfileFromRequest($request, $assessor);
                $assessor->save();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            Toastr::error('Could not create auditor. Please try again.');
            return back()->withInput();
        }

        if ((bool) $request->boolean('sync_to_tenants', true)) {
            $this->syncAuditorToTenants($request);
        }

        Toastr::success('Auditor created successfully.');
        return redirect()->route('super-admin.auditors.index');
    }

    public function edit($id)
    {
        $auditor = Admin::query()
            ->where('admin_role_id', 3)
            ->findOrFail($id);

        $assessor = null;
        if (Schema::hasTable('assessors')) {
            $assessor = Assessor::query()->where('assessor_id', $auditor->id)->first();
        }

        return view('super-admin.auditors.edit', compact('auditor', 'assessor'));
    }

    public function update(Request $request, $id)
    {
        $auditor = Admin::query()
            ->where('admin_role_id', 3)
            ->findOrFail($id);

        $request->validate($this->auditorFormValidationRules($auditor->id));

        DB::beginTransaction();
        try {
            $auditor->name = $request->name;
            $auditor->email = $request->email;
            $auditor->phone = $request->phone;
            if ($request->filled('password')) {
                $auditor->password = Hash::make($request->password);
            }

            if ($request->hasFile('image')) {
                if (!empty($auditor->image) && $auditor->image !== 'def.png') {
                    ImageManager::delete('/auditor/' . $auditor->image);
                }
                $ext = strtolower((string) $request->file('image')->getClientOriginalExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                    $ext = 'png';
                }
                $auditor->image = ImageManager::upload('auditor/', $ext, $request->file('image'));
            }
            $auditor->save();

            if (Schema::hasTable('assessors')) {
                $assessor = Assessor::query()->where('assessor_id', $auditor->id)->first();
                if (!$assessor) {
                    $assessor = new Assessor();
                    $assessor->assessor_id = $auditor->id;
                }

                $this->fillAssessorProfileFromRequest($request, $assessor);
                $assessor->save();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            Toastr::error('Could not update auditor. Please try again.');
            return back()->withInput();
        }

        Toastr::success('Auditor updated successfully.');
        return redirect()->route('super-admin.auditors.index');
    }

    public function reviewProfile(Request $request, $id)
    {
        $auditor = Admin::query()
            ->where('admin_role_id', 3)
            ->findOrFail($id);

        if (!Schema::hasTable('assessors')) {
            Toastr::error('Assessors table not available.');
            return redirect()->route('super-admin.auditors.index');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'reject_reason' => 'required_if:action,reject|nullable|string|max:2000',
        ]);

        $assessor = Assessor::query()->where('assessor_id', $auditor->id)->first();
        if (!$assessor) {
            Toastr::error('Assessor profile record not found.');
            return redirect()->route('super-admin.auditors.edit', $auditor->id);
        }

        if (!Schema::hasColumn('assessors', 'profile_status') || !Schema::hasColumn('assessors', 'remark')) {
            Toastr::error('Run database migrations: profile_status / remark columns are missing.');
            return redirect()->route('super-admin.auditors.edit', $auditor->id);
        }

        if ($request->input('action') === 'approve') {
            $assessor->profile_status = 1;
            $assessor->remark = 'Approved';
        } else {
            $assessor->profile_status = 2;
            $assessor->remark = $request->input('reject_reason', 'Not approved');
        }

        $assessor->save();

        Toastr::success('Profile review saved.');
        return redirect()->route('super-admin.auditors.edit', $auditor->id);
    }

    public function status($id, $status)
    {
        $auditor = Admin::query()
            ->where('admin_role_id', 3)
            ->findOrFail($id);

        $auditor->status = (int) $status === 1 ? 1 : 0;
        $auditor->save();

        Toastr::success('Auditor status updated.');
        return redirect()->route('super-admin.auditors.index');
    }

    public function destroy($id)
    {
        $auditor = Admin::query()
            ->where('admin_role_id', 3)
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            if (Schema::hasTable('assessors')) {
                Assessor::query()->where('assessor_id', $auditor->id)->delete();
            }

            if (!empty($auditor->image) && $auditor->image !== 'def.png') {
                ImageManager::delete('/auditor/' . $auditor->image);
            }

            $auditor->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            Toastr::error('Could not delete auditor. Please try again.');
            return redirect()->route('super-admin.auditors.index');
        }

        Toastr::success('Auditor deleted successfully.');
        return redirect()->route('super-admin.auditors.index');
    }

    /**
     * @return array<string, mixed>
     */
    private function auditorFormValidationRules(?int $adminId = null): array
    {
        $emailRule = ['required', 'email', 'max:120', 'unique:admins,email'];
        $phoneRule = ['required', 'string', 'max:30', 'unique:admins,phone'];
        if ($adminId !== null) {
            $emailRule = ['required', 'email', 'max:120', Rule::unique('admins', 'email')->ignore($adminId)];
            $phoneRule = ['required', 'string', 'max:30', Rule::unique('admins', 'phone')->ignore($adminId)];
        }

        $rules = [
            'name' => 'required|string|max:120',
            'email' => $emailRule,
            'phone' => $phoneRule,
            'apply_designation' => 'nullable|string|max:191',
            'highest_qualification' => 'nullable|string|max:191',
            'technical_area' => 'nullable|string|max:191',
            'experience' => 'nullable|integer|min:0|max:80',
            'home_address' => 'nullable|string|max:2000',
            'residence_tel' => 'nullable|string|max:191',
            'training' => 'nullable|string|max:5000',
            'specific_knowledge_gained' => 'nullable|string|max:5000',
            'additional_information' => 'nullable|string|max:5000',
            'professional_experience' => 'nullable|array',
            'professional_experience.*' => 'nullable|array',
            'assessment_summery' => 'nullable|array',
            'assessment_summery.*' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'qualification_document' => 'nullable|file|max:10240',
            'work_experience_document' => 'nullable|file|max:10240',
            'consultancy_document' => 'nullable|file|max:10240',
            'audit_document' => 'nullable|file|max:10240',
            'training_document' => 'nullable|file|max:10240',
        ];

        if ($adminId === null) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['sync_to_tenants'] = 'nullable|boolean';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    private function fillAssessorProfileFromRequest(Request $request, Assessor $assessor): void
    {
        $assessor->apply_designation = $request->apply_designation;
        $assessor->highest_qualification = $request->highest_qualification;
        $assessor->technical_area = $request->technical_area;
        $assessor->experience = $request->experience ?? 0;
        $assessor->home_address = $request->home_address;

        foreach ([
            'residence_tel',
            'training',
            'specific_knowledge_gained',
            'additional_information',
        ] as $col) {
            if (Schema::hasColumn('assessors', $col)) {
                $assessor->{$col} = $request->input($col);
            }
        }

        if (Schema::hasColumn('assessors', 'professional_experience')) {
            $assessor->professional_experience = $this->normalizeStructuredRows($request->input('professional_experience'));
        }
        if (Schema::hasColumn('assessors', 'assessment_summery')) {
            $assessor->assessment_summery = $this->normalizeStructuredRows($request->input('assessment_summery'));
        }

        $docFields = [
            'qualification_document',
            'work_experience_document',
            'consultancy_document',
            'audit_document',
            'training_document',
        ];
        foreach ($docFields as $field) {
            if (!Schema::hasColumn('assessors', $field)) {
                continue;
            }
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $ext = strtolower((string) $file->getClientOriginalExtension()) ?: 'pdf';
                $assessor->{$field} = ImageManager::upload('media/', $ext, $file);
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>|null
     */
    private function normalizeStructuredRows($raw): ?array
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        $rows = is_array($raw) ? $raw : $this->decodeJsonInput($raw);
        if (!is_array($rows)) {
            return null;
        }

        $out = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            if (!$this->structuredRowHasAnyValue($row)) {
                continue;
            }
            $out[] = $this->trimStructuredRow($row);
        }

        return count($out) > 0 ? array_values($out) : null;
    }

    private function structuredRowHasAnyValue(array $row): bool
    {
        foreach ($row as $value) {
            if (is_array($value)) {
                if ($this->structuredRowHasAnyValue($value)) {
                    return true;
                }
            } elseif ($value !== null && trim((string) $value) !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    private function trimStructuredRow(array $row): array
    {
        $trimmed = [];
        foreach ($row as $key => $value) {
            $trimmed[$key] = is_string($value) ? trim($value) : $value;
        }

        return $trimmed;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function decodeJsonInput($raw)
    {
        if ($raw === null || $raw === '') {
            return null;
        }
        if (is_array($raw)) {
            return $raw;
        }
        $decoded = json_decode((string) $raw, true);

        return is_array($decoded) ? $decoded : null;
    }

    private function syncAuditorToTenants(Request $request): void
    {
        $entities = Entity::query()->get();
        foreach ($entities as $entity) {
            try {
                $entity->run(function () use ($request) {
                    if (!DB::getSchemaBuilder()->hasTable('admins')) {
                        return;
                    }
                    if (DB::table('admins')->where('email', $request->email)->exists()) {
                        return;
                    }

                    $adminId = DB::table('admins')->insertGetId([
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'admin_role_id' => 3,
                        'password' => Hash::make($request->password),
                        'status' => 1,
                        'image' => 'def.png',
                        'remember_token' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if (DB::getSchemaBuilder()->hasTable('assessors')) {
                        $schema = DB::getSchemaBuilder();
                        $insert = [
                            'assessor_id' => $adminId,
                            'apply_designation' => $request->apply_designation,
                            'highest_qualification' => $request->highest_qualification,
                            'technical_area' => $request->technical_area,
                            'experience' => $request->experience ?? 0,
                            'home_address' => $request->home_address,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        if ($schema->hasColumn('assessors', 'profile_status')) {
                            $insert['profile_status'] = 0;
                        }
                        if ($schema->hasColumn('assessors', 'remark')) {
                            $insert['remark'] = 'Profile not updated';
                        }
                        foreach ([
                            'residence_tel',
                            'training',
                            'specific_knowledge_gained',
                            'additional_information',
                        ] as $col) {
                            if ($schema->hasColumn('assessors', $col)) {
                                $insert[$col] = $request->input($col);
                            }
                        }
                        DB::table('assessors')->insert($insert);
                    }
                });
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}

