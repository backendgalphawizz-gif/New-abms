<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Models\IsoStandard;
use App\Support\IsoChecklistTemplates;
use App\Model\Training;
use App\Model\TrainingAttempt;
use Illuminate\Http\Request;
use App\Model\ApplicationChecklist;
use App\Model\ApplicationAssessmentStatus;
use App\Model\Application;
use App\Model\ApplicationDocument;
use App\Model\ApplicationAssessment;
use App\Model\AssessmentFinding;
use App\Model\ApplicationWitness;
use App\Model\FindingHistory;
use App\Model\ClauseHistory;
use App\Model\WitnessReport;
use App\Model\Admin;

use App\Model\Room;
use App\Model\RoomUser;
use App\Model\RoomMessage;
use App\Model\RoomUserMessage;

use App\Model\Assessor;
use App\Model\SuperAdmin;
use App\Model\SchemeBiotechnologyBiobank;
use App\Model\SchemeCalibrationLaboratory;
use App\Model\SchemeForensicService;  
use App\Model\SchemeHalalCertification;  
use App\Model\SchemeInspectionBody;  
use App\Model\SchemeMedicalLaboratory;  
use App\Model\SchemeMsCertificationBody;  
use App\Model\SchemePersonCertification;  
use App\Model\SchemeProductCertification;  
use App\Model\SchemeProficiencyTesting;  
use App\Model\SchemeTestingLaboratory;
use App\Model\SchemeValidationVerification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AssessorController extends Controller
{
    public $assessor;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->assessor = $request->assessor; 
            return $next($request);
        });
    }

    public function getProfile(Request $request){
        $assessor = $this->assessor;
        $assessorProfile = $assessor->assessor;
        
        $response['status'] = true;
        $response['message'] = 'sucess';
        $response['data'] = [
            'id' => $assessor->id,
            'name' => $assessor->name,
            'phone' => $assessor->phone,
            'email' => $assessor->email,
            'admin_role_id' => $assessor->admin_role_id,
            'status' => $assessor->status,
            'image' => $assessor->image,
            'image_url' => $assessor->image ? asset('storage/app/public/admin/' . $assessor->image) : "",
            'apply_designation' => optional($assessorProfile)->apply_designation,
            'highest_qualification' => optional($assessorProfile)->highest_qualification,
            'technical_area' => optional($assessorProfile)->technical_area,
            'experience' => optional($assessorProfile)->experience,
            'home_address' => optional($assessorProfile)->home_address,
            'residence_tel' => optional($assessorProfile)->residence_tel,
            'training' => optional($assessorProfile)->training,
            'specific_knowledge_gained' => optional($assessorProfile)->specific_knowledge_gained,
            'additional_information' => optional($assessorProfile)->additional_information,
            'professional_experience' => optional($assessorProfile)->professional_experience,
            'assessment_summery' => optional($assessorProfile)->assessment_summery,
            'qualification_document' => optional($assessorProfile)->qualification_document,
            'work_experience_document' => optional($assessorProfile)->work_experience_document,
            'consultancy_document' => optional($assessorProfile)->consultancy_document,
            'audit_document' => optional($assessorProfile)->audit_document,
            'training_document' => optional($assessorProfile)->training_document,
            'profile_status' => optional($assessorProfile)->profile_status,
            'profile_status_label' => $this->assessorProfileStatusLabel(optional($assessorProfile)->profile_status),
            'remark' => optional($assessorProfile)->remark,
        ];
        return response()->json($response);
    }

    private function assessorProfileStatusLabel($status): string
    {
        switch ((int) $status) {
            case 1:
                return 'approved';
            case 2:
                return 'rejected';
            default:
                return 'pending';
        }
    }

    public function isoStandards()
    {
        $standards = IsoStandard::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'sort_order'])
            ->map(function (IsoStandard $standard) {
                $template = IsoChecklistTemplates::forCode((string) $standard->code);
                $standard->setAttribute('checklist_template', $template);

                return $standard;
            });

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $standards,
        ]);
    }

    public function getChat(Request $request) {
        $assessor = $this->assessor->id;
        // $user_id = $assessor;
        $user_id = Admin::where('admin_role_id', 1)->first()->id;
        $type = $request->input('type') ?? 'auditor';
        $application_id = $request->input('application_id');
        $application = Application::find($application_id);
        $room = Room::where(['room_type' => $type, 'application_id' => $application_id])->first();

        $types = ['auditor' => 'Assessor/Auditor', 'quality' => 'Quality/Technical', 'accreditation' => 'Accreditation Committee'];

        if($room == null) {
            $userIds = [];
            $userIds = explode(',', $application->auditor_team_ids);
            $userIds[] = $application->auditor_id;
            $userIds[] = $user_id;

            $room = new Room;
            $room->title = $types[$type];
            $room->description = $types[$type];
            $room->room_type = $type;
            $room->application_id = $application_id;
            $room->save();

            $postData = [];
            foreach($userIds as $userId) {
                $postData[] = [
                    'room_id' => $room->id,
                    'user_id' => $userId
                ];
            }

            RoomUser::insert($postData);
        }

        // $user_room = RoomUser::where(['room_id' => $room->id, 'user_id' => $user_id])->first();
        $user_room_messages = RoomUserMessage::with('message.user')->where(['room_id' => $room->id, 'user_id' => $assessor])->orderBy('id', 'ASC')->get();
        return response()->json(['status' => true, "data" => $user_room_messages, 'room_id' => strval($room->id)]);
    }

    public function sendMessage(Request $request) {
        $user_id = $this->assessor->id;
        $room_id = $request->input('room_id');
        $message = $request->input('message');
        $message_type = $request->input('message_type') ?? 'text';

        $file = '';
        if ($request->has('file')) {
            $fileObj   = $request->file('file');
            $extension = $fileObj->getClientOriginalExtension();
            $file = ImageManager::upload('admin/', $extension, $request->file('file'));
        }

        $roomMessage = new RoomMessage;
        $roomMessage->room_id = $room_id;
        $roomMessage->created_by = $user_id;
        $roomMessage->message = $message;
        $roomMessage->message_type = $message_type;
        $roomMessage->file = $file;
        $roomMessage->save();

        $roomUsers = RoomUser::where(['room_id' => $room_id])->pluck('user_id')->toArray();

        $bulkData = [];
        foreach ($roomUsers as $roomUserId) {
            $bulkData[] = [
                'room_id'     => $room_id,
                'user_id'     => $roomUserId,
                'room_message_id'  => $roomMessage->id,
                'is_read'     => 0
            ];
        }
        RoomUserMessage::insert($bulkData);
        $user_room_messages = RoomUserMessage::with('message.user')->where(['room_message_id' => $roomMessage->id])->orderBy('id', 'ASC')->get();
        return response()->json(['status' => true, "data" => $user_room_messages, 'room_id' => strval($room_id)]);

    }

    public function updateResume(Request $request){
        $assessor =$this->assessor;

        $assessorProfile = Assessor::where('assessor_id',$assessor->id)->first();

        if($assessorProfile){
            $assessorProfile->apply_designation = $request['apply_designation'] ?? null;
            $assessorProfile->scheme_id = $request['scheme_id'] ?? null;
            $assessorProfile->area_qualifications = $request['area_data'] ?? null;
            $assessorProfile->scope_qualifications = $request['scope_data'] ?? null;

            $assessorProfile->highest_qualification = $request['highest_qualification'] ?? null;
            $assessorProfile->technical_area = $request['technical_area'] ?? null;
            $assessorProfile->experience = $request['experience'] ?? null;
            $assessorProfile->qualifications = $request['qualifications'] ?? null;
            $assessorProfile->professional_experience = $request['professional_experience'] ?? null;
            $assessorProfile->assessment_summery = $request['assessment_summery'] ?? null;


            $assessorProfile->passport_number = $request['passport_number'] ?? null;
            $assessorProfile->passport_validity = $request['passport_validity'] ?? null;
            $assessorProfile->passport_image = $request['passport_image'] ?? null;  // image id


            $assessorProfile->profile_status = 0;
            $assessorProfile->is_resume = 1;
            $assessorProfile->save();

            $response['status'] = true;
            $response['message'] = 'Update successfully waiting for approval';
        } else {
            $response['status'] = false;
            $response['message'] = 'something went wrong';

        }
        return response()->json($response);
    }

    public function updateProfile(Request $request){

        $assessor =$this->assessor;
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $assessor->id,
            'phone' => 'required|unique:admins,phone,' . $assessor->id

        ], [
            'name.required' => 'Name is required!',
            'email.required' => 'Email id is Required',

        ]);

        if ($validator->fails()) {

            $response = [
                'token' => "",
                'status' => false,
                'message' => "Please fill all required fields",
                'data' => [],
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        

        if ($request->has('image')) {
            $imageName = ImageManager::update('admin/', $assessor->image, 'png', $request->file('image'));
        } else {
            $imageName = $assessor->image;
        }

        if ($request['password'] != null && strlen($request['password']) > 5) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $assessor->password;
        }

        $userDetails = [
            'name' => $request->name,
            'phone' => $request->phone,
            'image' => $imageName,
            'password' => $pass,
            'updated_at' => now(),
        ];

        if(isset($request->email) && $request->email != "") {
            $userDetails['email'] = $request->email;
        }

        Admin::where(['id' => $assessor->id])->update($userDetails);
        $assessorDetails = [
            'apply_designation' => $request->input('apply_designation')
                ?? $request->input('designation')
                ?? null,
            'highest_qualification' => $request['highest_qualification'] ?? null,
            'technical_area' => $request['technical_area'] ?? null,
            'experience' => $request['experience'] ?? 0,
            'home_address' => $request['home_address'] ?? null,
        ];

        $optionalFieldMap = [
            'residence_tel' => 'residence_tel',
            'training' => 'training',
            'specific_knowledge_gained' => 'specific_knowledge_gained',
            'additional_information' => 'additional_information',
            'professional_experience' => 'professional_experience',
            'assessment_summery' => 'assessment_summery',
        ];

        foreach ($optionalFieldMap as $column => $inputKey) {
            if (Schema::hasColumn('assessors', $column)) {
                $assessorDetails[$column] = $request->input($inputKey);
            }
        }

        $documentFieldMap = [
            'qualification_document' => 'qualification_document',
            'work_experience_document' => 'work_experience_document',
            'consultancy_document' => 'consultancy_document',
            'audit_document' => 'audit_document',
            'training_document' => 'training_document',
        ];
        foreach ($documentFieldMap as $column => $inputKey) {
            if (!Schema::hasColumn('assessors', $column)) {
                continue;
            }
            if ($request->hasFile($inputKey)) {
                continue;
            }
            if (!$request->exists($inputKey)) {
                continue;
            }
            $val = $request->input($inputKey);
            $assessorDetails[$column] = ($val === '' || $val === null) ? null : $val;
        }

        Assessor::updateOrCreate(['assessor_id' => $assessor->id], $assessorDetails);

        $assessorProfileAfter = Assessor::where('assessor_id', $assessor->id)->first();
        if ($assessorProfileAfter !== null
            && Schema::hasColumn('assessors', 'profile_status')
            && Schema::hasColumn('assessors', 'remark')
            && (int) $assessorProfileAfter->profile_status !== 1
        ) {
            $assessorProfileAfter->profile_status = 0;
            $assessorProfileAfter->remark = 'Submitted for admin review.';
            $assessorProfileAfter->save();
            $this->notifySuperAdminsAuditorProfileSubmitted($assessor);
        }

        $assessor->refresh();
        $assessor->load('assessor');
        $response = [
            'status' => true,
            'message' => translate('successfully updated!'),
            'data' => [
                'id' => $assessor->id,
                'name' => $assessor->name,
                'phone' => $assessor->phone,
                'email' => $assessor->email,
                'admin_role_id' => $assessor->admin_role_id,
                'status' => $assessor->status,
                'image' => $assessor->image,
                'image_url' => $assessor->image ? asset('storage/app/public/admin/' . $assessor->image) : "",
                'apply_designation' => optional($assessor->assessor)->apply_designation,
                'highest_qualification' => optional($assessor->assessor)->highest_qualification,
                'technical_area' => optional($assessor->assessor)->technical_area,
                'experience' => optional($assessor->assessor)->experience,
                'home_address' => optional($assessor->assessor)->home_address,
                'residence_tel' => optional($assessor->assessor)->residence_tel,
                'training' => optional($assessor->assessor)->training,
                'specific_knowledge_gained' => optional($assessor->assessor)->specific_knowledge_gained,
                'additional_information' => optional($assessor->assessor)->additional_information,
                'professional_experience' => optional($assessor->assessor)->professional_experience,
                'assessment_summery' => optional($assessor->assessor)->assessment_summery,
                'qualification_document' => optional($assessor->assessor)->qualification_document,
                'work_experience_document' => optional($assessor->assessor)->work_experience_document,
                'consultancy_document' => optional($assessor->assessor)->consultancy_document,
                'audit_document' => optional($assessor->assessor)->audit_document,
                'training_document' => optional($assessor->assessor)->training_document,
                'profile_status' => optional($assessor->assessor)->profile_status,
                'profile_status_label' => $this->assessorProfileStatusLabel(optional($assessor->assessor)->profile_status),
                'remark' => optional($assessor->assessor)->remark,
            ],
        ];
        return response()->json($response, 200);
    }

    /**
     * Uploads optional profile image + documents and saves filenames.
     */
    public function uploadProfileDocuments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'qualification_document' => 'nullable|file|max:10240',
            'work_experience_document' => 'nullable|file|max:10240',
            'consultancy_document' => 'nullable|file|max:10240',
            'audit_document' => 'nullable|file|max:10240',
            'training_document' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid upload payload',
                'errors' => Helpers::error_processor($validator),
            ], 422);
        }

        $documentFieldMap = [
            'qualification_document' => 'qualification_document',
            'work_experience_document' => 'work_experience_document',
            'consultancy_document' => 'consultancy_document',
            'audit_document' => 'audit_document',
            'training_document' => 'training_document',
        ];

        $data = [];
        foreach ($documentFieldMap as $responseKey => $inputKey) {
            if (!$request->hasFile($inputKey)) {
                continue;
            }

            $file = $request->file($inputKey);
            $extension = $file->getClientOriginalExtension() ?: 'pdf';
            $data[$responseKey] = ImageManager::upload('media/', $extension, $file);
        }

        $admin = $this->assessor;
        if ($request->hasFile('image')) {
            $admin->image = ImageManager::update('admin/', $admin->image, 'png', $request->file('image'));
            $admin->save();
            $data['image'] = $admin->image;
            $data['image_url'] = $admin->image ? asset('storage/app/public/admin/' . $admin->image) : '';
        }

        if (!empty($data)) {
            $assessorProfile = Assessor::firstOrCreate(['assessor_id' => $admin->id]);

            $updates = [];
            foreach ($data as $column => $fileName) {
                if (Schema::hasColumn('assessors', $column)) {
                    $updates[$column] = $fileName;
                }
            }

            if (!empty($updates)) {
                $assessorProfile->update($updates);
            }
        }

        return response()->json([
            'status' => true,
            'message' => empty($data) ? 'No files uploaded' : 'File(s) stored successfully',
            'data' => $data,
        ]);
    }

    public function getApplications(Request $request){

        $assessor = $this->assessor;
        $type = $request->type ?? '';
        $status = $request->status ?? '';
        if($status == 'pending') {
            $statusIds = [0,1];
        } else {
            $statusIds = [2];
        }
        $application = Application::with('scheme:id,title,code','auditor:id,name,phone')
            // ->when($type=='document_review', function($q) use($assessor, $statusIds) {
            //     $q->where('auditor_id', $assessor->id)->orWhereRaw('FIND_IN_SET(?, auditor_team_ids)', [$assessor->id])->whereIn('auditor_status', $statusIds);
            // })
            // ->when($type=='office_assessment', function($q) use($assessor, $statusIds) {
            //     $q->where('office_assessment_id', $assessor->id)->orWhereRaw('FIND_IN_SET(?, office_assessment_team_ids)', [$assessor->id])->whereIn('office_assessment_status', $statusIds)->where('auditor_status', 2);
            // })
            // ->when($type=='witness_assessment', function($q)  use($assessor, $statusIds){
            //     $q->where('witness_assessment_id', $assessor->id)->orWhereRaw('FIND_IN_SET(?, witness_assessment_team_ids)', [$assessor->id])->whereIn('witness_assessment_status', $statusIds)->where('office_assessment_status', 2);
            // })
            ->when($type === 'document_review', function ($q) use ($assessor, $statusIds,$type) {
                $q->where(function ($query) use ($assessor) {
                    $query->where('auditor_id', $assessor->id)
                        ->orWhereRaw('FIND_IN_SET(?, auditor_team_ids)', [$assessor->id]);
                })
                // ->where('application_status', $type)
                ->whereIn('auditor_status', $statusIds);
            })
            ->when($type === 'office_assessment', function ($q) use ($assessor, $statusIds,$type) {
                $q->where(function ($query) use ($assessor) {
                    $query->where('office_assessment_id', $assessor->id)
                        ->orWhereRaw('FIND_IN_SET(?, office_assessment_team_ids)', [$assessor->id]);
                })
                // ->where('application_status', $type)
                ->whereIn('office_assessment_status', $statusIds)
                ->where('auditor_status', 2);
            })
            ->when($type === 'witness_assessment', function ($q) use ($assessor, $statusIds,$type) {
                $q->where(function ($query) use ($assessor) {
                    $query->where('witness_assessment_id', $assessor->id)
                        ->orWhereRaw('FIND_IN_SET(?, witness_assessment_team_ids)', [$assessor->id]);
                })
                // ->where('application_status', $type)
                ->whereIn('witness_assessment_status', $statusIds);
                // ->where('office_assessment_status', 2);
            })
            ->orderBy('id','DESC')
            ->get();

        if($application->isNotEmpty()){
            $response['status'] = true;
            $response['message'] = 'Success';
            $response['data'] = $application;
        } else {
            $response['status'] = true;
            $response['message'] = 'No data found';
            $response['data'] = [];
        }
        
        return response()->json($response);
    }
    public function getApplicationDetail(Request $request){

        $assessor =$this->assessor;

        if(!isset($request['application_id']) || empty($request['application_id'])){
            return response()->json(['status'=>false, 'message'=> 'Application not found'],403);
        }


       $application = Application::with(
                    'scheme:id,title,code',
                    'auditor:id,name,phone,witness_status',
                    'basic_info',
                    'document',
                    'checklist',
                    'company',
                    'assessmentstatus',
                    'application_assessment'
                )
                ->where(function($q) use ($assessor) {
                    // $q->where('auditor_id', $assessor->id)
                    // ->orWhereRaw('FIND_IN_SET(?, auditor_team_ids)', [$assessor->id]);
                })
                ->where('id', $request['application_id'])
                ->first();

        if($application){

            $scheme = null;

            switch ($application->scheme_id) {
                case 1:
                    $scheme = SchemeTestingLaboratory::where('application_id', $application->id)->first();
                    break;
                case 2:
                    $scheme = SchemeCalibrationLaboratory::where('application_id', $application->id)->first();
                    break;
                case 3:
                    $scheme = SchemeMedicalLaboratory::where('application_id', $application->id)->first();
                    break;
                case 4:
                    $scheme = SchemeInspectionBody::where('application_id', $application->id)->first();
                    break;
                case 5:
                    $scheme = SchemeMsCertificationBody::where('application_id', $application->id)->first();
                    break;
                case 6:
                    $scheme = SchemeProficiencyTesting::where('application_id', $application->id)->first();
                    break;
                case 7:
                    $scheme = SchemeProductCertification::where('application_id', $application->id)->first();
                    break;
                case 8:
                    $scheme = SchemePersonCertification::where('application_id', $application->id)->first();
                    break;
                case 9:
                    $scheme = SchemeForensicService::where('application_id', $application->id)->first();
                    break;
                case 10:
                    $scheme = SchemeHalalCertification::where('application_id', $application->id)->first();
                    break;
                case 11:
                    $scheme = SchemeBiotechnologyBiobank::where('application_id', $application->id)->first();
                    break;
                case 12:
                    $scheme = SchemeValidationVerification::where('application_id', $application->id)->first();
                    break;
            }
            $scheme['areas'] = isset($scheme->areas) ? $scheme->areas : null;

            $application->scheme_details = $scheme;
            if($application->auditor_team_ids){
                $application->auditor_team = $application->auditor_team; 
            }

            if($application->document){
                $application->document->legal_entity_file = $application->document->legal_entity_file;
                $application->document->logo_electronic_file = $application->document->logo_electronic_file;
                $application->document->cab_agreement_file = $application->document->cab_agreement_file;
                $application->document->assessment_checklist_file = $application->document->assessment_checklist_file;
                $application->document->standard_file = $application->document->standard_file;
                $application->document->quality_documentation_file = $application->document->quality_documentation_file;
                $application->document->relevant_associate_file = $application->document->relevant_associate_file;
                $application->document->testing_scheme_file = $application->document->testing_scheme_file;
                $application->document->proficiency_testing_file = $application->document->proficiency_testing_file;
                $application->document->requiring_accreditation_file = $application->document->requiring_accreditation_file;
                $application->document->job_description_file = $application->document->job_description_file;
                $application->document->risk_analysis_file = $application->document->risk_analysis_file;
                $application->document->technical_and_quality_file = $application->document->technical_and_quality_file;
                $application->document->signature_file = $application->document->signature_file;
                $application->document->selfie_file = $application->document->selfie_file;
                $application->document->application_fee_file = $application->document->application_fee_file;
            }
            $response['status'] = true;
            $response['message'] = 'Success';
            $response['data'] = $application;
        } else {
            $response['status'] = true;
            $response['message'] = 'No data found';
            $response['data'] = [];
        }
        
        return response()->json($response);
    }


    public function updateComment(Request $request){

        $assessor =$this->assessor;

        $check = Application::where('auditor_id',$assessor->id)->first();
        if(!$check){
            $response['status'] = false;
            $response['message'] = 'You are not eligible for the comment';

            return response()->json($response);
        }

        $checkList = ApplicationChecklist::find($request['checklist_id']);

        if($checkList){

            $oldRequirements = $checkList->requirements;
            $oldClauseData = $this->findClause($oldRequirements, $request['clause_no']);

            if(!$oldClauseData){
                $response['status'] = false;
                $response['message'] = 'Clause not found';

                return response()->json($response);
            }

            $comment_by = ($assessor) ? $assessor->name.'(Team Lead)' : 'Team Lead';

            $updated_requirements = $this->updateClause( $oldRequirements, $request['clause_no'], $request['compliance'], $request['team_leader_comments'], $comment_by);

            $newClauseData = $this->findClause($updated_requirements, $request['clause_no']);


            $history = new ClauseHistory();
            $history->checklist_id = $request['checklist_id'];
            $history->clause_no = $request['clause_no'];
            $history->old_data = $oldClauseData;
            $history->new_data = $newClauseData;
            $history->update_type = 'Team Lead';
            $history->update_by = $assessor->name ?? 'Auditor';
            $history->save();

            // $checkList->general_info = $request['general_info'];
            $checkList->requirements = $updated_requirements;
            $checkList->save();

            $response['status'] = true;
            $response['message'] = 'Update successfully';
            // $response['data'] = $checkList;
        } else {
            $response['status'] = false;
            $response['message'] = 'Something went wrong';
            // $response['data'] = [];
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

    public function checklistCommentHistory(Request $request)
    {

        $checkHistory = ClauseHistory::where('checklist_id',$request['checklist_id'])
                                    ->where('clause_no',$request['clause_no'])
                                    ->get();

        if($checkHistory->isEmpty()){
            return response()->json(['status'=>false, 'message'=>'No Data Found','data'=>[]]);

        }

        return response()->json(['status'=>true, 'message'=> 'success', 'data'=>$checkHistory]);
    }

    public function witnessRequest(Request $request){

        $assessor = $this->assessor;

        $check = WitnessReport::where('assessor_id', $request['assessor_id'])->where('status',1)->first();

        // if($check){
        //     $response['status'] = false;
        //     $response['message'] = 'Witness is already approve of this assessor';

        //     return response()->json($response);
        // }

        $witness = new WitnessReport();
        $witness->user_id = $assessor->id;
        $witness->assessor_id = $request['assessor_id'];
        $witness->role_during_assessment = $request['role_during_assessment'];
        $witness->scheme_id = $request['scheme_id'];
        $witness->cab_name = $request['cab_name'];
        $witness->location = $request['location'];
        $witness->witness_date = $request['witness_date'];
        $witness->evaluation_criteria = $request['evaluation_criteria'];
        $witness->summary = $request['summary'];
        $witness->overall_recommendation = $request['overall_recommendation'];
        $witness->training_required = $request['training_required'];
        $witness->save();

        if($witness){
            Admin::where('id',$request['assessor_id'])->update(['witness_status'=>1]);
            Assessor::where('assessor_id',$request['assessor_id'])->update(['witness_status'=>1]);

            $response['status'] = true;
            $response['message'] = 'Witness added Successfully, Waiting for admin approval';
            $response['data'] = $witness;
        } else {
            $response['status'] = false;
            $response['message'] = 'Something went wrong';
            $response['data'] = [];
        }

        return response()->json($response);
    }
    public function witnessRequestList(Request $request){

        $assessor = $this->assessor;

        $witness = WitnessReport::with('auditor:id,name,phone','scheme:id,title,code','approveby:id,name,phone')
                                ->where('user_id', $assessor->id)//$assessor->id
                                ->orderBy('id','DESC')
                                ->get();

        if($witness){
            $response['status'] = true;
            $response['message'] = 'Success';
            $response['data'] = $witness;

        } else {
            $response['status'] = false;
            $response['message'] = 'No data Found';
            $response['data'] = [];
        }
        
        return response()->json($response);
        
    }
    public function trainingList(Request $request){

        $assessor = $this->assessor;

        // $traning = Training::with('scheme:id,title,code','area:id,title,code','scopeData:id,title,code')
        //                         ->whereNull('assessor_ids')
        //                         ->where('status',1)
        //                         ->get();
        // $traning = Training::with('scheme:id,title,code','area:id,title,code','scopeData:id,title,code')
        //                         ->whereRaw('FIND_IN_SET(?, assessor_ids)', [$assessor->id])
        //                         ->where('status',1)
        //                         ->get();

        $training = Training::with('scheme:id,title,code','area:id,title,code','scopeData:id,title,code')
                            ->where('status', 1)
                            ->where(function ($q) use ($assessor) {
                                $q->whereNull('assessor_ids')
                                ->orWhereRaw('FIND_IN_SET(?, assessor_ids)', [$assessor->id]);
                            })
                            ->get();
        
        if($training->isNotEmpty()){

            $attempts = TrainingAttempt::where('user_id', $assessor->id)
                ->whereIn('training_id', $training->pluck('id'))
                ->get()
                ->keyBy('training_id'); 

               

            foreach ($training as $t) {
                $attempt = $attempts->get($t->id);
                if (!$attempt) {
                    $t->is_start = 0;   
                } elseif ($attempt->is_completed == 1) {
                    $t->is_start = 2;   
                } else {
                    $t->is_start = 1;   
                }
                $t->attempts = $attempt->total_attempt ?? 0;
            }

            $response['status'] = true;
            $response['message'] = 'Success';
            $response['data'] = $training;

        } else {
            $response['status'] = false;
            $response['message'] = 'No data Found';
            $response['data'] = [];
        }
        
        return response()->json($response);
        
    }
    public function trainingDetail(Request $request){

        $assessor = $this->assessor;

        if(!isset($request['training_id'])){
            return response()->json(["status"=>false, "message"=> "Training Id is required"]);
        }
        $traning = Training::with(
            'scheme:id,title,code',
            'area:id,title,code',
            'scopeData:id,title,code',
            'files',
            'question'
            )->find($request['training_id']);
        
        if($traning){
            $response['status'] = true;
            $response['message'] = 'Success';
            $response['data'] = $traning;

        } else {
            $response['status'] = false;
            $response['message'] = 'No data Found';
            $response['data'] = [];
        }
        
        return response()->json($response);
        
    }
    public function startTraining(Request $request){

        $assessor = $this->assessor;

        $validator = Validator::make($request->all(), [
            'training_id' => 'required'
        ], [
            'training_id.required' => 'Training is required!'
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $traning = Training::find($request['training_id']);

        if($traning){

            $check = TrainingAttempt::where('training_id', $request['training_id'])->where('user_id', $assessor->id)->first();

            if($check){
                $message = ($check->is_completed) ?  'Completed' : 'Start';
                $response['status'] = false;
                $response['message'] = 'You already '.$message.' this traininig';

                return response()->json($response);
            }

            $attempt = new TrainingAttempt();
            $attempt->training_id = $request['training_id'];
            $attempt->user_id  = $assessor->id;
            $attempt->save();

            $response['status'] = true;
            $response['message'] = 'Success';
        } else {
            $response['status'] = false;
            $response['message'] = 'No data Found';
        }
        
        return response()->json($response);
        
    }
    public function completeTraining(Request $request){

        $assessor = $this->assessor;

        $validator = Validator::make($request->all(), [
            'training_id' => 'required'
        ], [
            'training_id.required' => 'Training is required!'
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $traning = Training::find($request['training_id']);

        if($traning){

            $check = TrainingAttempt::where('training_id', $request['training_id'])->where('user_id', $assessor->id)->first();

            if(!$check){
                $response['status'] = false;
                $response['message'] = 'Training not started yet';

                return response()->json($response);
            }

            if($check->is_completed){
                $response['status'] = false;
                $response['message'] = 'You already submitted this Training';

                return response()->json($response);
            }

            $totalQuestion = count($traning->question) ?? 0;
            $attemptQuestion = count($request['data']) ?? 0;
            $correctQuestion = 0;

            for($i=0; $i<$attemptQuestion; $i++){
                $correctQuestion += ($request['data'][$i]['selected_option'] == $request['data'][$i]['correct_answer']) ? 1 : 0 ;
            }

            $check->total_questions = $totalQuestion;
            $check->attempt_questions = $attemptQuestion;
            $check->correct_answers = $correctQuestion;

            $percent = ($totalQuestion > 0) ? round(($correctQuestion / $totalQuestion) * 100) : 0;

            if ($percent >= 70) { //($totalQuestion == $attemptQuestion && $totalQuestion == $correctQuestion)
                $check->is_completed = 1;
                $status = true;
                $message = 'Congrats You Complete the training with '.$percent.'%.';
            } else {

                $status = false;
                $message ='Oops! You have to give the test again.';
            }
            $check->total_attempt += 1;
            $check->percent = $percent;

            $check->save();

            $assessor = Assessor::where('assessor_id', $assessor->id)->first();

            $scheme_id = [];
            $area_id = [];
            $scope_id = [];

            if ($percent >= 70) {
                if(!empty($assessor['scheme_id'])){
                    $scheme_id = array_map('intval', explode(',', $assessor['scheme_id']));
                }
                if(!empty($traning['scheme_id'])){
                    $scheme_id[] = (int) $traning['scheme_id'];
                    $scheme_id = array_values(array_unique($scheme_id));
                }

                if(!empty($assessor['area_id'])){
                    $area_id = array_map('intval', explode(',', $assessor['area_id']));
                }
                if(!empty($traning['area_id'])){
                    $area_id[] = (int) $traning['area_id'];
                    $area_id = array_values(array_unique($area_id));
                }

                if(!empty($assessor['scope_id'])){
                    $scope_id = array_map('intval', explode(',', $assessor['scope_id']));
                }
                if(!empty($traning['scope_id'])){
                    $scope_id[] = (int) $traning['scope_id'];
                    $scope_id = array_values(array_unique($scope_id));
                }

                $assessor->scheme_id = !empty($scheme_id) ? implode(',',$scheme_id) : $assessor->scheme_id;
                $assessor->area_id = !empty($area_id) ? implode(',',$area_id) : $assessor->area_id;
                $assessor->scope_id = !empty($scope_id) ? implode(',',$scope_id) : $assessor->scope_id;
            }    
            $assessor->save();

            $response['status'] = $status;
            $response['message'] = $message;
            $response['total_questions'] = $totalQuestion;
            $response['correct_answers'] = $correctQuestion;
        } else {
            $response['status'] = false;
            $response['message'] = 'No data Found';
        }
        
        return response()->json($response);
        
    }

    public function updateApplicationStatus(Request $request){

        $assessor = $this->assessor;

        $validator = Validator::make($request->all(), [
            'application_id' => 'required',
            'type' => 'required',
            'assessment_type' => 'required|in:document_review,office_assessment,witness_assessment',
            'latitude' => 'required',
            'longitude' => 'required',
            'witness_id'      => 'required_if:assessment_type,witness_assessment',
        ], [
            'application_id.required' => 'Application is required!',
            'type.required' => 'Status Type is required!',
            'latitude.required' => 'Location is required!',
            'longitude.required' => 'Location is required!',
            'assessment_type.required' => 'Assessment Type is required!',
            'assessment_type.in'       => 'Assessment Type must be Document review, Office assessment, or Witness assessment!',
            'witness_id.required_if'    => 'Witness is required for Witness Assessment!',
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }
        

        if($request['assessment_type'] == 'witness_assessment'){

            $check = ApplicationWitness::where(function($q) use ($assessor) {
                                            $q->where('auditor_id', $assessor->id)
                                            ->orWhereRaw('FIND_IN_SET(?, auditor_team_ids)', [$assessor->id]);
                                        })
                                        ->where('application_id', $request['application_id'])
                                        ->where('id', $request['witness_id'])
                                        ->first();
                if( isset($check) && $check->client_auditor_team_status != 1){
                    $response['status'] = false;
                    $response['message'] = 'Client has not approve your team';
        
                    return response()->json($response);
                }

            if($check){

                $status = ApplicationAssessmentStatus::where('application_id', $request['application_id'])
                                ->where('type', $request['assessment_type'])
                                ->where('status','!=',2)
                                ->first();
                              
                $application = Application::find($request['application_id']);                

                if((!$status) && $request['type'] == 'end'){
                    $response['status'] = false;
                    $response['message'] = 'Application yet not started';

                    return response()->json($response);
                }

                if(($status) && $request['type'] == 'end'){

                    $findings = AssessmentFinding::where('finding_type','witness_assessment')
                                    ->where('application_id',$request['application_id'])
                                    ->where('status',0)
                                    ->count();
                    if($findings > 0){
                        $response['status'] = false;
                        $response['message'] = 'Some Finding still not close';
    
                        return response()->json($response);
                    }                
                }


                if(!$status){
                    $status = new ApplicationAssessmentStatus();
                    $status->type = $request['assessment_type'];
                }

                $status->application_id = $request['application_id'];
                $status->status = 0;

                if($request['type'] == 'start'){
                    $status->start_date = $request['date'] ?? Carbon::now()->format('Y-m-d');
                    $status->start_date_time = Carbon::now()->format('Y-m-d H:i:s');
                    $status->start_selfie = ImageManager::uploadMedia('media/','png', $request->file('image'));
                    $status->status = 1;
                    $status->start_by = $assessor->id;
                    $status->start_latitude = $request['latitude'];
                    $status->start_longitude = $request['longitude'];
                    $status->remark = $request['remark'] ?? null;

                    $check->witness_status = 1;
                    $check->save();
                    $message = 'Application Witness started successfully';
                }
                if($request['type'] == 'end'){

                    $status->end_date = $request['date'] ?? Carbon::now()->format('Y-m-d');
                    $status->end_date_time = Carbon::now()->format('Y-m-d H:i:s');
                    $status->end_selfie = ImageManager::uploadMedia('media/','png', $request->file('image'));
                    $status->status = 2;
                    $status->end_by = $assessor->id;
                    $status->end_latitude = $request['latitude'];
                    $status->end_longitude = $request['longitude'];
                    $status->remark = $request['remark'] ?? null;

                    $check->witness_status = 2;
                    $check->save();

                    $message = 'Application Witness Ended successfully';
                }

                $status->save();

                $application->witness_assessment_status = ($request['type'] == 'start') ? 1 : 2;
                $application->save();

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

        } else {
            

            if($request['assessment_type'] == 'document_review'){
                
                $check = Application::where(function($q) use ($assessor) {
                                            $q->where('auditor_id', $assessor->id)
                                            ->orWhereRaw('FIND_IN_SET(?, auditor_team_ids)', [$assessor->id]);
                                        })
                                    ->where('id', $request['application_id'])
                                    ->first();
                
                if($check->client_auditor_team_status != 1){
                    $response['status'] = false;
                    $response['message'] = 'Client has not approve your team';
        
                    return response()->json($response);
                }

                $application_status = ($request['type'] == 'start') ? 'document_review' : 'office_assessment';
            }

            if($request['assessment_type'] == 'office_assessment'){
                
                $check = Application::where(function($q) use ($assessor) {
                                            $q->where('office_assessment_id', $assessor->id)
                                            ->orWhereRaw('FIND_IN_SET(?, office_assessment_team_ids)', [$assessor->id]);
                                        })
                                    ->where('id', $request['application_id'])
                                    ->first();
                
                if($check->client_office_assessment_team_status != 1){
                    $response['status'] = false;
                    $response['message'] = 'Client has not approve your team';
        
                    return response()->json($response);
                }

                $application_status = ($request['type'] == 'start') ? 'office_assessment' : 'office_assessment';

                $findingCount = AssessmentFinding::where('finding_type', 'office_assessment')
                                            ->where('application_id', $request['application_id'])
                                            ->where('status', 0)
                                            ->count();

                if(($findingCount > 0) && ($request['type'] == 'end')){
                    $application_status = 'quality_check';
                }                            

            }

            if($check){
                $status = ApplicationAssessmentStatus::where('application_id', $request['application_id'])
                                ->where('type', $request['assessment_type'])
                                ->first();

                if((!$status) && $request['type'] == 'end'){
                    $response['status'] = false;
                    $response['message'] = 'Application yet not started';

                    return response()->json($response);
                }

                if(($status) && $request['type'] == 'end'){

                    $findings = AssessmentFinding::where('finding_type','office_assessment')
                                    ->where('application_id',$request['application_id'])
                                    ->where('status',0)
                                    ->count();
                    if($findings > 0){
                        $response['status'] = false;
                        $response['message'] = 'Some Finding still not close';
    
                        return response()->json($response);
                    } else if($request['assessment_type'] != 'document_review'){
                        $application_status = 'quality_check';
                    }              
                }


                if(!$status){
                    $status = new ApplicationAssessmentStatus();
                    $status->type = $request['assessment_type'];
                }


                $status->application_id = $request['application_id'];
                $status->status = 0;
                if($request['type'] == 'start'){
                    $status->start_date = $request['date'] ?? Carbon::now()->format('Y-m-d');
                    $status->start_date_time = Carbon::now()->format('Y-m-d H:i:s');
                    $status->start_selfie = ImageManager::uploadMedia('media/','png', $request->file('image'));
                    $status->status = 1;
                    $status->start_by = $assessor->id;
                    $status->start_latitude = $request['latitude'];
                    $status->start_longitude = $request['longitude'];
                    $status->remark = $request['remark'] ?? null;

                    $check->status = 'in_process';
                    $check->application_status = $application_status;
                    if($request['assessment_type'] == 'document_review'){
                        $check->auditor_status = 1;
                    } else if($request['assessment_type'] == 'office_assessment'){
                        $check->office_assessment_status = 1;
                    } 
                    // else if($request['assessment_type'] == 'witness_assessment') {
                    //     $check->witness_assessment_status = 1;
                    // }
                    $check->save();

                    $message = 'Application started successfully';
                }
                if($request['type'] == 'end'){
                    $status->end_date = $request['date'] ?? Carbon::now()->format('Y-m-d');
                    $status->end_date_time = Carbon::now()->format('Y-m-d H:i:s');
                    $status->end_selfie = ImageManager::uploadMedia('media/','png', $request->file('image'));
                    $status->status = 2;
                    $status->end_by = $assessor->id;
                    $status->end_latitude = $request['latitude'];
                    $status->end_longitude = $request['longitude'];
                    $status->remark = $request['remark'] ?? null;

                    $check->status = ($application_status == 'quality_check') ? 'quality_check' : 'in_process';
                    $check->application_status = $application_status;
                    if($request['assessment_type'] == 'document_review'){
                        $check->auditor_status = 2;
                    } else if($request['assessment_type'] == 'office_assessment'){
                        $check->office_assessment_status = 2;
                    } 
                    // else if($request['assessment_type'] == 'witness_assessment') {
                    //     $check->witness_assessment_status = 2;
                    // }
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

        }

        // if($request['assessment_type'] == 'witness_assessment'){
            
        //     $check = Application::where(function($q) use ($assessor) {
        //                                 $q->where('witness_assessment_id', $assessor->id)
        //                                 ->orWhereRaw('FIND_IN_SET(?, witness_assessment_team_ids)', [$assessor->id]);
        //                             })
        //                         ->where('id', $request['application_id'])
        //                         ->first();
            
        //     if($check->client_witness_assessment_team_status != 1){
        //         $response['status'] = false;
        //         $response['message'] = 'Client has not approve your team';
    
        //         return response()->json($response);
        //     }

        //     $application_status = ($request['type'] == 'start') ? 'witness_assessment' : 'quality_check';
        // }
        
        return response()->json($response);                 
    }
    public function updateApplicationRemark(Request $request){

        $assessor = $this->assessor;

        $validator = Validator::make($request->all(), [
            'application_id' => 'required',
            'type' => 'required',
            'remark' => 'required',
            // 'status' => 'required_if:type,checklist_remark'
            'status' => 'required'

        ], [
            'application_id.required' => 'Application is required!',
            'type.required' => 'Remark type is required!',
            'remark.required' => 'Remark is required!',
            'status.required' => 'Status is required!'
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $check = Application::where(function($q) use ($assessor) {
                                    $q->where('auditor_id', $assessor->id)
                                    ->orWhereRaw('FIND_IN_SET(?, auditor_team_ids)', [$assessor->id]);
                                })
                            ->where('id', $request['application_id'])
                            ->first();
        if($check){
            
            if($request['type'] == 'document_remark'){

                $document = ApplicationDocument::where('application_id', $check->id)->first();

                if($document){
                    $document->remark = $request['remark'];
                    $document->remark_by = $assessor->id;
                    $document->status = $request['status'] ?? 0;
                    $document->save();
                }

                $message = 'Document Remark Update Successfully';
            }
            if($request['type'] == 'checklist_remark'){

                $checklist = ApplicationChecklist::where('application_id', $check->id)->first();

                if($checklist){
                    $checklist->remark = $request['remark'];
                    $checklist->remark_by = $assessor->id;
                    $checklist->status = $request['status'] ?? 0;
                    $checklist->save();
                }

                $message = 'Checklist Remark & status Update Successfully';
            }

            $response['status'] = true;
            $response['message'] = $message;
        } else {
            $response['status'] = false;
            $response['message'] = 'Application not found';
        }
        
        return response()->json($response);                 
    }

    public function updateAssessment(Request $request){
        $assessor = $this->assessor;

        $validator = Validator::make($request->all(), [
            'application_id' => 'required',
            'assessment_type' => 'required',
            // 'assessment' => 'required'
            'witness_id'      => 'required_if:assessment_type,witness_assessment',

        ], [
            'application_id.required' => 'Application is required!',
            'assessment_type.required' => 'Assessment type is required!',
            'witness_id.required_if'    => 'Witness is required for Witness Assessment!',
            // 'assessment' => 'Assessment is required!'
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        if($request['assessment_type'] == 'witness_assessment'){

            $check = ApplicationWitness::where(function($q) use ($assessor) {
                                            $q->where('auditor_id', $assessor->id)
                                            ->orWhereRaw('FIND_IN_SET(?, auditor_team_ids)', [$assessor->id]);
                                        })
                                        ->where('application_id', $request['application_id'])
                                        ->where('id', $request['witness_id'])
                                        ->first();

            if( isset($check) && $check->witness_status == 0){
                $response['status'] = false;
                $response['message'] = 'witness not start yet';
    
                return response()->json($response);
            }

            if($check){

                $check->witness_assessment = $request['assessment'] ?? [];
                $check->witness_assessment_by = $assessor->name ?? null;

                $check->save();

                $response['status'] = true;
                $response['message'] = 'Success';

            } else {
                $response['status'] = false;
                $response['message'] = 'Application not found';
            }


        } else {

            $check = Application::where(function($q) use ($assessor) {
                                        $q->where('office_assessment_id', $assessor->id)
                                        ->orWhereRaw('FIND_IN_SET(?, office_assessment_team_ids)', [$assessor->id]);
                                    })
                                ->where('id', $request['application_id'])
                                ->first();
    
            if($check){
                $assessment = ApplicationAssessment::where('application_id', $request['application_id'])->first();
    
                if ($assessment) {
    
                    switch ($request['assessment_type']) {
    
                        case 'team_lead_assessment':
                            $assessment->team_leader_assessment = $request['assessment'] ?? [];
                            $assessment->team_leader_assessment_by = $assessor->name ?? null;
                            $assessment->main_general_info = $request['main_general_info'] ?? null;
                            break;
    
                        case 'technical_assessment':
                            $assessment->technical_assessment = $request['assessment'] ?? [];
                            $assessment->technical_assessment_by = $assessor->name ?? null;
                            break;
    
                        case 'vertical_assessment':
                            $assessment->vertical_assessment = $request['assessment'] ?? [];
                            $assessment->vertical_assessment_by = $assessor->name ?? null;
                            break;
    
                        case 'witness_assessment':
                            $assessment->witness_assessment = $request['assessment'] ?? [];
                            $assessment->witness_assessment_by = $assessor->name ?? null;
                            break;
    
                        case 'attendance_form':
                            if ($request->hasFile('attendance_form')) {
                                $assessment->attendance_form = $request['attendance_form'];//ImageManager::uploadMedia('media/','png',$request->file('attendance_form'));
                            }
                            break;
    
                        default:
                            return response()->json([
                                'status' => false,
                                'message' => 'Invalid assessment type'
                            ], 422);
                    }
    
                    $assessment->save();
                }
    
                $response['status'] = true;
                $response['message'] = 'Success';
    
            } else {
                $response['status'] = false;
                $response['message'] = 'Application not found';
            }
        }

        
        return response()->json($response);  
    }

    public function addAssessmentFinding(Request $request){
        $assessor = $this->assessor;

        // dd($assessor);
        $validator = Validator::make($request->all(), [
            'application_id' => 'required',
            'finding_type' => 'required|in:office_assessment,witness_assessment',
            'witness_id'      => 'required_if:finding_type,witness_assessment',

        ], [
            'application_id.required' => 'Application is required!',
            'finding_type.required' => 'Finding Type is required!',
            'finding_type.in'       => 'Finding Type must be Office assessment, or Witness assessment!',
            'witness_id.required_if'    => 'Witness is required for Witness Assessment!',
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $check = Application::find($request['application_id']);

        if($check){
            
            $finding = new AssessmentFinding();
            $finding->application_id = $request['application_id'];
            $finding->finding_type = $request['finding_type'];
            $finding->clause_no = $request['clause_no'] ?? null;
            $finding->general_info = $request['general_info'] ?? null;
            $finding->detailed_findings = $request['detailed_findings'] ?? null;
            $finding->assessor_id = $assessor->id ?? null;
            $finding->assessor_name = $assessor->name ?? null;
            $finding->save();

            if($finding) {

                $history = new FindingHistory();
                $history->finding_id = $finding->id;
                $history->assessor_data = $request['detailed_findings'];
                $history->role = 'Assessor';
                $history->assessor_date = Carbon::now();
                $history->update_by = $assessor->name ?? null;
                $history->save();

                $response['status'] = true;
                $response['message'] = 'Success';
            } else {
                $response['status'] = false;
                $response['message'] = 'Somthing went wrong';
            }
        } else {
            $response['status'] = false;
            $response['message'] = 'Application not found';
        }

        return response()->json($response);  
    }

    public function listAssessmentFinding(Request $request, $id, $type){
        $assessor = $this->assessor;

        $check = Application::find($id);

        if($check){

            $finding = AssessmentFinding::where('application_id',$id)
                                ->where('finding_type',$type)
                                ->get();

            $response['status'] = true;
            $response['message'] = 'Success';
            $response['data'] = $finding;
            
        } else {
            $response['status'] = false;
            $response['message'] = 'Application not found';
        }

        return response()->json($response); 
    }
    public function updateFindingResponse(Request $request){
        $assessor = $this->assessor;

        $validator = Validator::make($request->all(), [
            'finding_id' => 'required',

        ], [
            'finding_id.required' => 'Finding is required!',
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $check = AssessmentFinding::find($request['finding_id']);

        if($check){

            // $oldResponse = $check->responses;
            // $newResponse = $request['responses'] ?? null ;

            $checkHistory = FindingHistory::where('finding_id',$request['finding_id'])->orderBy('id','DESC')->first();

            if($checkHistory){
                if(!$checkHistory->cab_data){
                    $response = [
                        'status' => false,
                        'message' => "Cab Yet not reply your previous action. wait for cab response"
                    ];

                    return response()->json($response);
                }
            }

            $history = new FindingHistory();
            $history->finding_id = $request['finding_id'];
            $history->assessor_data = $request['data'] ?? NUll;
            $history->role = 'Assessor';
            $history->assessor_date = Carbon::now();
            $history->update_by = $assessor->name ?? null;
            $history->save();

            $check->detailed_findings = isset($request['data']) ? $request['data'] : $check->detailed_findings;
            $check->status = ($request['status'] == 1) ? 1 : 0;
            $check->save();

            $response['status'] = true;
            $response['message'] = 'Finding Update Successfully';

        } else {
            $response['status'] = false;
            $response['message'] = 'Finding not found';
        }

        return response()->json($response); 
    }

    public function viewFindings(Request $request, $id){
        $assessor = $this->assessor;

        $check = AssessmentFinding::with('histroy')->find($id);

        if($check){

            $response['status'] = true;
            $response['message'] = 'Success';
            $response['data'] = $check;

        } else {
            $response['status'] = false;
            $response['message'] = 'Finding not found';
        }

        return response()->json($response);
    }
    
    public function deleteChat(Request $request, $id) {
        $roomMessage = RoomMessage::find($id);
        RoomUserMessage::where(['room_message_id' => $id])->delete();
        $roomMessage->delete();

        return response()->json(['status' => true, 'message' => 'Chat deleted success']);
    }
    
    public function applicationWitness(Request $request, $id){
        $assessor = $this->assessor;

        // $check = ApplicationWitness::where('application_id', $id)
        //                             ->whereRaw('FIND_IN_SET(?, audit_team_ids)', [$assessor->id])
        //                             ->where('witness_status','!=',2)
        //                             ->first();
        $check = ApplicationWitness::where('application_id', $id)
                                    ->where(function($q) use ($assessor) {
                                            $q->where('auditor_id', $assessor->id)
                                            ->orWhereRaw('FIND_IN_SET(?, auditor_team_ids)', [$assessor->id]);
                                        })
                                    ->where('witness_status','!=',2)
                                    ->first();

        if($check){

            $response['status'] = true;
            $response['message'] = 'Success';
            $response['data'] = $check;

        } else {
            $response['status'] = false;
            $response['message'] = 'Witness not found';
        }

        return response()->json($response);
    }

    public function updateWitnessAssessment(Request $request){
        $assessor = $this->assessor;

        $validator = Validator::make($request->all(), [
            'witness_id' => 'required',
            // 'assessment' => 'required'
            'assessment' => 'required'

        ], [
            'witness_id.required' => 'witness is required!',
            // 'assessment.required' => 'Assessment type is required!'
            'assessment' => 'Assessment is required!'
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $check = ApplicationWitness::where('id', $request['witness_id'])
                                    ->where(function($q) use ($assessor) {
                                            $q->where('auditor_id', $assessor->id)
                                            ->orWhereRaw('FIND_IN_SET(?, auditor_team_ids)', [$assessor->id]);
                                        })
                                    // ->whereRaw('FIND_IN_SET(?, audit_team_ids)', [$assessor->id])
                                    ->first();
                            

        if($check){

            if(isset($request['assessment'])){
                $check->witness_assessment = $request['assessment'];
            }
            $check->witness_assessment_by = $assessor->id;
            $check->save();

            $response['status'] = true;
            $response['message'] = 'Success';

        } else {
            $response['status'] = false;
            $response['message'] = 'Application not found';
        }
        
        return response()->json($response);  
    }

    private function notifySuperAdminsAuditorProfileSubmitted(Admin $auditor): void
    {
        try {
            $emails = SuperAdmin::query()
                ->whereNotNull('email')
                ->pluck('email')
                ->filter()
                ->unique()
                ->values();

            if ($emails->isEmpty()) {
                return;
            }

            $body = "Auditor {$auditor->name} ({$auditor->email}) has submitted their profile for review.\n\n"
                .'Review this auditor in Super Admin → Auditors.';

            foreach ($emails as $email) {
                Mail::raw($body, function ($message) use ($email, $auditor) {
                    $message->to($email)->subject('Auditor profile pending review: '.$auditor->name);
                });
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
