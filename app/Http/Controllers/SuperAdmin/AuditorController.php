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
        $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:120|unique:admins,email',
            'phone' => 'required|string|max:30|unique:admins,phone',
            'password' => 'required|string|min:8|confirmed',
            'apply_designation' => 'nullable|string|max:191',
            'highest_qualification' => 'nullable|string|max:191',
            'technical_area' => 'nullable|string|max:191',
            'experience' => 'nullable|integer|min:0|max:80',
            'home_address' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'sync_to_tenants' => 'nullable|boolean',
        ]);

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
                $assessor->apply_designation = $request->apply_designation;
                $assessor->highest_qualification = $request->highest_qualification;
                $assessor->technical_area = $request->technical_area;
                $assessor->experience = $request->experience ?? 0;
                $assessor->home_address = $request->home_address;
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

        $request->validate([
            'name' => 'required|string|max:120',
            'email' => ['required', 'email', 'max:120', Rule::unique('admins', 'email')->ignore($auditor->id)],
            'phone' => ['required', 'string', 'max:30', Rule::unique('admins', 'phone')->ignore($auditor->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'apply_designation' => 'nullable|string|max:191',
            'highest_qualification' => 'nullable|string|max:191',
            'technical_area' => 'nullable|string|max:191',
            'experience' => 'nullable|integer|min:0|max:80',
            'home_address' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

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

                $assessor->apply_designation = $request->apply_designation;
                $assessor->highest_qualification = $request->highest_qualification;
                $assessor->technical_area = $request->technical_area;
                $assessor->experience = $request->experience ?? 0;
                $assessor->home_address = $request->home_address;
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
                        DB::table('assessors')->insert([
                            'assessor_id' => $adminId,
                            'apply_designation' => $request->apply_designation,
                            'highest_qualification' => $request->highest_qualification,
                            'technical_area' => $request->technical_area,
                            'experience' => $request->experience ?? 0,
                            'home_address' => $request->home_address,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                });
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}

