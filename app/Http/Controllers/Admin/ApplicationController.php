<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Application;
use App\Model\Admin;
use App\Model\ApplicationAssessment;
use App\Model\ApplicationChecklist;
use App\Model\ApplicationPaymentDetail;
use App\Model\ApplicationAssessmentStatus;
use App\Model\SchemeChecklist;
use App\Model\ClauseHistory;
use App\Model\FindingHistory;
use App\Model\Assessor;
use App\Model\SchemeArea;
use App\Model\Scheme;
use App\Model\Room;
use App\Model\RoomUser;
use App\Model\ApplicationWitness;
use App\User;
use App\CPU\Helpers;
use Carbon\Carbon;

class ApplicationController extends Controller
{

    public function index(Request $request){
        $query_param = [];
        $search = $request['search'];
        $status = $request->status;

        $baseQuery = Application::query();

        if (auth('admin')->user()->admin_role_id != 1) {
            $baseQuery->where('auditor_id', auth('admin')->user()->id);
        }

        if ($search) {

            $words = explode(' ', trim($search));

            $baseQuery->where(function ($q) use ($search, $words) {

                $q->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('organization', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('scheme', function ($q4) use ($search) {
                        $q4->where('title', 'like', "%{$search}%");
                    });

                foreach ($words as $value) {
                    $q->orWhere('reference_number', 'like', "%{$value}%")
                        ->orWhereHas('company', function ($q2) use ($value) {
                            $q2->where('name', 'like', "%{$value}%")
                                ->orWhere('organization', 'like', "%{$value}%")
                                ->orWhere('address', 'like', "%{$value}%");
                        })
                        ->orWhereHas('user', function ($q3) use ($value) {
                            $q3->where('name', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%")
                                ->orWhere('email', 'like', "%{$value}%");
                        })
                        ->orWhereHas('scheme', function ($q4) use ($value) {
                            $q4->where('title', 'like', "%{$value}%");
                        });
                }
            });

            $query_param['search'] = $search;
        }

        $count_all      = (clone $baseQuery)->count();
        $count_pending  = (clone $baseQuery)->where('is_accept', 0)->count();
        $count_accepted = (clone $baseQuery)->where('is_accept', 1)->count();
        $count_rejected = (clone $baseQuery)->where('is_accept', 2)->count();

        if ($status !== null && $status !== '') {
            $baseQuery->where('is_accept', $status);
            $query_param['status'] = $status;
        }

        $applications = $baseQuery
            ->latest()
            ->paginate(Helpers::pagination_limit())
            ->appends($query_param);

        return view('admin-views.applicants.assessment', compact(
            'applications',
            'search',
            'count_all',
            'count_pending',
            'count_accepted',
            'count_rejected',
            'status'
        ));
    }

    public function update_status(Request $request)
    {

        if ($request['status'] == 2) {

            Application::where(['id' => $request['id']])->update([
                'is_accept' => $request['status'],
                'status' => 'reject'
            ]);
        } else {
            Application::where(['id' => $request['id']])->update([
                'is_accept' => $request['status']
            ]);
        }

        return response()->json([], 200);
    }
    public function update_accessor(Request $request)
    {
        $application_status = (isset($request->assessment_type) && ($request->assessment_type != 'pending')) ? $request->assessment_type : 'document_review';

        $application = Application::find($request['id']);
        if($request['type'] == 'Assessor') {
            Application::where(['id' => $request['id']])->update([
                'auditor_id' => $request['auditor_id'],
                'auditor_team_ids' => !empty($request['other_auditor_id']) ? implode(',', $request['other_auditor_id']) : null,
                'auditor_status' => 0,
                'client_auditor_team_status' => 0,
                'application_status' => $application_status
            ]);
            $teamLeaderName = Admin::find($request['auditor_id'])->name ?? '-';
            $reportingTeamName = implode(', ', Admin::whereIn('id', $request['other_auditor_id'])->get()->pluck('name')->toArray() ?? []);
            $applicationCheckList = ApplicationChecklist::where('application_id', $request['id'])->first();
            if($applicationCheckList) {
                Helpers::updateTeamLeadNdMemberName($applicationCheckList, $application->scheme_id, $teamLeaderName, $reportingTeamName);
            }
            $applicationAssessment = ApplicationAssessment::where('application_id', $request['id'])->first();
            if($applicationAssessment) {
                Helpers::updateApplicationAssesTeamLeadNdMemberName($applicationAssessment, $application->scheme_id, $teamLeaderName, $reportingTeamName);
            }
        }else if($request['type'] == 'office_assessment'){
            Application::where(['id' => $request['id']])->update([
                'office_assessment_id' => $request['auditor_id'],
                'office_assessment_team_ids' => !empty($request['other_auditor_id']) ? implode(',', $request['other_auditor_id']) : null,
                'office_assessment_status' => 0,
                'client_office_assessment_team_status' => 0,
                'application_status' => 'office_assessment'
            ]);
        }else if($request['type'] == 'witness_assessment'){
            Application::where(['id' => $request['id']])->update([
                'witness_assessment_id' => $request['auditor_id'],
                'witness_assessment_team_ids' => !empty($request['other_auditor_id']) ? implode(',', $request['other_auditor_id']) : null,
                'witness_assessment_status' => 0,
                'client_witness_assessment_team_status' => 0,
                'application_status' => 'witness_assessment'
            ]);
        }else if($request['type'] == 'Quality'){
            Application::where(['id' => $request['id']])->update([
                'quality_check_id' => $request['auditor_id'],
                'quality_check_team_ids' => !empty($request['other_auditor_id']) ? implode(',', $request['other_auditor_id']) : null,
                'quality_status' => 0,
                'application_status' => $application_status
            ]);

            $application_id = $request['id'];
            $room_type = 'quality';

            $room = Room::where(['application_id' => $application_id, 'room_type' => $room_type])->first();
            if($room) {
                $roomuser = RoomUser::where(['room_id' => $room->id, 'user_id' => $request['auditor_id']])->first();
                if(!$roomuser) {
                    $new = new RoomUser;
                    $new->room_id = $room->id;
                    $new->user_id = $request['auditor_id'];
                    $new->save();
                }

                foreach($request['other_auditor_id'] as $id) {
                    $roomuser = RoomUser::where(['room_id' => $room->id, 'user_id' => $id])->first();
                    if(!$roomuser) {
                        $new = new RoomUser;
                        $new->room_id = $room->id;
                        $new->user_id = $id;
                        $new->save();
                    }   
                }
            }
        }else if($request['type'] == 'Accreditation') {
            Application::where(['id' => $request['id']])->update([
                'accreditation_id' => $request['auditor_id'],
                'accreditation_team_ids' => !empty($request['other_auditor_id']) ? implode(',', $request['other_auditor_id']) : null,
                'accreditation_status' => 0,
                'application_status' => $application_status,
            ]);
        } else {
           Application::where(['id' => $request['id']])->update([
                'allot_date' => $request['allotted_date'],
                'mode_of_auditor' => $request['mode_of_auditor'],
                'auditor_id' => $request['auditor_id'],
                'auditor_team_ids' => !empty($request['other_auditor_id']) ? implode(',', $request['other_auditor_id']) : null,
                'status' => 'schedule',
                'auditor_status' => 0,
                'client_auditor_team_status' => 0,
                'application_status' => $application_status,
            ]); 
        } 


        return response()->json([], 200);
    }

    // public function get_accessor_list(Request $request){

    //     $application = Application::find($request['id']);

    //     $schemeId = $application->scheme_id;

    //     $assessors = Assessor::with(['assessor'])
    //                     ->whereRaw("FIND_IN_SET(?, scheme_id)", [$schemeId])
    //                     ->get();
    //     $acc_html = '<option value="">-- Select --</option>';
    //     foreach($assessors as $assessor) {
    //         $acc_html .= '<option value="' . $assessor->assessor->id . '" '. ($application->auditor_id == $assessor->assessor->id ? 'selected' : '') .'>' . $assessor->assessor->name . ' </option>';
    //     }

    //     return response()->json(['acc_html' => $acc_html, 'application' => $application], 200);
    // }


    public function get_accessor_list(Request $request){
        $application = Application::find($request->id);
        $type = isset($request->type) ? $request->type : 'Assessor';

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        $schemeId = $application->scheme_id;

     //   $assessors = Assessor::with('assessor')->whereRaw("FIND_IN_SET(?, scheme_id)", [$schemeId])->get();

            $assessors = Assessor::with('assessor')
            ->where('profile_status', 1)
            ->whereHas('assessor') 
            ->whereRaw("FIND_IN_SET(?, scheme_id)", [$schemeId])
            ->get();

        if($type == 'Assessor'){

    
            $selectedLead = (int) ($application->auditor_id ?? 0);
            $selectedOthers = [];
            if (!empty($application->auditor_team_ids)) {
                $selectedOthers = array_map('intval', array_filter(array_map('trim', explode(',', $application->auditor_team_ids))));
            }
    
            $acc_html = '<option value="">-- Select --</option>';
            foreach ($assessors as $item) {
                $id = (int) $item->assessor->id;
                $name = htmlspecialchars($item->assessor->name, ENT_QUOTES, 'UTF-8');
                $selected = ($id === $selectedLead) ? ' selected' : '';
                $acc_html .= "<option value=\"{$id}\"{$selected}>{$name}</option>";
            }
    
            $other_html = '';
            foreach ($assessors as $item) {
                $id = (int) $item->assessor->id;
                // if ($id === $selectedLead) continue; 
                $name = htmlspecialchars($item->assessor->name, ENT_QUOTES, 'UTF-8');
                $sel = in_array($id, $selectedOthers, true) ? ' selected' : '';
                $other_html .= "<option value=\"{$id}\"{$sel}>{$name}</option>";
            }
    
            return response()->json([
                'acc_html' => $acc_html,
                'other_html' => $other_html,
                'application' => [
                    'id' => $application->id,
                    'auditor_id' => $application->auditor_id,
                    'other_auditor_id' => $application->auditor_team_ids,
                    'assessment_type'=> $application->application_status,
                    'allotted_date'=> $application->allot_date,
                    'mode_of_auditor'=> $application->mode_of_auditor,
                ]
            ], 200);

        } 
        if($type == 'Quality'){

            $assessors = Admin::where('admin_role_id',2)->get();
    
            $selectedLead = (int) ($application->quality_check_id ?? 0);
            $selectedOthers = [];
            if (!empty($application->quality_check_team_ids)) {
                $selectedOthers = array_map('intval', array_filter(array_map('trim', explode(',', $application->quality_check_team_ids))));
            }
    
            $acc_html = '<option value="">-- Select --</option>';
            foreach ($assessors as $item) {
                $id = (int) $item->id;
                $name = htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');
                $selected = ($id === $selectedLead) ? ' selected' : '';
                $acc_html .= "<option value=\"{$id}\"{$selected}>{$name}</option>";
            }
    
            $other_html = '';
            foreach ($assessors as $item) {
                $id = (int) $item->id;
                // if ($id === $selectedLead) continue; 
                $name = htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');
                $sel = in_array($id, $selectedOthers, true) ? ' selected' : '';
                $other_html .= "<option value=\"{$id}\"{$sel}>{$name}</option>";
            }
    
            return response()->json([
                'acc_html' => $acc_html,
                'other_html' => $other_html,
                'application' => [
                    'assessment_type'=> $application->application_status,
                    'id' => $application->id,
                    'auditor_id' => $application->quality_check_id,
                    'other_auditor_id' => $application->quality_check_team_ids
                ]
            ], 200);

        } 
        if($type == 'Accreditation'){

            $assessors = Admin::where('admin_role_id',5)->get();
    
            $selectedLead = (int) ($application->accreditation_id ?? 0);
            $selectedOthers = [];
            if (!empty($application->accreditation_team_ids)) {
                $selectedOthers = array_map('intval', array_filter(array_map('trim', explode(',', $application->accreditation_team_ids))));
            }
    
            $acc_html = '<option value="">-- Select --</option>';
            foreach ($assessors as $item) {
                $id = (int) $item->id;
                $name = htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');
                $selected = ($id === $selectedLead) ? ' selected' : '';
                $acc_html .= "<option value=\"{$id}\"{$selected}>{$name}</option>";
            }
    
            $other_html = '';
            foreach ($assessors as $item) {
                $id = (int) $item->id;
                // if ($id === $selectedLead) continue; 
                $name = htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');
                $sel = in_array($id, $selectedOthers, true) ? ' selected' : '';
                $other_html .= "<option value=\"{$id}\"{$sel}>{$name}</option>";
            }
    
            return response()->json([
                'acc_html' => $acc_html,
                'other_html' => $other_html,
                'application' => [
                    'assessment_type'=> $application->application_status,
                    'id' => $application->id,
                    'auditor_id' => $application->accreditation_id,
                    'other_auditor_id' => $application->accreditation_team_ids
                ]
            ], 200);

        } 

        if($type == 'office_assessment') {
            $html = '';
            foreach ($assessors as $admin) {
                $html .= '<option value="'.$admin->assessor->id.'">'.$admin->assessor->name.'</option>';
            }
            $other_html = '';
            foreach ($assessors as $admin) {
                $other_html .= '<option value="'.$admin->assessor->id.'">'.$admin->assessor->name.'</option>';
            }
            return response()->json([
                'acc_html' => $html,
                'other_html' => $other_html,
                'application' => [
                    'assessment_type'=> $application->application_status,
                    'id' => $application->id,
                    'auditor_id' => $application->office_assessment_id,
                    'other_auditor_id' => $application->office_assessment_team_ids
                ]
            ], 200);
        }
        if($type == 'witness_assessment') {
            $html = '';
            foreach ($assessors as $admin) {
                $html .= '<option value="'.$admin->assessor->id.'">'.$admin->assessor->name.'</option>';
            }
            $other_html = '';
            foreach ($assessors as $admin) {
                $other_html .= '<option value="'.$admin->assessor->id.'">'.$admin->assessor->name.'</option>';
            }
            return response()->json([
                'acc_html' => $html,
                'other_html' => $other_html,
                'application' => [
                    'assessment_type'=> $application->application_status,
                    'id' => $application->id,
                    'auditor_id' => $application->witness_assessment_id,
                    'other_auditor_id' => $application->witness_assessment_team_ids
                ]
            ], 200);
        }

    }

    public function approved_application(Request $request)
    {
        $query_param = [];
        $search = $request->search;
        $status = $request->status ?? 'schedule';
        $type = $request->type ?? '';

        // $baseQuery = Application::query()
        //     ->when(auth('admin')->user()->admin_role_id != 1, function ($q) {
        //         $q->where('auditor_id', auth('admin')->user()->id);
        //     });

        $admin = auth('admin')->user();

        $baseQuery = Application::query()
            ->when($admin->admin_role_id == 2, function ($q) use ($admin) {
                $q->where('quality_check_id', $admin->id);
            })
            ->when($admin->admin_role_id == 3, function ($q) use ($admin) {
                $q->where('auditor_id', $admin->id);
            })
            ->when($admin->admin_role_id == 5, function ($q) use ($admin) {
                $q->where('accreditation_id', $admin->id);
            });

        if(!empty($type)){
            if($type == 'initial'){
                $baseQuery->where('application_type', 'Initial accreditation');
            }
            if($type == 're-accreditation'){
                $baseQuery->where('application_type', 'Re-accreditation');
            }
            if($type == 'surveillance'){
                $baseQuery->where('is_surveillance', 1);
            }
        }    

        if (!empty($search)) {

            $key = explode(' ', trim($search));

            $baseQuery->where(function ($q) use ($key) {

                foreach ($key as $value) {

                    $q->orWhereHas('user', function ($u) use ($value) {
                        $u->where('name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    });

                    $q->orWhereHas('company', function ($c) use ($value) {
                        $c->where('name', 'like', "%{$value}%")
                            ->orWhere('organization', 'like', "%{$value}%")
                            ->orWhere('address', 'like', "%{$value}%");
                    });

                    $q->orWhereHas('scheme', function ($s) use ($value) {
                        $s->where('title', 'like', "%{$value}%");
                    });

                    $q->orWhereHas('auditor', function ($a) use ($value) {
                        $a->where('name', 'like', "%{$value}%");
                    });

                    $q->orWhereHas('quality', function ($qc) use ($value) {
                        $qc->where('name', 'like', "%{$value}%");
                    });

                    $q->orWhereHas('accreditation', function ($ac) use ($value) {
                        $ac->where('name', 'like', "%{$value}%");
                    });
                }
            });

            $query_param['search'] = $search;
        }

        $statusCounts = [
            'schedule'        => (clone $baseQuery)->where('status', 'schedule')->count(),
            'in_process'      => (clone $baseQuery)->where('status', 'in_process')->count(),
            'quality_check'   => (clone $baseQuery)->where('status', 'quality_check')->count(),
            'non_conformance' => (clone $baseQuery)->where('status', 'non_conformance')->count(),
            'reject'          => (clone $baseQuery)->where('status', 'reject')->count(),
            'complete'        => (clone $baseQuery)->where('status', 'complete')->count(),
        ];
        $applicationCounts = [
            'all'        => Application::count(),
            'initial'      => Application::where('application_type', 'Initial accreditation')->count(),
            're-accreditation'   => Application::where('application_type', 'Re-accreditation')->count(),
            'surveillance'        => Application::where('is_surveillance', 1)->count(),
        ];

        $applications = (clone $baseQuery)
            ->where('status', $status)
            ->latest()
            ->paginate(Helpers::pagination_limit())
            ->appends($query_param);

        return view('admin-views.applicants.approved', compact(
            'applications',
            'search',
            'status',
            'statusCounts',
            'applicationCounts','type'
        ));
    }

    public function view_detail(Request $request, Application $application){
        $qualityIds = $application->quality_team?->pluck('id')->toArray();
        $qualityIds[] = $application->quality?->id;

        $accreditationIds = $application?->accreditation_team?->pluck('id')->toArray();
        $accreditationIds[] = $application?->accreditation?->id;

        $qualityStatus = 0;
        $accreditationStatus = 0;
        $qualityCheck = ApplicationAssessmentStatus::where('type','quality_check')->where('application_id',$application->id)->first();
        $accreditationCheck = ApplicationAssessmentStatus::where('type','accreditation_committee')->where('application_id',$application->id)->first();

        if($qualityCheck){
            $qualityStatus = $qualityCheck->status ;
        }
        if($accreditationCheck){
            $accreditationStatus = $qualityCheck->status ;
        }

        return view('admin-views.applicants.assessment-details', compact('application', 'qualityIds', 'accreditationIds','qualityStatus','accreditationStatus'));
    }

    public function updateComment(Request $request)
    {

        $checkList = ApplicationChecklist::find($request['checklist_id']);

        if ($checkList) {

            $oldRequirements = $checkList->requirements;
            $oldClauseData = $this->findClause($oldRequirements, $request['clause_no']);

            if (!$oldClauseData) {
                $response['status'] = false;
                $response['message'] = 'Clause not found';

                return response()->json($response);
            }

            $comment_by = auth('admin')->user()->name ?? 'Team Lead';

            $updated_requirements = $this->updateClause($oldRequirements, $request['clause_no'], $request['compliance'], $request['team_leader_comments'], $comment_by);

            $newClauseData = $this->findClause($updated_requirements, $request['clause_no']);


            $history = new ClauseHistory();
            $history->checklist_id = $request['checklist_id'];
            $history->clause_no = $request['clause_no'];
            $history->old_data = $oldClauseData;
            $history->new_data = $newClauseData;
            $history->update_type = 'Team Lead';
            $history->update_by = auth('admin')->user()->name ?? 'user';
            $history->save();

            // $checkList->general_info = $request['general_info'];
            $checkList->requirements = $updated_requirements;
            $checkList->save();

            $response['status'] = true;
            $response['message'] = 'Update successfully';
            $response['data'] = $checkList;
        } else {
            $response['status'] = false;
            $response['message'] = 'Something went wrong';
            $response['data'] = [];
        }
        return response()->json($response);
    }

    private function findClause($data, $clause_no)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                // Match found
                if (isset($value['clause_no']) && $value['clause_no'] == $clause_no) {
                    return $value;
                }

                // Continue searching deeper
                $found = $this->findClause($value, $clause_no);
                if ($found) {
                    return $found;
                }
            }
        }

        return null;
    }

    private function updateClause($data, $clause_no, $compliance, $team_leader_comments, $comment_by)
    {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {

                if (isset($value['clause_no']) && $value['clause_no'] == $clause_no) {
                    $value['compliance'] = $compliance;
                    $value['team_leader_comments'] = $team_leader_comments;
                    $value['comment_by'] = $comment_by;
                }

                $value = $this->updateClause($value, $clause_no, $compliance, $team_leader_comments, $comment_by);
            }
        }

        return $data;
    }

    public function getCommentHistory(Request $request)
    {

        $checkHistory = ClauseHistory::where('checklist_id', $request['checklist_id'])
            ->where('clause_no', $request['clause_no'])
            ->get();

        if ($checkHistory->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No Data Found', 'data' => []]);
        }

        return response()->json(['status' => true, 'message' => 'success', 'data' => $checkHistory]);
    }
    public function getFindingHistory(Request $request)
    {

        $checkHistory = FindingHistory::where('finding_id', $request['finding_id'])
                                ->get();

        if ($checkHistory->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No Data Found', 'data' => []]);
        }

        return response()->json(['status' => true, 'message' => 'success', 'data' => $checkHistory]);
    }

    public function updateAdminRemark(Request $request)
    {
        $application = Application::find($request['application_id']);

        $application->remark = $request['remark'];
        $application->save();

        return response()->json(['status' => true, 'message' => 'Update Remark Successfully']);
    }


    public function updatePaymentStatus(Request $request)
    {

        $application = Application::find($request['applicationId']);

        if ($application) {
            $check = ApplicationPaymentDetail::where('application_id', $request['applicationId'])->first();

            if (!$check) {
                $check = new ApplicationPaymentDetail();
            }

            if ($request['type'] == 'application_fee') {
                $check->application_fee_status = $request['status'];
                $check->document_fee_status = ($request['status'] == 1) ? 3 : null; // 3 for acitve next payment on application for paynow button 
            }

            if ($request['type'] == 'document_fee') {
                $check->document_fee_status = $request['status'];
                $check->assessment_fee_status = ($request['status'] == 1) ? 3 : null; // 3 for acitve next payment on application for paynow button
            }

            if ($request['type'] == 'assessment_fee') {
                $check->assessment_fee_status = $request['status'];
                $check->technical_assessment_fee_status = ($request['status'] == 1) ? 3 : null; // 3 for acitve next payment on application for paynow button
            }

            if ($request['type'] == 'technical_assessment_fee') {
                $check->technical_assessment_fee_status = $request['status'];
            }

            $check->save();

            $response['status'] = true;
            $response['message'] = 'Update Status Successfully';
        } else {
            $response['status'] = false;
            $response['message'] = 'Application not found';
        }

        return response()->json($response);
    }

    public function updateApplicationStatus(Request $request){

        $authId = auth('admin')->user()->id;


        if($request['type'] == 'quality_check'){
            
            $check = Application::where(function($q) use ($authId) {
                                        $q->where('quality_check_id', $authId)
                                        ->orWhereRaw('FIND_IN_SET(?, quality_check_team_ids)', [$authId]);
                                    })
                                ->where('id', $request['application_id'])
                                ->first();

            $application_status = ($request['status'] == 1) ? 'quality_check' : 'accreditation_committee';
        }

        if($request['type'] == 'accreditation_committee'){
            
            $check = Application::where(function($q) use ($authId) {
                                        $q->where('accreditation_id', $authId)
                                        ->orWhereRaw('FIND_IN_SET(?, accreditation_team_ids)', [$authId]);
                                    })
                                ->where('id', $request['application_id'])
                                ->first();

            $application_status = ($request['status'] == 1) ? 'accreditation_committee' : 'complete';
        }

        if($check){
            $status = ApplicationAssessmentStatus::where('application_id', $request['application_id'])
                            ->where('type', $request['type'])
                            ->first();

            if((!$status) && $request['status'] == 2){
                $response['status'] = false;
                $response['message'] = 'Application yet not started';

                return response()->json($response);
            }
            if(!$status){
                $status = new ApplicationAssessmentStatus();
                $status->type = $request['type'];
            }


            $status->application_id = $request['application_id'];
            $status->status = 0;
            if($request['status'] == 1){
                $status->start_date = $request['date'] ?? Carbon::now()->format('Y-m-d');
                $status->start_date_time = Carbon::now()->format('Y-m-d H:i:s');
                // $status->start_selfie = ImageManager::uploadMedia('media/','png', $request->file('image'));
                $status->status = 1;
                $status->start_by = $authId;
                $status->remark = $request['remark'] ?? null;
                // $status->start_latitude = $request['latitude'];
                // $status->start_longitude = $request['longitude'];

                $check->status = ($application_status == 'quality_check') ? 'quality_check' : 'non_conformance';
                $check->application_status = $application_status;
                if($request['type'] == 'quality_check'){
                    $check->quality_status = 1;
                    $check->quality_check_remark = $request['remark'] ?? null;
                } else if($request['type'] == 'accreditation_committee'){
                    $check->accreditation_status = 1;
                    $check->accreditation_remark = $request['remark'] ?? null;
                }
                $check->save();

                $message = 'Application started successfully';
            }
            if($request['status'] == 2){
                $status->end_date = $request['date'] ?? Carbon::now()->format('Y-m-d');
                $status->end_date_time = Carbon::now()->format('Y-m-d H:i:s');
                // $status->end_selfie = ImageManager::uploadMedia('media/','png', $request->file('image'));
                $status->status = 2;
                $status->end_by = $authId;
                $status->remark = $request['remark'] ?? null;
                // $status->end_latitude = $request['latitude'];
                // $status->end_longitude = $request['longitude'];

                $check->status = ($application_status == 'accreditation_committee') ? 'non_conformance' : 'complete';
                $check->application_status = $application_status;
                if($request['type'] == 'quality_check'){
                    $check->quality_status = 2;
                    $check->quality_check_remark = $request['remark'] ?? null;
                } else if($request['type'] == 'accreditation_committee'){
                    $check->accreditation_status = 2;
                    $check->accreditation_remark = $request['remark'] ?? null;
                }
                $check->save();

                $message = 'Application Ended successfully';
            }

            $status->save();

            if($status){
                $response['status'] = true;
                $response['message'] = $message;
            } else {
                $response['status'] = false;
                $response['message'] = 'Something went wrong';
            }
        } else {
            $response['status'] = false;
            $response['message'] = 'Application not found';
        }
        
        return response()->json($response);                 
    }

    public function startSurveillance(Request $request){

        $application = Application::find($request['application_id']);
        
        if($application){
            $assessment = ApplicationAssessmentStatus::where('application_id',$request['application_id'])
                            ->where('type','!=','document_review')
                            ->delete();

            $application->office_assessment_id = null;
            $application->office_assessment_team_ids = null;
            $application->office_assessment_status = null;
            $application->client_office_assessment_team_status = null;
            $application->client_office_assessment_team_remark = null;

            $application->witness_assessment_id = null;
            $application->witness_assessment_team_ids = null;
            $application->witness_assessment_status = null;
            $application->client_witness_assessment_team_status = null;
            $application->client_witness_assessment_team_remark = null;

            $application->application_status = 'office_assessment';
            $application->status = 'in_process';

            $application->quality_check_id = null;
            $application->quality_check_team_ids = null;
            $application->quality_status = null;
            $application->quality_check_remark = null;

            $application->accreditation_id = null;
            $application->accreditation_team_ids = null;
            $application->accreditation_status = null;
            $application->accreditation_remark = null;

            $application->is_surveillance = 1;

            $application->save();

            $response['status'] = true;
            $response['message'] = 'Surveillance Started Successfully';

        } else {
            $response['status'] = false;
            $response['message'] = 'Application not found';
        }
         return response()->json($response);
    }


    public function clientWitnessList(Request $request){

        $query_param = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $categories = ApplicationWitness::with('application','client')
                ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('application_number', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $categories = ApplicationWitness::with('application','client');
        }

        $categories = $categories->orderBy('id','DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.witness.list', compact('categories','search'));
    }

    public function getApplication(Request $request)
    {
        $search = $request->q;

        if(strlen($search) < 3){
            return response()->json([]);
        }

        $data = Application::with('company')
                ->where(function ($q) use ($search) {
                    $q->where('reference_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
                })
                ->limit(20)
                ->get();

        $formatted = [];

        foreach ($data as $center) {
            $formatted[] = [
                "id"   => $center->id,
                "text" => $center->reference_number 
                        . " (" . ucfirst($center->company->name ?? '-') . ") "
            ];
        }

        return response()->json($formatted);
    }
    public function getClients(Request $request)
    {
        $search = $request->q;

        if(strlen($search) < 3){
            return response()->json([]);
        }

        $data = User::with('company')
                ->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
                })
                ->limit(20)
                ->get();

        $formatted = [];

        foreach ($data as $center) {
            $formatted[] = [
                "id"   => $center->id,
                "text" => ucfirst($center->name) 
                        . " (" . ucfirst($center->company->name ?? '-') . ") "
            ];
        }

        return response()->json($formatted);
    }

    public function storeWitness(Request $request){

        $application = Application::find($request['application_id']);

        $checkList = SchemeChecklist::where('scheme_id', $application['scheme_id'])->first();

        $witness = new ApplicationWitness();
        $witness->application_id = $request['application_id']; 
        $witness->scheme_id = $application['scheme_id'] ?? null; 
        $witness->application_number = $application['reference_number'] ?? null; 
        $witness->application_type = $request['application_type']; 
        $witness->client_id = $request['client_id']; 
        $witness->standards = $request['standards']; 
        $witness->area = $request['area']; 
        $witness->due_before_date = $request['due_before_date']; 
        $witness->remark = $request['remark']; 

        $witness->witness_assessment = $checkList['witness_assessment'] ?? null; 
        $witness->witness_findings = $checkList['witness_findings'] ?? null; 
        $witness->witness_status = 0; 

        $witness->save();

        return redirect()->back();
    }

public function updateWitnessTeam(Request $request)
{
    $request->validate([
        'witness_id'       => 'required|exists:application_witnesses,id',
        'auditor_id'       => 'required|integer',
        'other_auditor_id' => 'nullable|array',
    ]);

    $witness = ApplicationWitness::findOrFail($request->witness_id);

    $application = Application::find($witness->application_id);

    
    $witness->auditor_id = $request->auditor_id;

    
    if ($request->filled('other_auditor_id') && is_array($request->other_auditor_id)) {
        $auditor_team_ids = implode(',', $request->other_auditor_id);
    } else {
       
        $auditor_team_ids = null;
    }
    
    $witness->auditor_team_ids = $auditor_team_ids;
   
    $witness->auditor_team_status = 0;
    $witness->client_auditor_team_status = 0;

    $witness->save();

    if($application){
        $application->witness_assessment_id = $request->auditor_id;
        $application->witness_assessment_team_ids = $auditor_team_ids ?? null;
        $application->witness_assessment_status = 0;
        $application->client_witness_assessment_team_status = 0;
        $application->save();
    }

    return response()->json([
        'status'  => true,
        'message' => 'Witness team updated successfully'
    ]);
}



public function viewWitness($id, $tab = 'details')
{
    $check = ApplicationWitness::with(
        'client:id,name,phone',
        'auditor:id,name,phone',
        'scheme:id,title,code'
    )->get();

    $witness = $check->firstWhere('id', $id);
    if (!$witness) {
        abort(404);
    }

    $auditorTeam = collect();
    if (!empty($witness->auditor_team_ids)) {
        $auditorTeam = Admin::whereIn(
            'id',
            explode(',', $witness->auditor_team_ids)
        )->select('id','name','phone')->get();
    }

    $witnessAssessment = $witness->witness_assessment ?? [];
    $witnessFindings   = $witness->witness_findings ?? [];

    $allowedTabs = ['details','assessment','findings'];
    if (!in_array($tab, $allowedTabs)) {
        $tab = 'details';
    }

    return view(
        'admin-views.witness.witness-view',
        compact(
            'witness',
            'auditorTeam',
            'witnessAssessment',
            'witnessFindings',
            'tab'
        )
    );
}





}
