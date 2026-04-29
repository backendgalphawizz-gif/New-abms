<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Assessor;
use App\Model\Category;
use App\Model\Scheme;
use App\Model\SchemeArea;
use App\Model\Media;
use App\Model\CompanyProfile;
use App\Model\Application;
use App\Model\ApplicationAssessment;
use App\Model\ApplicationBasicDetail;
use App\Model\ApplicationDocument;
use App\Model\ApplicationWitness;
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
use App\Model\SchemeChecklist;  
use App\Model\ClauseHistory;  
use App\Model\AssessmentFinding;
use App\Model\FindingHistory;
use App\Model\ApplicationChecklistClause;  
use App\Model\ApplicationChecklist;  
use App\Model\ApplicationPaymentDetail;  
use App\Model\Translation;
use Beste\Json;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use DB;
class FormController extends Controller
{
    

    public function schemeList(Request $request)
    {
        $scheme = Scheme::with('area:id,scheme_id,title,code','feeStructure')->get();

        $response['status'] = true;
        $response['message'] = 'success';
        $response['data'] = $scheme;

        return response()->json($response);
    }
    public function schemeAreaList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scheme_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = ['status' => false, 'message' => translate('Scheme is required'), 'data' => []];
            return response()->json($response, 403);
        }

        $scheme = SchemeArea::with('scope')->where('scheme_id',$request['scheme_id'])->get();

        $response['status'] = true;
        $response['message'] = 'success';
        $response['data'] = $scheme;

        return response()->json($response);
    }
    public function AssessorList(Request $request)
    {
        $assessor = Assessor::with('assessor')
                        ->where('profile_status',1)
                        ->get();

        $response['status'] = true;
        $response['message'] = 'success';
        $response['data'] = $assessor;

        return response()->json($response);
    }

    public function storeMedia(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $type = $request['type'] ?? 'png';

        $media = new Media();
        $media->type = $type;
        $media->file = ImageManager::uploadMedia('media/',$type, $request->file('file'));
        $media->save();

        $response['status'] = true;
        $response['message'] = 'success';
        $response['data'] = $media;

        return response()->json($response);
    }

    public function documents(Request $request){
        $data = DB::table('documents')->get();

        if(!empty($data)){
            foreach($data as $key=>$value){
                $data[$key]->file = asset($value->file);
            }
        }

        $response['status'] = true;
        $response['message'] = 'success';
        $response['data'] = $data;

        return response()->json($response);
    }
    public function applicationStore(Request $request){
        $validator = Validator::make($request->all(), [
            'application_type' => 'required',
            'scheme_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        // $data = Application::get();
        // return response()->json($data);

        $user = Helpers::get_customer($request);
        $companyProfile = CompanyProfile::where('user_id',$user->id)->first();

        if(empty($companyProfile)){
            $response['status'] = false;
            $response['message'] = 'You don`t have any company profile';

            return response()->json($response);
        }

        if($companyProfile['status'] != 'approve'){
            $response['status'] = false;
            $response['message'] = 'Your company profile is not approve yet. waiting for approval ';

            return response()->json($response);
        }

        $count = Application::orderBy('id','DESC')->first();

        $refence_no = 10000000 + (($count) ? $count->id + 1 : 1);

        $application = new Application();
        $application->user_id = $user->id;
        $application->company_id = $companyProfile->id;
        $application->reference_number = $refence_no;
        $application->application_type = $request['application_type'];
        $application->scheme_id = $request['scheme_id'];
        $application->area_ids = $request['area_ids'] ?? null;
        $application->status = 'pending';
        $application->application_status = 'pending';
        $application->declaration = $request['declaration'] ?? 1;

        // $application->application_outside_usa = is_string($request['application_outside_usa'])
        //         ? json_decode($request['application_outside_usa'], true)
        //         : json_encode($request['application_outside_usa']);

        $application->application_outside_usa = $request['application_outside_usa'] ?? null;

        $application->application_outside_usa_text = $request['application_outside_usa_text'] ?? null;        
        $application->is_accept = 0;
        $application->signature = $request['signature'] ?? null;
        $application->save();

        if($application){
            
            if ($request->has('form_data.basic_data')) {
                $basic = new ApplicationBasicDetail();
                $basic->application_id = $application->id;
                $basic->scheme_id = $request->scheme_id;
                $basic->area_ids = $request->area_ids;

                $basic->main_activities       = $request->form_data['basic_data']['main_activities'] ?? null;
                $basic->name_of_cab_appears   = $request->form_data['basic_data']['name_of_cab_appears'] ?? null;
                $basic->internal_audit_review = $request->form_data['basic_data']['internal_audit_review'] ?? null;
                $basic->senior_staff_info     = $request->form_data['basic_data']['senior_staff_info'] ?? null;
                $basic->scheme_manager_info   = $request->form_data['basic_data']['scheme_manager_info'] ?? null;
                $basic->another_key_person_info = $request->form_data['basic_data']['another_key_person_info'] ?? null;
                $basic->quality_manager_info  = $request->form_data['basic_data']['quality_manager_info'] ?? null;
                $basic->local_regulation      = $request->form_data['basic_data']['local_regulation'] ?? null;
                $basic->certifications        = $request->form_data['basic_data']['certifications'] ?? null;
                $basic->suspended_or_withdrawn= $request->form_data['basic_data']['suspended_or_withdrawn'] ?? null;

                $basic->save();
            }

            if ($request->has('form_data.documents')) {
                $document = new ApplicationDocument();
                $document->application_id = $application->id;

                $document->legal_entity       = $request->form_data['documents']['legal_entity'] ?? null;
                $document->logo_electronic       = $request->form_data['documents']['logo_electronic'] ?? null;
                $document->cab_agreement   = $request->form_data['documents']['cab_agreement'] ?? null;
                $document->assessment_checklist = $request->form_data['documents']['assessment_checklist'] ?? null;
                $document->standard     = $request->form_data['documents']['standard'] ?? null;
                $document->quality_documentation   = $request->form_data['documents']['quality_documentation'] ?? null;
                $document->relevant_associate = $request->form_data['documents']['relevant_associate'] ?? null;
                $document->testing_scheme  = $request->form_data['documents']['testing_scheme'] ?? null;
                $document->proficiency_testing      = $request->form_data['documents']['proficiency_testing'] ?? null;
                $document->requiring_accreditation        = $request->form_data['documents']['requiring_accreditation'] ?? null;
                $document->job_description= $request->form_data['documents']['job_description'] ?? null;
                $document->risk_analysis= $request->form_data['documents']['risk_analysis'] ?? null;
                $document->technical_and_quality= $request->form_data['documents']['technical_and_quality'] ?? null;
                $document->signature= $request->form_data['documents']['signature'] ?? null;
                $document->selfie= $request->form_data['documents']['selfie'] ?? null;
                $document->application_fee= $request->form_data['documents']['application_fee'] ?? null;

                $document->save();
            } 

            if($request->scheme_id == 1 && $request->has('form_data.scheme_data')){
               
                $this->testingLaboratory(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }
            if($request->scheme_id == 2 && $request->has('form_data.scheme_data')){
               
                $this->calibrationLaboratory(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 3 && $request->has('form_data.scheme_data')){
               
                $this->medicalLaboratory(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 4 && $request->has('form_data.scheme_data')){
               
                $this->inspectionLaboratory(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 5 && $request->has('form_data.scheme_data')){

                $this->managementCertificate(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );
            }

            if($request->scheme_id == 6 && $request->has('form_data.scheme_data')){
                $this->proficiencyTesting(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );
            }

            if($request->scheme_id == 7 && $request->has('form_data.scheme_data')){
                $this->productTesting(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );
            }

            if($request->scheme_id == 8 && $request->has('form_data.scheme_data')){
                $this->personCertificate(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 9 && $request->has('form_data.scheme_data')){
                $this->forensicService(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 10 && $request->has('form_data.scheme_data')){
                $this->halalCertificate(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }
            if($request->scheme_id == 11 && $request->has('form_data.scheme_data')){
                $this->biotechnology(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 12 && $request->has('form_data.scheme_data')){
                $this->validationVerification(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            $response['status'] = true;
            $response['message'] = 'Form Submit Successfully, Waiting for response';
            $response['data'] = $application;

            return response()->json($response);

        }

        $response['status'] = false;
        $response['message'] = 'Something Went Wrong';

        return response()->json($response);
    }

    public static function testingLaboratory($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeTestingLaboratory::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeTestingLaboratory();
        }
        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        $scheme->area_ids = $area_ids;

        $scheme->scope_of_testing     = $data['scope_of_testing'] ?? null;
        $scheme->list_of_equipment    = $data['list_of_equipment'] ?? null;
        $scheme->lab_equipment        = $data['lab_equipment'] ?? null;
        $scheme->calibration_sites    = $data['calibration_sites'] ?? null;
        $scheme->calibration_sites_table_data = $data['calibration_sites_table_data'] ?? null;

        $scheme->save();

        return true;
    }
    public static function calibrationLaboratory($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeCalibrationLaboratory::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeCalibrationLaboratory();
        }

        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        $scheme->area_ids = $area_ids;

        $scheme->scope_of_calibration     = $data['scope_of_calibration'] ?? null;
        $scheme->list_of_equipment    = $data['list_of_equipment'] ?? null;
        $scheme->lab_equipment        = $data['lab_equipment'] ?? null;
        $scheme->calibration_sites    = $data['calibration_sites'] ?? null;
        $scheme->calibration_sites_table_data = $data['calibration_sites_table_data'] ?? null;

        $scheme->save();

        return true;
    }
    public static function medicalLaboratory($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeMedicalLaboratory::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeMedicalLaboratory();
        }

        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        $scheme->area_ids = $area_ids;

        $scheme->laboratory_sample     = $data['laboratory_sample'] ?? null;
        $scheme->primary_sample_collections     = $data['primary_sample_collections'] ?? null;
        $scheme->main_lab     = $data['main_lab'] ?? null;
        $scheme->laboratory_branches     = $data['laboratory_branches'] ?? null;
        $scheme->branch_address     = $data['branch_address'] ?? null;
        $scheme->branch_activites    = $data['branch_activites'] ?? null;
        $scheme->branch_separate    = $data['branch_separate'] ?? null;
        $scheme->lab_equipment        = $data['lab_equipment'] ?? null;
        $scheme->calibration_sites    = $data['calibration_sites'] ?? null;
        $scheme->calibration_sites_table_data = $data['calibration_sites_table_data'] ?? null;

        // dd($scheme);

        $scheme->save();

        return true;
    }

    public static function inspectionLaboratory($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeInspectionBody::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeInspectionBody();
        }

        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        $scheme->area_ids = $area_ids;

        $scheme->scope_of_inspection     = $data['scope_of_inspection'] ?? null;
        $scheme->undertake_inspection     = $data['undertake_inspection'] ?? null;
        $scheme->type_inspection_body     = $data['type_inspection_body'] ?? null;
        $scheme->inspection_outside_usa     = $data['inspection_outside_usa'] ?? null;
        $scheme->other_inspection_country     = $data['other_inspection_country'] ?? null;
        $scheme->inspection_equipment    = $data['inspection_equipment'] ?? null;
        $scheme->calibration_sites    = $data['calibration_sites'] ?? null;
        $scheme->calibration_sites_table_data = $data['calibration_sites_table_data'] ?? null;

        // dd($scheme);

        $scheme->save();

        return true;
    }
    public static function managementCertificate($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeMsCertificationBody::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeMsCertificationBody();
        }

        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        // $scheme->area_ids = $area_ids;

        $scheme->area_data     = $data['area_data'] ?? null;
        // dd($scheme);

        $scheme->save();

        return true;
    }
    public static function proficiencyTesting($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeProficiencyTesting::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeProficiencyTesting();
        }

        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        // $scheme->area_ids = $area_ids;

        $scheme->table_data               = $data['table_data'] ?? null;
        $scheme->externally_data          = $data['externally_data'] ?? null;
        $scheme->scope_of_proficiency     = $data['scope_of_proficiency'] ?? null;
        // dd($scheme);

        $scheme->save();

        return true;
    }
    public static function productTesting($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeProductCertification::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeProductCertification();
        }

        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        // $scheme->area_ids = $area_ids;

        $scheme->conformity               = $data['conformity'] ?? null;
        $scheme->scope_of_product          = $data['scope_of_product'] ?? null;
        $scheme->document_safeguard     = $data['document_safeguard'] ?? null;
        $scheme->committee_structure     = $data['committee_structure'] ?? null;
        $scheme->surveillance_certification_applied     = $data['surveillance_certification_applied'] ?? null;
        $scheme->test_certification_applied     = $data['test_certification_applied'] ?? null;
        $scheme->testing_laboratories     = $data['testing_laboratories'] ?? null;
        $scheme->testing_laboratory_data     = $data['testing_laboratory_data'] ?? null;
        $scheme->nonaccredited_subcontractors    = $data['nonaccredited_subcontractors'] ?? null;
        // dd($scheme);

        $scheme->save();

        return true;
    }
    public static function personCertificate($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemePersonCertification::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemePersonCertification();

        }

        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        // $scheme->area_ids = $area_ids;

        $scheme->additional_location               = $data['additional_location'] ?? null;
        $scheme->location_data          = $data['location_data'] ?? null;
        $scheme->scheme_for_person     = $data['scheme_for_person'] ?? null;
        $scheme->is_already_accreditation     = $data['is_already_accreditation'] ?? null;
        $scheme->already_accreditation_detail     = $data['already_accreditation_detail'] ?? null;
        $scheme->is_another_accreditation     = $data['is_another_accreditation'] ?? null;
        $scheme->another_accreditation_detail     = $data['another_accreditation_detail'] ?? null;
        $scheme->is_already_quotation     = $data['is_already_quotation'] ?? null;
        $scheme->reference_number    = $data['reference_number'] ?? null;
        $scheme->documented_structure    = $data['documented_structure'] ?? null;
        $scheme->structure_detail    = $data['structure_detail'] ?? null;
        $scheme->staff_applying_details    = $data['staff_applying_details'] ?? null;
        $scheme->personal_quality_manual    = $data['personal_quality_manual'] ?? null;
        $scheme->owner_personal_scheme    = $data['owner_personal_scheme'] ?? null;
        $scheme->scheme_committee    = $data['scheme_committee'] ?? null;
        $scheme->scheme_nationality    = $data['scheme_nationality'] ?? null;
        $scheme->quality_system    = $data['quality_system'] ?? null;
        $scheme->scope_of_application    = $data['scope_of_application'] ?? null;
        // dd($scheme);

        $scheme->save();

        return true;
    }
    public static function forensicService($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeForensicService::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeForensicService();

        }

        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        $scheme->area_ids = $area_ids;

        $scheme->service_apply_for    = $data['service_apply_for'] ?? null;
        $scheme->sample_details          = $data['sample_details'] ?? null;
        $scheme->forensic_test     = $data['forensic_test'] ?? null;
        $scheme->forensic_test_details     = $data['forensic_test_details'] ?? null;
        $scheme->laboratory_branches     = $data['laboratory_branches'] ?? null;
        $scheme->branch_separate_management     = $data['branch_separate_management'] ?? null;
        $scheme->laboratory_branch_details     = $data['laboratory_branch_details'] ?? null;
        $scheme->major_discipline     = $data['major_discipline'] ?? null;
        $scheme->major_discipline_detail    = $data['major_discipline_detail'] ?? null;
        $scheme->undertake_inspection    = $data['undertake_inspection'] ?? null;
        $scheme->type_of_inspection    = $data['type_of_inspection'] ?? null;
        $scheme->lab_equipment    = $data['lab_equipment'] ?? null;
        $scheme->calibration_sites    = $data['calibration_sites'] ?? null;
        $scheme->calibration_sites_table_data    = $data['calibration_sites_table_data'] ?? null;
        // dd($scheme);

        $scheme->save();

        return true;
    }

    public static function halalCertificate($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeHalalCertification::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeHalalCertification();

        }

        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        // $scheme->area_ids = $area_ids;

        $scheme->critical_location    = $data['critical_location'] ?? null;
        $scheme->location_details          = $data['location_details'] ?? null;
        $scheme->authorized_person_detail     = $data['authorized_person_detail'] ?? null;
        $scheme->stamp     = $data['stamp'] ?? null;
        $scheme->logo     = $data['logo'] ?? null;
        $scheme->islamic_affairs     = $data['islamic_affairs'] ?? null;
        $scheme->scope_of_accreditation     = $data['scope_of_accreditation'] ?? null;
        $scheme->halal_product_table_1     = $data['halal_product_table_1'] ?? null;
        $scheme->halal_product_table_2    = $data['halal_product_table_2'] ?? null;
        $scheme->halal_product_table_3    = $data['halal_product_table_3'] ?? null;
        // dd($scheme);

        $scheme->save();

        return true;
    }
    public static function biotechnology($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeBiotechnologyBiobank::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeBiotechnologyBiobank();

        }

        // dd($data);
        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        $scheme->area_ids = $area_ids;

        $scheme->category    = $data['category'] ?? null;
        $scheme->source_of_material    = $data['source_of_material'] ?? null;
        $scheme->activities_of_biobank          = $data['activities_of_biobank'] ?? null;
        $scheme->scope_of_testing     = $data['scope_of_testing'] ?? null;
        $scheme->equipment_activities_of_biobank     = $data['equipment_activities_of_biobank'] ?? null;
        $scheme->staff_list     = $data['staff_list'] ?? null;
        $scheme->bio_bank_activities     = $data['bio_bank_activities'] ?? null;
        $scheme->regulation_requirements     = $data['regulation_requirements'] ?? null;
        $scheme->lab_equipment     = $data['lab_equipment'] ?? null;
        $scheme->calibration_sites     = $data['calibration_sites'] ?? null;
        $scheme->calibration_sites_table_data    = $data['calibration_sites_table_data'] ?? null;
        // dd($scheme);

        $scheme->save();

        return true;
    }
    public static function validationVerification($application_id, $scheme_id, $area_ids, $data){

        $scheme = SchemeValidationVerification::where('application_id',$application_id)->first();
        if(empty($scheme)){
            $scheme = new SchemeValidationVerification();

        }

        $scheme->application_id = $application_id;
        $scheme->scheme_id = $scheme_id;
        $scheme->area_ids = $area_ids;

        $scheme->main_activities    = $data['main_activities'] ?? null;
        $scheme->list_of_enclosures          = $data['list_of_enclosures'] ?? null;
        $scheme->no_of_employees     = $data['no_of_employees'] ?? null;
        $scheme->meeting_minimum_eligibility     = $data['meeting_minimum_eligibility'] ?? null;
        $scheme->counties_vvb_operate     = $data['counties_vvb_operate'] ?? null;
        $scheme->current_accreditations     = $data['current_accreditations'] ?? null;
        $scheme->type_of_validation_activities     = $data['type_of_validation_activities'] ?? null;
        $scheme->standard_documents     = $data['standard_documents'] ?? null;
        $scheme->organization_level_sector     = $data['organization_level_sector'] ?? null;
        $scheme->project_level_sector    = $data['project_level_sector'] ?? null;

        $scheme->save();

        return true;
    }

    public function applicationAppliedList(Request $request){

        $user = $request->user();

        $application = Application::with('scheme')->where('user_id',$user->id)->get();

        $response['status'] = true;
        $response['message'] = "Success";
        $response['data'] = $application;

        return response()->json($response);
    }
    public function applicationDetail(Request $request, $id){

        $user = $request->user();

        $application = Application::with(
                        'scheme:id,title,code',
                        'basic_info',
                        'auditor:id,name,phone,admin_role_id,email,status,witness_status',
                        'office_assessment:id,name,phone,admin_role_id,email,status,witness_status',
                        'witness_assessment:id,name,phone,admin_role_id,email,status,witness_status',
                        'document',
                        'checklist',
                        'payments',
                        'fees'
                        // 'application_assessment'
                        )
                        ->where('user_id',$user->id)
                        ->where('id', $id)
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

            $application->scheme_details = $scheme;

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

            if($application->auditor_team_ids){
                $application->auditor_team = $application->auditor_team; 
            }
            if($application->office_assessment_team_ids){
                $application->office_assessment_team = $application->office_assessment_team; 
            }
            if($application->witness_assessment_team_ids){
                $application->witness_assessment_team = $application->witness_assessment_team; 
            }

            $application->totalFindings = AssessmentFinding::where('application_id',$id)->count();
            // dd($application->document->logo_electronic_file);

            $response['status'] = true;
            $response['message'] = "Success";
            $response['data'] = $application;
            
        } else {
            $response['status'] = false;
            $response['message'] = "Invalid Application Id";
            $response['data'] = [];

        }


        return response()->json($response);
    }

    public function applicationUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'application_type' => 'required',
            'scheme_id' => 'required',
            'application_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $application = Application::find($request['application_id']);

        if($application){

            $application->application_type = $request['application_type'];
            $application->scheme_id = $request['scheme_id'];
            $application->area_ids = $request['area_ids'] ?? null;
            $application->status = 'pending';
            $application->declaration = $request['declaration'] ?? 1;
            $application->application_outside_usa = $request['application_outside_usa'] ?? null;
            $application->application_outside_usa_text = $request['application_outside_usa_text'] ?? null;

            $application->save();

            if ($request->has('form_data.basic_data')) {

                $basic = ApplicationBasicDetail::where('application_id',$application->id)->first();

                $basic->scheme_id = $request->scheme_id;
                $basic->area_ids = $request->area_ids;

                $basic->main_activities       = $request->form_data['basic_data']['main_activities'] ?? null;
                $basic->name_of_cab_appears   = $request->form_data['basic_data']['name_of_cab_appears'] ?? null;
                $basic->internal_audit_review = $request->form_data['basic_data']['internal_audit_review'] ?? null;
                $basic->senior_staff_info     = $request->form_data['basic_data']['senior_staff_info'] ?? null;
                $basic->scheme_manager_info   = $request->form_data['basic_data']['scheme_manager_info'] ?? null;
                $basic->another_key_person_info = $request->form_data['basic_data']['another_key_person_info'] ?? null;
                $basic->quality_manager_info  = $request->form_data['basic_data']['quality_manager_info'] ?? null;
                $basic->local_regulation      = $request->form_data['basic_data']['local_regulation'] ?? null;
                $basic->certifications        = $request->form_data['basic_data']['certifications'] ?? null;
                $basic->suspended_or_withdrawn= $request->form_data['basic_data']['suspended_or_withdrawn'] ?? null;

                $basic->save();
            }

            if ($request->has('form_data.documents')) {
                $document = ApplicationDocument::where('application_id',$application->id)->first();

                $document->legal_entity       = $request->form_data['documents']['legal_entity'] ?? null;
                $document->logo_electronic       = $request->form_data['documents']['logo_electronic'] ?? null;
                $document->cab_agreement   = $request->form_data['documents']['cab_agreement'] ?? null;
                $document->assessment_checklist = $request->form_data['documents']['assessment_checklist'] ?? null;
                $document->standard     = $request->form_data['documents']['standard'] ?? null;
                $document->quality_documentation   = $request->form_data['documents']['quality_documentation'] ?? null;
                $document->relevant_associate = $request->form_data['documents']['relevant_associate'] ?? null;
                $document->testing_scheme  = $request->form_data['documents']['testing_scheme'] ?? null;
                $document->proficiency_testing      = $request->form_data['documents']['proficiency_testing'] ?? null;
                $document->requiring_accreditation        = $request->form_data['documents']['requiring_accreditation'] ?? null;
                $document->job_description= $request->form_data['documents']['job_description'] ?? null;
                $document->risk_analysis= $request->form_data['documents']['risk_analysis'] ?? null;
                $document->technical_and_quality= $request->form_data['documents']['technical_and_quality'] ?? null;
                $document->signature= $request->form_data['documents']['signature'] ?? null;
                $document->selfie= $request->form_data['documents']['selfie'] ?? null;
                $document->application_fee= $request->form_data['documents']['application_fee'] ?? null;

                $document->save();
            } 

            if($request->scheme_id == 1 && $request->has('form_data.scheme_data')){
               
                $this->testingLaboratory(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 2 && $request->has('form_data.scheme_data')){
               
                $this->calibrationLaboratory(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 3 && $request->has('form_data.scheme_data')){
               
                $this->medicalLaboratory(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 4 && $request->has('form_data.scheme_data')){
               
                $this->inspectionLaboratory(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 5 && $request->has('form_data.scheme_data')){

                $this->managementCertificate(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );
            }

            if($request->scheme_id == 6 && $request->has('form_data.scheme_data')){
                $this->proficiencyTesting(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );
            }

            if($request->scheme_id == 7 && $request->has('form_data.scheme_data')){
                $this->productTesting(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );
            }

            if($request->scheme_id == 8 && $request->has('form_data.scheme_data')){
                $this->personCertificate(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 9 && $request->has('form_data.scheme_data')){
                $this->forensicService(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 10 && $request->has('form_data.scheme_data')){
                $this->halalCertificate(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            if($request->scheme_id == 11 && $request->has('form_data.scheme_data')){
                $this->biotechnology(
                    $application->id, 
                    $request->scheme_id,
                    $request->area_ids,
                    $request->form_data['scheme_data'],
                );

            }

            $response['status'] = true;
            $response['message'] = 'Form update Successfully';
            $response['data'] = $application;

            return response()->json($response);


        } else {

            $response['status'] = false;
            $response['message'] = 'Application Not Found';

            return response()->json($response);
        }

    }

    public function dashboardCounter(Request $request){

        $user = $request->user();

        $applications = Application::with('scheme')->where('user_id', $user->id)->get();

        $grouped = $applications->groupBy('status');

        $data = [
            'all_application_count'        => $applications->count(),
            'pending_application_count'    => $grouped->get('pending', collect())->count(),
            'assessment_application_count' => $grouped->get('schedule', collect())->count(),
            'in_process_application_count' => $grouped->get('in_process', collect())->count(),
            'quality_check_application_count' => $grouped->get('quality_check', collect())->count(),
            'non_conformance_application_count' => $grouped->get('non_conformance', collect())->count(),
            'reject_application_count'     => $grouped->get('reject', collect())->count(),
            'complete_application_count'   => $grouped->get('complete', collect())->count(),
            
            'all_application'              => $applications,
            'pending_application'          => $grouped->get('pending', collect()),
            'assessment_application'       => $grouped->get('schedule', collect()),
            'in_process_application'       => $grouped->get('in_process', collect()),
            'quality_check_application'    => $grouped->get('quality_check', collect()),
            'non_conformance_application'  => $grouped->get('non_conformance', collect()),
            'reject_application'           => $grouped->get('reject', collect()),
            'complete_application'         => $grouped->get('complete', collect()),

        ];

        $response['status'] = true;
        $response['message'] = "Success";
        $response['data'] = $data;

        return response()->json($response);
    }

    public function updateAssessorStatus(Request $request){
        
        $validator = Validator::make($request->all(), [
            'application_id' => 'required',
            'team_type' => 'required|in:auditor_team,office_team,witness_team',
            'status' => 'required',
            'remark' => 'required',
            'witness_id'  => 'required_if:team_type,witness_team',
        ], [
            'application_id.required' => 'Application is required!',
            'team_type.required' => 'Team Type is required!',
            'team_type.in'       => 'Team Type must be Document review Team, Office Team, or Witness Team!',
            'witness_id.required_if'    => 'Witness is required for Witness Assessment!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $application = Application::find($request['application_id']);

        if($application){

            if($request['team_type'] == 'witness_team'){
                $witness = ApplicationWitness::find($request['witness_id']);
                if(!$witness){
                    $response['status'] = false;
                    $response['message'] = 'Clause not found';

                    return response()->json($response);
                }

                $witness->client_auditor_team_status = $request['status'];
                $witness->client_auditor_team_remark = $request['remark'] ?? null;

                $witness->save();

                $application->client_witness_assessment_team_status = $request['status'];
                $application->client_witness_assessment_team_remark = $request['remark'] ?? null;

                $application->save();

            } else {

                if($request['team_type'] == 'auditor_team'){
                    $application->client_auditor_team_status = $request['status'];
                    $application->client_auditor_team_remark = $request['remark'] ?? null;  
                    $application->application_status = 'document_review';
                }
                if($request['team_type'] == 'office_team'){
                    $application->client_office_assessment_team_status = $request['status'];
                    $application->client_office_assessment_team_remark = $request['remark'] ?? null;  
                    $application->application_status = 'office_assessment';
                }
                $application->save();
            }
            // if($request['team_type'] == 'witness_team'){
            //     $application->client_witness_assessment_team_status = $request['status'];
            //     $application->client_witness_assessment_team_remark = $request['remark'] ?? null;
            //     $application->application_status = 'witness_assessment';
            // }

            $status = ($request['status'] == 1) ? 'Team Approve Successfully' : 'Team Reject Successfully';

            $response['status'] = false;
            $response['message'] = $status;

        } else {
            $response['status'] = false;
            $response['message'] = 'Application Not Found';
        }    
        return response()->json($response);
    }


    // checklist fucntions

    public function getChecklistStructure(Request $request){
       
        $data = SchemeChecklist::with('payment:scheme_id,application_fee')->where('scheme_id', $request['scheme_id'])->first();

        if($data){
           
            $response['status'] = true;
            $response['message'] = "Success";
            $response['data'] = $data;

        } else {
            
            $response['status'] = false;
            $response['message'] = "data not found";
            $response['data'] = [];

        }
        return response()->json($response);
    }

    public function submitChecklist(Request $request){

        $checkList = SchemeChecklist::where('scheme_id', $request['scheme_id'])->first();

        $applicationAssessment = ApplicationAssessment::where('application_id', $request['application_id'])->first();

        $check = ApplicationChecklist::where('application_id', $request['application_id'])->first();

        if(!$check){
            $check = new ApplicationChecklist();
        }

        $check->application_id = $request['application_id'];
        $check->scheme_id = $request['scheme_id'];
        $check->clause = $checkList['clause'];//$request['clause'];
        $check->general_info = $request['general_info'];
        $check->requirements = $request['requirements'];
        $check->save();

        $applicationAssessment = ApplicationAssessment::where('application_id', $request['application_id'])->first();
        if(!$applicationAssessment) {
            $applicationAssessment = new ApplicationAssessment;
        }

        $applicationAssessment->application_id = $request['application_id'];
        $applicationAssessment->scheme_id = $request['scheme_id'];
        $applicationAssessment->clause = ($checkList['clause'] ?? []);
        $applicationAssessment->main_general_info = ($checkList['team_leader_assessment']['general_info'] ?? []);
        $applicationAssessment->team_leader_assessment = ($checkList['team_leader_assessment']['requirements'] ?? []);
        $applicationAssessment->technical_assessment = ($checkList['technical_assessment'] ?? []);
        $applicationAssessment->vertical_assessment = ($checkList['vertical_assessment'] ?? []);
        $applicationAssessment->witness_assessment = ($checkList['witness_assessment'] ?? []);
        $applicationAssessment->attendance_form = ($checkList['attendance_form'] ?? null);
        $applicationAssessment->save();

        if($check){

            // $payment = new ApplicationPaymentDetail();
            // $payment->application_id = $request['application_id'];
            // $payment->fee_structure_id = $request['scheme_id'];
            // $payment->save();
           
            $response['status'] = true;
            $response['message'] = "Success";
            $response['data'] = $check;

        } else {
            
            $response['status'] = false;
            $response['message'] = "Something went wrong";
            $response['data'] = [];

        }
        return response()->json($response);
    }

    public function updateChecklistClause(Request $request){

        $checkList = ApplicationChecklist::find($request['checklist_id']);

        if($checkList){

            $oldRequirements = $checkList->requirements;
            $oldClauseData = $this->findClause($oldRequirements, $request['update_clause_id']);
            
            if(!$oldClauseData){
                $response['status'] = false;
                $response['message'] = 'Clause not found';

                return response()->json($response);
            }

            $newRequirements = $request['requirements'];
            $newClauseData = $this->findClause($newRequirements, $request['update_clause_id']);

            $history = new ClauseHistory();
            $history->checklist_id = $request['checklist_id'];
            $history->clause_no = $request['update_clause_id'];
            $history->old_data = $oldClauseData;
            $history->new_data = $newClauseData;
            $history->update_type = 'CAB';
            $history->update_by = $request->user()->name ?? 'user';
            $history->save();

            // $checkList->general_info = $request['general_info'];
            $checkList->requirements = $request['requirements'];
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


    public function updatePaymentDetail(Request $request){
        $user = $request->user();

        $application = Application::find($request['application_id']);

        if($application){
            $check = ApplicationPaymentDetail::where('application_id',$request['application_id'])->first();

            if(!$check){
                $check = new ApplicationPaymentDetail();

                $check->application_id = $request['application_id'];
                $check->fee_structure_id = $application['scheme_id'];
            } 

            if($request['type'] == 'application_fee'){
                $check->application_fee = $request['amount'];
                $check->application_fee_image = ImageManager::uploadMedia('media/','png', $request->file('file'));
                $check->application_fee_status = 0;
                $check->application_fee_date = Carbon::now()->format('Y-m-d H:i:s');
            }

            if($request['type'] == 'document_fee'){
                $check->document_fee = $request['amount'];
                $check->document_fee_image = ImageManager::uploadMedia('media/','png', $request->file('file'));
                $check->document_fee_status = 0;
                $check->document_fee_date = Carbon::now()->format('Y-m-d H:i:s');
            }

            if($request['type'] == 'assessment_fee'){
                $check->assessment_fee = $request['amount'];
                $check->assessment_fee_image = ImageManager::uploadMedia('media/','png', $request->file('file'));
                $check->assessment_fee_status = 0;
                $check->assessment_fee_date = Carbon::now()->format('Y-m-d H:i:s');
            }

            if($request['type'] == 'technical_assessment_fee'){
                $check->technical_assessment_fee = $request['amount'];
                $check->technical_assessment_fee_image = ImageManager::uploadMedia('media/','png', $request->file('file'));
                $check->technical_assessment_fee_status = 0;
                $check->technical_assessment_date = Carbon::now()->format('Y-m-d H:i:s');
            }
            
            $check->save();

            $response['status'] = true;
            $response['message'] = 'Payment Update Successfully';
        } else {
            $response['status'] = false;
            $response['message'] = 'Application not found';
        }

        return response()->json($response);
    }

    public function listAssessmentFinding(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:office_assessment,witness_assessment',

        ], [
            'type.required' => 'Finding type is required, It Must be Office assessment or Witness aseessment',
        ]);

        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => "Please fill all required fields",
                'errors' => Helpers::error_processor($validator)
            ];

            return response()->json($response, 403);
        }

        $check = Application::find($id);

        if($check){

            $finding = AssessmentFinding::where('application_id',$id)->where('finding_type',$request->type)->get();

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

        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'finding_id' => 'required',
            'data' => 'required'

        ], [
            'finding_id.required' => 'Finding is required!',
            'data.required' => 'Data is required!',
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

            $history = FindingHistory::where('finding_id',$request['finding_id'])->orderBy('id','DESC')->first();
            if($history){
                $history->cab_data = $request['data'] ?? NUll;
                $history->cab_name = $user->name ?? null;
                $history->cab_date = Carbon::now();
                $history->save();
            }

            $check->action_proposed_by_cab = isset($request['data']) ? $request['data'] : $check->action_proposed_by_cab;

            $check->name_of_cab_representative = isset($request['data']['name_of_cab_representative']) ? $request['data']['name_of_cab_representative'] : $check->name_of_cab_representative;

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


    public function applicationWitness(Request $request, $id){

        $user = $request->user();
        
        $check = ApplicationWitness::with('client:id,name,phone','auditor:id,name,phone','scheme:id,title,code')
                                    ->where('application_id', $id)
                                    ->where('client_id', $user->id)
                                    ->where('witness_status','!=',2)
                                    ->first();

        if($check){

            if($check->auditor_team_ids){
                $check->auditor_team = $check->auditor_team; 
            } else {
                $check->auditor_team = NULL; 
            }
            $response['status'] = true;
            $response['message'] = 'Success';
            $response['data'] = $check;

        } else {
            $response['status'] = false;
            $response['message'] = 'Witness not found';
        }

        return response()->json($response);
    }

    public function updatewitness(Request $request){
       
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'witness_id' => 'required',
            'client_name' => 'required',
            'client_address' => 'required',
            'scope' => 'required',
            // 'client_status' => 'required',

        ], [
            'witness_id.required' => 'Witness is required!',
            'client_name.required' => 'Client name is required!',
            'client_address.required' => 'Client Address is required!',
            'scope.required' => 'Scope is required!',
            // 'client_status.required' => 'Client Status is required!',
        ]);

        $check = ApplicationWitness::where('id', $request->witness_id)
                                    ->where('client_id', $user->id)
                                    ->where('witness_status','!=',2)
                                    ->first();

        if($check){

            $check->client_name = $request->client_name;
            $check->client_address = $request->client_address;
            $check->scope = $request->scope;
            $check->client_status = 1;//$request->client_status;
            $check->inspection_date = $request->inspection_date;
            $check->inspection_duration = $request->inspection_duration;
            $check->audit_stage = $request->audit_stage;
            $check->attachments = $request->attachments;
            $check->save();


            $response['status'] = true;
            $response['message'] = 'Successfully update Waiting for team assign';
            $response['data'] = $check;

        } else {
            $response['status'] = false;
            $response['message'] = 'Witness not found';
        }

        return response()->json($response);

    }




    //  test functions

    
    public function testScheme(Request $request){
        // dd($request);
        if($request->scheme_id == 12 && $request->has('form_data.scheme_data')){
            // dd($request);   
            $response = $this->validationVerification(
                1, 
                $request->scheme_id,
                $request->area_ids,
                $request->form_data['scheme_data'],
            );

            return response()->json($response);

        }
        $response['status']=true;
         return response()->json($response);
    }

    public function testCheckList(Request $request){

        // dd(phpinfo());

        $checklist = new SchemeChecklist();
        $checklist->scheme_id = $request->scheme_id ?? 1;
        $checklist->code = $request->code;
        $checklist->clause = $request->clause;
        $checklist->data = $request->data;
        $checklist->save();

        return response()->json($checklist);

        // dd($checklist);
    }

    public function getClauseDetails(Request $request)
    {
        $application_id = $request['application_id'];
        $clause_no = $request['clause_no'];

        $checklist = ApplicationChecklist::where('application_id', $application_id)->first();

        if (!$checklist) {
            return response()->json([
                'status' => false,
                'message' => 'Application checklist not found',
                'data' => []
            ]);
        }

        $requirements = $checklist['requirements'];

        $result = $this->searchClause($requirements, $clause_no);

        if ($result) {
            return response()->json([
                'status' => true,
                'message' => 'Clause found',
                'data' => $result
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Clause not found',
                'data' => []
            ]);
        }
    }

   
    private function searchClause($data, $clause_no)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                // Match found
                if (isset($value['clause_no']) && $value['clause_no'] == $clause_no) {
                    return $value;
                }

                // Continue searching deeper
                $found = $this->searchClause($value, $clause_no);
                if ($found) {
                    return $found;
                }
            }
        }

        return null;
    }
}
