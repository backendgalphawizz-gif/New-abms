<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Scheme;
use App\Model\Assessor;
use App\Model\WitnessReport;
use App\Model\AdminRole;
use App\Model\SchemeArea;
use App\Model\Scope;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmployeeController extends Controller
{

    public function add_new()
    {
        $rls = AdminRole::whereNotIn('id', [1])->get();
        return view('admin-views.employee.add-new', compact('rls'));
    }
    public function addAssessor()
    {
        // $rls = AdminRole::whereNotIn('id', [1])->get();
        $scheme = Scheme::get();
        return view('admin-views.employee.add-assessor', compact('scheme'));
    }

    public function storeAssessor(Request $request){

        $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'image' => 'required',
            'email' => 'required|email|unique:admins',
            'password'=>'required',
            'phone'=>'required|unique:admins'

        ], [
            'name.required' => 'Role name is required!',
            'role_name.required' => 'Role id is Required',
            'email.required' => 'Email id is Required',
            'image.required' => 'Image is Required',

        ]);

        if ($request->confirm_password != $request->password) {
            Toastr::error('Confirm password and password does not match');
            return redirect()->back();
        }


        $admin = new Admin();
        $admin->name = $request->name;
        $admin->phone = $request->phone;
        $admin->email = $request->email;
        $admin->admin_role_id = $request->role_id;
        $admin->password = bcrypt($request->password);
        $admin->status = 1;
        //$admin->profile_status = 1;
        $admin->image = ImageManager::upload('admin/', 'png', $request->file('image'));
        $admin->save();

        $files = [];
        if(isset($request['file'])){
            foreach($request['file'] as $key=>$value){
                $ext = $value->getClientOriginalExtension();

                $uploadedPath = ImageManager::uploadMedia('media/', $ext, $value);
                $files[$key] = $uploadedPath;
            }

        }
        $countdegree = count($request['qualifications']['degree']);
        for($i=0;$i<$countdegree;$i++){
            if(!isset($files[$i])){
                $files[$i] = null;
            }
        }
        ksort($files);
        for($i=0;$i<$countdegree;$i++){
            $qualification[]=[
              'qualification'=>  $request['qualifications']['degree'][$i],
              'file'=>   $files[$i],
              'year'=>  $request['qualifications']['year'][$i],
              'institute'  => $request['qualifications']['institute'][$i],
              'remark'     => $request['qualifications']['remark'][$i],  
            ];
        }
        
        

        $assessor = new Assessor();
        $assessor->assessor_id = $admin->id;
        $assessor->id_number = $request['id_number'];
       // $assessor->last_organization = $request['last_organization'];
        $assessor->apply_designation = $request['apply_designation'];
        $assessor->highest_qualification = $request->highest_qualification;
        $assessor->technical_area = $request->technical_area ?? null;
        $assessor->experience = $request->first_experience;

       // $assessor->work_address = $request['work_address'];
        $assessor->home_address = $request['home_address'];
        $assessor->scheme_id = (isset($request['scheme_id']) && !empty($request['scheme_id'])) ? implode(',',$request['scheme_id']) : null;
        $assessor->area_id = (isset($request['area_id']) && !empty($request['area_id'])) ? implode(',',$request['area_id']) : null;
        $assessor->scope_id = (isset($request['scope_id']) && !empty($request['scope_id'])) ? implode(',',$request['scope_id']) : null;
        $assessor->qualifications = isset($qualification) ? ($qualification) : null;

        //     $competenceAreas = [
        //     "Knowledge of ISO/IEC 17011",
        //     "Knowledge of Relevant Scheme Standards",
        //     "Assessment & Audit Skills",
        //     "Technical Expertise",
        //     "Report Writing & Communication"
        // ];

        // $assessmentSummary = [];
        // foreach ($competenceAreas as $index => $area) {
        //     $assessmentSummary[] = [
        //         "area"   => $area,
        //         "remark" => $request->remarks[$index] ?? null,
        //         "rating" => $request->rating[$index] ?? null,
        //     ];
        // }
        // $assessor->assessment_summery = $assessmentSummary;


        $experience = [];
        $countExp = isset($request->experience['organization']) ? count($request->experience['organization']) : 0;

        for ($i = 0; $i < $countExp; $i++) {
            $experience[] = [
                "organization"          => $request->experience['organization'][$i],
                "position"              => $request->experience['position'][$i],
                "duration"              => $request->experience['duration'][$i],
                "key_responsibilities"  => $request->experience['key_responsibilities'][$i],
            ];
        }

        $assessor->professional_experience = $experience;

        $assessor->save();

        Toastr::success('Assesor added successfully!');

        return redirect()->route('admin.assessor.assessor-list');


    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'image' => 'required',
            'email' => 'required|email|unique:admins',
            'password'=>'required',
            'phone'=>'required'

        ], [
            'name.required' => 'Role name is required!',
            'role_name.required' => 'Role id is Required',
            'email.required' => 'Email id is Required',
            'image.required' => 'Image is Required',

        ]);

        if ($request->role_id == 1) {
            Toastr::warning('Access Denied!');
            return back();
        }

        if ($request->confirm_password != $request->password) {
            Toastr::error('Confirm password and password does not match');
            return redirect()->back();
        }

        DB::table('admins')->insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'admin_role_id' => $request->role_id,
            'password' => bcrypt($request->password),
            'status'=>1,
            'image' => ImageManager::upload('admin/', 'png', $request->file('image')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success('Employee added successfully!');
        return redirect()->route('admin.employee.list');
    }

    function list(Request $request)
    {
        $search = $request['search'];
        $key = explode(' ', $request['search']);
        $em = Admin::with(['role'])->whereNotIn('admin_role_id', [1,3])
                    ->when($search, function ($query) use ($key) {

                        $query->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('name', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%")
                                ->orWhere('email', 'like', "%{$value}%");
                            }
                        })

                        ->orWhereHas('role', function ($q) use ($key) {
                            $q->where(function ($r) use ($key) {
                                foreach ($key as $value) {
                                    $r->orWhere('name', 'like', "%{$value}%");
                                }
                            });
                        });

                    })
                    ->paginate(Helpers::pagination_limit());
        return view('admin-views.employee.list', compact('em','search'));
    }
   function assessorList(Request $request)
{
    $search = $request->search;
    $hasAssessorsTable = Schema::hasTable('assessors');

    $query = Admin::query()
        ->where('admin_role_id', 3)
        ->when($search, function ($query) use ($search) {
            $key = explode(' ', $search);
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
        })
        ->orderBy('created_at', 'desc');

    if ($hasAssessorsTable) {
        $query->with('assessor');
    }

    $em = $query->paginate(Helpers::pagination_limit());

    return view('admin-views.employee.assessor-list', compact('em', 'search', 'hasAssessorsTable'));
}


     public function CompetenceEvaluationUpdate(Request $request, $id)
    {
        $assessor = Assessor::where('assessor_id', $id)->first();

        if (!$assessor) {
            Toastr::error('Assessor not found!');
            return back();
        }

        $areas = [
            "Knowledge of ISO/IEC 17011",
            "Knowledge of Relevant Scheme Standards",
            "Assessment & Audit Skills",
            "Technical Expertise",
            "Report Writing & Communication"
        ];

        $summary = [];
        foreach ($areas as $index => $area) {
            $summary[] = [
                "area" => $area,
                "remark" => $request->remarks[$index] ?? '',
                "rating" => $request->rating[$index] ?? '',
            ];
        }

        $evaluation = [
            "evaluator_name"         => $request->evaluator_name ?? '',
            "evaluation_date"        => $request->evaluation_date ?? '',
            "overall_recommendation" => $request->evaluation_recommendation ?? '',
            "comment"                => $request->evaluation_comment ?? ''
        ];

    
        $assessor->assessment_summery = $summary;
        $assessor->evaluation_details = [$evaluation];
        $assessor->save();

        Toastr::success('Saved Successfully!');
        return back();
    }



    public function edit($id)
    {
        $e = Admin::where(['id' => $id])->first();
        $rls = AdminRole::whereNotIn('id', [1])->get();
        return view('admin-views.employee.edit', compact('rls', 'e'));
    }
    public function assessorEdit($id)
    {
        $e = Admin::where(['id' => $id])->first();
        $assessor = Assessor::where(['assessor_id' => $id])->first();
       
        $scheme = Scheme::get();

        return view('admin-views.employee.assessor-edit', compact( 'assessor','e','scheme'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'email' => 'required|email|unique:admins,email,'.$id,
        ], [
            'name.required' => 'Role name is required!',
        ]);

        if ($request->role_id == 1) {
            Toastr::warning('Access Denied!');
            return back();
        }

        $e = Admin::find($id);
        if ($request['password'] == null) {
            $pass = $e['password'];
        } else {
            if (strlen($request['password']) < 7) {
                Toastr::warning('Password length must be 8 character.');
                return back();
            }
            $pass = bcrypt($request['password']);
        }

        if ($request->has('image')) {
            $e['image'] = ImageManager::update('admin/', $e['image'], 'png', $request->file('image'));
        }

        DB::table('admins')->where(['id' => $id])->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'admin_role_id' => $request->role_id,
            'password' => $pass,
            'image' => $e['image'],
            'updated_at' => now(),
        ]);

        Toastr::success('Employee updated successfully!');
        return back();
    }

    // public function assessorUpdate(Request $request, $id){

    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email|unique:admins,email,'.$id,
    //     ], [
    //         'name.required' => 'Name is required!',
    //     ]);

    //     // if ($request->role_id == 1) {
    //     //     Toastr::warning('Access Denied!');
    //     //     return back();
    //     // }

    //     $admin = Admin::find($id);
    //     if ($request['password'] == null) {
    //         $pass = $admin['password'];
    //     } else {
    //         if (strlen($request['password']) < 7) {
    //             Toastr::warning('Password length must be 8 character.');
    //             return back();
    //         }
    //         $pass = bcrypt($request['password']);
    //     }

    //     if ($request->has('image')) {
    //         $updateimage = ImageManager::update('admin/', $admin['image'], 'png', $request->file('image'));
    //     }

    //     $admin->name = $request->name;
    //     $admin->phone = $request->phone;
    //     $admin->email = $request->email;
    //     $admin->password = $pass;
    //     $admin->status = 1;
    //     $admin->profile_status = $request['profile_status'] ?? 0;
    //     $admin->image = isset($updateimage) ? $updateimage : $admin['image'] ;
    //     $admin->save();

    //     $files = [];
    //     if(isset($request['file'])){
    //         foreach($request['file'] as $key=>$value){
    //             $ext = $value->getClientOriginalExtension();

    //             $uploadedPath = ImageManager::uploadMedia('media/', $ext, $value);
    //             $files[$key] = $uploadedPath;
    //         }

    //     }
    //     $countdegree = count($request['qualifications']['degree']);
    //     for($i=0;$i<$countdegree;$i++){
    //         if(!isset($files[$i])){
    //             $files[$i] = $request['qualifications']['file'][$i] ?? null;
    //         }
    //     }
    //     ksort($files);
    //     for($i=0;$i<$countdegree;$i++){
    //         $qualification[]=[
    //           'degree'=>  $request['qualifications']['degree'][$i],
    //           'file'=>   $files[$i] ?? $request['qualifications']['file'][$i],
    //           'year'=>  $request['qualifications']['year'][$i],
    //         ];
    //     }
        

    //     $assessor = Assessor::where('assessor_id',$id)->first();
    //     $assessor->id_number = $request['id_number'];
    //     $assessor->last_organization = $request['last_organization'];
    //     $assessor->apply_designation = $request['apply_designation'];
    //     $assessor->work_address = $request['work_address'];
    //     $assessor->home_address = $request['home_address'];
    //     $assessor->scheme_id = (isset($request['scheme_id']) && !empty($request['scheme_id'])) ? implode(',',$request['scheme_id']) : null;
    //     $assessor->area_id = (isset($request['area_id']) && !empty($request['area_id'])) ? implode(',',$request['area_id']) : null;
    //     $assessor->scope_id = (isset($request['scope_id']) && !empty($request['scope_id'])) ? implode(',',$request['scope_id']) : null;
    //     $assessor->qualifications = isset($qualification) ? json_encode($qualification) : null;
    //     $assessor->save();

    //     Toastr::success('Assesor Update successfully!');

    //     return redirect()->route('admin.assessor.assessor-list');
    // }

public function assessorUpdate(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:admins,email,'.$id,
    ], [
        'name.required' => 'Name is required!',
    ]);

    $admin = Admin::find($id);

    if ($request['password']) {
        if (strlen($request['password']) < 7) {
            Toastr::warning('Password length must be 8 character.');
            return back();
        }
        $pass = bcrypt($request['password']);
    } else {
        $pass = $admin['password'];
    }

    if ($request->hasFile('image')) {
        $updateimage = ImageManager::update('admin/', $admin['image'], 'png', $request->file('image'));
    }

    $admin->name = $request->name;
    $admin->phone = $request->phone;
    $admin->email = $request->email;
    $admin->password = $pass;
    $admin->status = 1;
   // $admin->profile_status = $request['profile_status'] ?? 0;
    $admin->image = isset($updateimage) ? $updateimage : $admin['image'];
    $admin->save();
    $files = [];
    if(isset($request['file'])){
        foreach($request['file'] as $key=>$value){
            $ext = $value->getClientOriginalExtension();
            $uploadedPath = ImageManager::uploadMedia('media/', $ext, $value);
            $files[$key] = $uploadedPath;
        }
    }

    $qualification = [];
    $countdegree = count($request['qualifications']['degree']);
    for($i=0; $i<$countdegree; $i++){
        $qualification[] = [
            'qualification'=>  $request['qualifications']['degree'][$i],
            'file'=>   $files[$i] ?? ($request['qualifications']['file'][$i] ?? null),
            'year'=>  $request['qualifications']['year'][$i],
            'institute'=> $request['qualifications']['institute'][$i] ?? '',
            'remark'=> $request['qualifications']['remark'][$i] ?? '',
        ];
    }

    $experience = [];
    if (isset($request->experience['organization'])) {
        foreach ($request->experience['organization'] as $i => $val) {
            $experience[] = [
                'organization' => $request->experience['organization'][$i],
                'position' => $request->experience['position'][$i],
                'duration' => $request->experience['duration'][$i],
                'key_responsibilities' => $request->experience['key_responsibilities'][$i],
            ];
        }
    }

    // $areas = [
    //     "Knowledge of ISO/IEC 17011",
    //     "Knowledge of Relevant Scheme Standards",
    //     "Assessment & Audit Skills",
    //     "Technical Expertise",
    //     "Report Writing & Communication"
    // ];

    // $summary = [];
    // foreach ($areas as $index => $area) {
    //     $summary[] = [
    //         "area" => $area,
    //         "remark" => $request->remarks[$index] ?? '',
    //         "rating" => $request->rating[$index] ?? '',
    //     ];
    // }

    $assessor = Assessor::where('assessor_id', $id)->first();
    $assessor->id_number = $request['id_number'];
    $assessor->apply_designation = $request['apply_designation'];
    $assessor->home_address = $request['home_address'];
    $assessor->highest_qualification = $request->highest_qualification;
    $assessor->technical_area = $request->technical_area ?? null;
    $assessor->experience = $request->first_experience;
    $assessor->scheme_id = $request->scheme_id ? implode(',', $request['scheme_id']) : null;
    $assessor->area_id = $request->area_id ? implode(',', $request['area_id']) : null;
    $assessor->scope_id = $request->scope_id ? implode(',', $request['scope_id']) : null;
    $assessor->qualifications = ($qualification);
    $assessor->professional_experience = ($experience);
    //$assessor->assessment_summery = ($summary);
    $assessor->save();

    Toastr::success('Assessor updated successfully!');
    return redirect()->route('admin.assessor.assessor-list');
}

public function assessorProfile($id)
{
    $admin = Admin::findOrFail($id);

    $assessor = Assessor::where('assessor_id', $id)->firstOrFail();

    return view('admin-views.employee.assessor-profile', [
        'admin' => $admin,
        'assessor' => $assessor,
        'qualifications' => $assessor->qualifications,
        'experience' => $assessor->professional_experience,
        'assessment' => $assessor->assessment_summery,
        'evaluation_details' => $assessor->evaluation_details,
    ]);
}


    public function updateAssessorStatus(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $assessor = Assessor::where('assessor_id', $id)->first();

        if (!$assessor) {
            Toastr::error('Assessor not found!');
            return back();
        }

        $assessor->profile_status = $request->profile_status ?? 0;

        $assessor->approve_by = auth('admin')->id(); 
        $assessor->approve_date = now();             

        $assessor->save();

        Toastr::success('Status updated successfully!');
        return back();
    }
    public function updateQualificationStatus(Request $request, $id)
    {
        $assessor = Assessor::where('assessor_id', $id)->first();
        
        if (!$assessor) {
            Toastr::error('Assessor not found!');
            return back();
        }

        $scheme_id = [];
        $area_id = [];
        $scope_id = [];

        if($request['type'] == 'area'){
            $keyData = $assessor['area_qualifications'];
            $keyData[$request['key']]['status'] = $request['status'];
            $keyData[$request['key']]['updated_by'] = auth('admin')->user()->name;

            $assessor->area_qualifications = $keyData;

            if($request['status'] == 1){

                if(!empty($assessor['scheme_id'])){
                    $scheme_id = array_map('intval', explode(',', $assessor['scheme_id']));
                }
                if(!empty($request['value']['scheme_id'])){
                    $scheme_id[] = (int) $request['value']['scheme_id'];
                    $scheme_id = array_values(array_unique($scheme_id));
                }
    
                if(!empty($assessor['area_id'])){
                    $area_id = array_map('intval', explode(',', $assessor['area_id']));
                }
                if(!empty($request['value']['area_id'])){
                    $area_id[] = (int) $request['value']['area_id'];
                    $area_id = array_values(array_unique($area_id));
                }
    
                if(!empty($assessor['scope_id'])){
                    $scope_id = array_map('intval', explode(',', $assessor['scope_id']));
                }
                if(!empty($request['value']['scope_id'])){
                    $scope_id[] = (int) $request['value']['scope_id'];
                    $scope_id = array_values(array_unique($scope_id));
                }
            }

            $assessor->scheme_id = !empty($scheme_id) ? implode(',',$scheme_id) : $assessor->scheme_id;
            $assessor->area_id = !empty($area_id) ? implode(',',$area_id) : $assessor->area_id;
            $assessor->scope_id = !empty($scope_id) ? implode(',',$scope_id) : $assessor->scope_id;
        }

        if($request['type'] == 'scope'){
            $keyData = $assessor['scope_qualifications'];
            $keyData[$request['key']]['status'] = $request['status'];
            $keyData[$request['key']]['updated_by'] = auth('admin')->user()->name;

            $assessor->scope_qualifications = $keyData;

            if($request['status'] == 1){

                if(!empty($assessor['scheme_id'])){
                    $scheme_id = array_map('intval', explode(',', $assessor['scheme_id']));
                }
                if(!empty($request['value']['scheme_id'])){
                    $scheme_id[] = (int) $request['value']['scheme_id'];
                    $scheme_id = array_values(array_unique($scheme_id));
                }
    
                if(!empty($assessor['area_id'])){
                    $area_id = array_map('intval', explode(',', $assessor['area_id']));
                }
                if(!empty($request['value']['area_id'])){
                    $area_id[] = (int) $request['value']['area_id'];
                    $area_id = array_values(array_unique($area_id));
                }
    
                if(!empty($assessor['scope_id'])){
                    $scope_id = array_map('intval', explode(',', $assessor['scope_id']));
                }
                if(!empty($request['value']['scope_id'])){
                    $scope_id[] = (int) $request['value']['scope_id'];
                    $scope_id = array_values(array_unique($scope_id));
                }
            }

            $assessor->scheme_id = !empty($scheme_id) ? implode(',',$scheme_id) : $assessor->scheme_id;
            $assessor->area_id = !empty($area_id) ? implode(',',$area_id) : $assessor->area_id;
            $assessor->scope_id = !empty($scope_id) ? implode(',',$scope_id) : $assessor->scope_id;
        }

        $assessor->save();

        Toastr::success('Qualification Status updated successfully!');
        return back();
    }


    public function status(Request $request)
    {
        $employee = Admin::find($request->id);
    
        $employee->status = $request->status;
        $employee->save();
    
        Toastr::success('Employee status updated!');
        return back();
    }

    public function getAreasBySchemes(Request $request)
    {
        $schemeIds = $request->scheme_ids ?? [];
        $areas = SchemeArea::whereIn('scheme_id', $schemeIds)->get();
        return response()->json($areas);
    }

    public function getScopesByAreas(Request $request)
    {
        $areaIds = $request->area_ids ?? [];
        $scopes = Scope::whereIn('area_id', $areaIds)->get();
        return response()->json($scopes);
    }


public function witnessList(Request $request)
{
    $search = $request->search ?? null;

    $reports = WitnessReport::with(['assessor.admin','user','scheme'])
    ->when($search, function($q) use ($search){
        $q->whereHas('assessor.admin', function($a) use ($search){
            $a->where('name', 'LIKE', "%$search%");
        })
        ->orWhereHas('user', function($u) use ($search){
            $u->where('name', 'LIKE', "%$search%");
        });
    })
    ->when($request->status, function($q) use ($request){
        $q->where('status', $request->status);
    })
    ->latest()
    ->paginate(10);

    $counts = [
        'pending'  => WitnessReport::where('status', 0)->count(),
        'approved' => WitnessReport::where('status', 1)->count(),
        'rejected' => WitnessReport::where('status', 2)->count(),
    ];

    return view('admin-views.employee.witness-list', compact('reports','counts','search'));
}

public function viewWitness($id)
{
    $report = WitnessReport::with(['assessor.admin', 'user', 'scheme'])
                ->findOrFail($id);

    return view('admin-views.employee.view-witness', compact('report'));
}



public function updateWitnessStatus(Request $request, $id)
{
    $report = WitnessReport::findOrFail($id);
    $admin  = auth('admin')->user(); 

    $roleName = null;
    if ($admin && $admin->admin_role_id) {
        $role = AdminRole::where('id', $admin->admin_role_id)->first();
        $roleName = $role->name ?? null;
    }
    $report->status       = (int) $request->status;
    $report->approve_by   = $admin->id;
    $report->approve_role = $roleName;     
    $report->approve_date = now()->format('Y-m-d');
    $report->save();

    return response()->json(['success' => true]);
}

}
