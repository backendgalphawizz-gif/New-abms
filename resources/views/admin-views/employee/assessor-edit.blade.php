@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Assesor Edit'))

@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

<style>
    .ul.select2-selection__rendered{
        display: flex !important;
    }
    .select2-selection__choice__remove:hover{
        background-color: unset !important;
    }
    .select2-selection__choice__remove:hover{
        border-right: none !important;
    }
    li.select2-selection__choice{
        background: linear-gradient(260deg, rgba(184, 28, 24, 1) 0%, rgba(252, 68, 64, 1) 100%);
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
        border-right: none !important;
    }
    input[type="file"] {
        display: block !important;
    }
    input.custom-file-input::file-selector-button{
        display: none;
    }
</style>

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3 statusContainer">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/add-new-employee.png')}}" alt="">
            {{\App\CPU\translate('Assessor_Update')}}
        </h2>
        <!-- <div class="statusDiv">
            <div class="inputCard">
                <input
                    id="pendingLabel"
                    type="radio"
                    name="profile_status"
                    value="0"
                    form="assessor-form"
                    {{ (isset($e['profile_status']) && $e['profile_status'] == 0) ? 'checked' : '' }}
                >
                <label for="pendingLabel">Pending</label>
            </div>
            <div class="inputCard">
                <input
                    id="approveLabel"
                    type="radio"
                    name="profile_status"
                    value="1"
                    form="assessor-form"
                    {{ (isset($e['profile_status']) && $e['profile_status'] == 1) ? 'checked' : '' }}
                >
                <label for="approveLabel">Approve</label>
            </div>
            <div class="inputCard">
                <input
                    id="rejectLabel"
                    type="radio"
                    name="profile_status"
                    value="2"
                    form="assessor-form"
                    {{ (isset($e['profile_status']) && $e['profile_status'] == 2) ? 'checked' : '' }}
                >
                <label for="rejectLabel">Reject</label>
            </div>
        </div> -->
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <form id="assessor-form"
                  action="{{route('admin.assessor.update-assessor',[$e['id']])}}"
                  method="post"
                  enctype="multipart/form-data"
                  style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                @csrf

               
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                            <i class="tio-user"></i>
                            {{\App\CPU\translate('General_Information')}}
                        </h5>
                        <div class="row">
                            <input type="hidden" name="role_id" value="{{ $e['admin_role_id'] }}">

                            <div class="col-md-6 form-group">
                                <label for="name" class="title-color">{{\App\CPU\translate('Name')}}</label>
                                <input type="text" name="name" value="{{$e['name']}}" class="form-control" id="name"
                                       placeholder="{{\App\CPU\translate('Ex')}} : Jhon Doe">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="phone" class="title-color">{{\App\CPU\translate('Phone')}}</label>
                                <input type="number" value="{{$e['phone']}}" required name="phone" class="form-control" id="phone"
                                       placeholder="{{\App\CPU\translate('Ex')}} : +88017********">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="email" class="title-color">{{\App\CPU\translate('Email')}}</label>
                                <input type="email" value="{{$e['email']}}" name="email" class="form-control" id="email"
                                       placeholder="{{\App\CPU\translate('Ex')}} : ex@gmail.com" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="password" class="title-color">{{\App\CPU\translate('Password')}}</label>
                                <small> ( {{\App\CPU\translate('input if you want to change')}} )</small>
                                <input type="text" name="password" class="form-control" id="password"
                                       placeholder="{{\App\CPU\translate('Password')}}">
                            </div>

                            <div class="col-md-6 form-group">
                                <div class="form-group">
                                    <label for="customFileUpload" class="title-color">{{\App\CPU\translate('employee_image')}}</label>
                                    <span class="text-danger">( {{\App\CPU\translate('ratio')}} 1:1 )</span>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">
                                            {{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}
                                        </label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img class="upload-img-view" id="viewer"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('storage/app/public/admin')}}/{{$e['image']}}" alt="Employee thumbnail"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                            <i class="tio-user"></i>
                            {{\App\CPU\translate('Personal_Information')}}
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_number" class="title-color">{{\App\CPU\translate('id_number')}}</label>
                                    <input type="text" name="id_number" value="{{$assessor['id_number']}}" class="form-control"
                                           id="id_number" placeholder="{{\App\CPU\translate('Ex')}} : ABC1234" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="apply_designation" class="title-color">
                                        {{\App\CPU\translate('applied_designation')}}
                                    </label>
                                    <input type="text" name="apply_designation" value="{{$assessor['apply_designation']}}" class="form-control"
                                           id="apply_designation">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('highest_qualification')}}</label>
                                    <input type="text" name="highest_qualification" 
                                        value="{{ $assessor['highest_qualification'] ?? '' }}" 
                                        class="form-control"
                                        placeholder="Ex: B.Tech, MBA" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('technical_area')}}</label>
                                    <input type="text" name="technical_area" 
                                        value="{{ $assessor['technical_area'] ?? '' }}" 
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('experience_(in_years)')}}</label>
                                    <input type="text" name="first_experience" 
                                        value="{{ $assessor['experience'] ?? '' }}" 
                                        class="form-control"
                                        placeholder="Ex: 5 years" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="home_address" class="title-color">{{\App\CPU\translate('home_address')}}</label>
                                    <input type="text" name="home_address" value="{{$assessor['home_address']}}" class="form-control"
                                           id="home_address" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                            <i class="tio-user"></i>
                            {{\App\CPU\translate('Specialization')}}
                        </h5>

                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    @php
                                        $selectedSchemes = array_filter(array_map('intval', explode(',', $assessor->scheme_id ?? '')));
                                    @endphp
                                    <label class="title-color">{{ \App\CPU\translate('Select Scheme') }}</label>
                                    <select class="form-control scheme_id" name="scheme_id[]" id="scheme_id" multiple required>
                                        @foreach ($scheme as $value)
                                            <option value="{{ $value->id }}" 
                                                {{ in_array($value->id, $selectedSchemes) ? 'selected' : '' }}>
                                                {{ $value->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{ \App\CPU\translate('Select Area') }}</label>
                                    <select name="area_id[]" class="form-control area_id" id="area_id" multiple required></select>
                                </div>
                            </div>

                         
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{ \App\CPU\translate('Select Scope') }}</label>
                                    <select name="scope_id[]" class="form-control scope_id" id="scope_id" multiple></select>
                                </div>
                            </div>
                        </div>

                 
                        <h5 class="mt-3">Qualification</h5>

                        <div id="section-wrapper" class="mt-3">
                            @php
                                $qualifications = $assessor->qualifications;
                                if (is_string($qualifications)) {
                                    $qualifications = ($qualifications);
                                }
                            @endphp

                            @if(!empty($qualifications))
                                @foreach($qualifications as $key => $value)
                                <div class="row section-row g-3 align-items-end mb-3">

                                <!-- Degree -->
                                <div class="col-md-2">
                                    <label class="form-label">Degree/Certificate</label>
                                    <input type="text"
                                        name="qualifications[degree][]"
                                        value="{{ $value['qualification'] ?? '' }}"
                                        class="form-control form-control-sm"
                                        required>
                                </div>

                                <!-- Institute -->
                                <div class="col-md-2">
                                    <label class="form-label">Institute</label>
                                    <input type="text"
                                        name="qualifications[institute][]"
                                        value="{{ $value['institution'] ?? '' }}"
                                        class="form-control form-control-sm">
                                </div>

                                <!-- Year -->
                                <div class="col-md-2">
                                    <label class="form-label">Completion Year</label>
                                    <input type="number"
                                        name="qualifications[year][]"
                                        value="{{ $value['year'] ?? '' }}"
                                        class="form-control form-control-sm"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>

                                <!-- Upload + View File -->
                                <div class="col-md-3">
                                    <label class="form-label">Upload Certificate</label>

                                    @if(!empty($value['file']))
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ asset($value['file']) }}"
                                            target="_blank"
                                            class="btn btn-primary btn-sm">View</a>

                                            <input type="file"
                                                name="file[{{$key}}]"
                                                class="form-control form-control-sm"
                                                style="max-width:180px;">
                                        </div>

                                        <input type="hidden"
                                            name="qualifications[file][{{$key}}]"
                                            value="{{ $value['file'] }}">
                                    @else
                                        <input type="file"
                                            name="file[]"
                                            class="form-control form-control-sm">
                                    @endif
                                </div>

                                <!-- Remark -->
                                <div class="col-md-2">
                                    <label class="form-label">Remark</label>
                                    <input type="text"
                                        name="qualifications[remark][]"
                                        value="{{ $value['remarks'] ?? '' }}"
                                        class="form-control form-control-sm">
                                </div>

                                <!-- Delete button -->
                                <div class="col-md-1 d-flex justify-content-end">
                                    <button type="button"
                                            class="btn btn-danger remove-section-row w-100">
                                        X
                                    </button>
                                </div>

                            </div>

                                @endforeach
                            @else
                               
                                <div class="row section-row mb-3">
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Degree/Certificate</label>
                                            <input type="text" name="qualifications[degree][]" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Institute</label>
                                            <input type="text" name="qualifications[institute][]" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Completion Year</label>
                                            <input type="number" name="qualifications[year][]" class="form-control form-control-sm"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Upload Certificate</label>
                                            <input type="file" name="file[]" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Remark</label>
                                            <input type="text" name="qualifications[remark][]" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end mb-2">
                                        <button type="button" class="btn btn-danger remove-section-row w-100">X</button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="button" id="add-section-row" class="btn btn--primary px-4 mt-3">
                            + Add New Row
                        </button>

                      
                        <br><br>

                        <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                            Professional Experience
                        </h5>

                        <div id="experience-wrapper" class="mt-3">
                            @php
                                $experience = $assessor->professional_experience;
                                if (is_string($experience)) {
                                    $experience = ($experience);
                                }
                              //  dd($experience);
                            @endphp

                            @if(!empty($experience))
                                @foreach($experience as $exp)
                               
                                    <div class="row experience-row mb-3">

                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="form-label">Organization</label>
                                                <input type="text" name="experience[organization][]" value="{{ $exp['organization'] ?? '' }}" class="form-control form-control-sm" required>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group mb-2">
                                                <label class="form-label">Position</label>
                                                <input type="text" name="experience[position][]" value="{{ $exp['position'] ?? '' }}" class="form-control form-control-sm" required>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group mb-2">
                                                <label class="form-label">Duration</label>
                                                <input type="text" name="experience[duration][]" value="{{ $exp['duration'] ?? '' }}" class="form-control form-control-sm" placeholder="e.g. 1 year" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-2">
                                                <label class="form-label">Key Responsibilities</label>
                                                <textarea name="experience[key_responsibilities][]" class="form-control form-control-sm" rows="1">{{ $exp['key_responsibilities'] ?? '' }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-1 d-flex align-items-end mb-2">
                                            <button type="button" class="btn btn-danger remove-experience-row w-100">X</button>
                                        </div>

                                    </div>
                                @endforeach
                            @else
                                <div class="row experience-row mb-3">

                                    <div class="col-md-3">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Organization</label>
                                            <input type="text" name="experience[organization][]" class="form-control form-control-sm" required>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Position</label>
                                            <input type="text" name="experience[position][]" class="form-control form-control-sm" required>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Duration</label>
                                            <input type="text" name="experience[duration][]" class="form-control form-control-sm" placeholder="e.g. 1 year" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="form-label">Key Responsibilities</label>
                                            <textarea name="experience[key_responsibilities][]" class="form-control form-control-sm" rows="1"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end mb-2">
                                        <button type="button" class="btn btn-danger remove-experience-row w-100">X</button>
                                    </div>

                                </div>
                            @endif
                        </div>

                        <button type="button" id="add-experience-row" class="btn btn--primary px-4 mt-2">
                            + Add Experience
                        </button>
                        <div class="d-flex justify-content-end gap-3 mt-3">
                            <button type="submit" class="btn btn--primary px-4">
                                {{\App\CPU\translate('Update')}}
                            </button>
                        </div>
                    </div>
                </div> 

               
                <!-- <div class="card mt-3">
                    <div class="card-body">

                        <h5 class="mb-3 page-header-title d-flex align-items-center gap-2 border-bottom pb-3">
                            <i class="tio-user"></i>
                            4. Competence Assessment Summary
                        </h5>

                        <p class="small text-muted mb-0">
                            <strong>*Rating:</strong> c – Competent | s – Satisfactory | n – Needs Improvement
                        </p>

                        @php
                            $competenceList = [
                                "Knowledge of ISO/IEC 17011",
                                "Knowledge of Relevant Scheme Standards",
                                "Assessment & Audit Skills",
                                "Technical Expertise",
                                "Report Writing & Communication"
                            ];

                            $assessment = $assessor['assessment_summery'];
                            if (is_string($assessment)) {
                                $assessment = ($assessment);
                            }
                        @endphp

                        <table class="table table-bordered table-hover text-center align-middle mt-3">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-start">Competence Area</th>
                                    <th>Evaluator Remarks</th>
                                    <th>Rating (C/S/N)*</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($competenceList as $area)
                                    @php
                                        $current = null;
                                        if(!empty($assessment)){
                                            foreach($assessment as $row){
                                                if(isset($row['area']) && $row['area'] == $area){
                                                    $current = $row;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-start">{{ $area }}</td>

                                        <td>
                                            <input type="text"
                                                name="remarks[]"
                                                value="{{ $current['remark'] ?? '' }}"
                                                class="form-control form-control-sm">
                                        </td>

                                        <td>
                                            <select name="rating[]" class="form-control  text-center">
                                                <option value="">Select</option>
                                                <option value="Competent"
                                                    {{ (!empty($current['rating']) && $current['rating']=='Competent') ? 'selected' : '' }}>
                                                    Competent
                                                </option>
                                                <option value="Satisfactory"
                                                    {{ (!empty($current['rating']) && $current['rating']=='Satisfactory') ? 'selected' : '' }}>
                                                    Satisfactory
                                                </option>
                                                <option value="Needs Improvement"
                                                    {{ (!empty($current['rating']) && $current['rating']=='Needs Improvement') ? 'selected' : '' }}>
                                                    Needs Improvement
                                                </option>
                                            </select>
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end gap-3 mt-3">
                            <button type="submit" class="btn btn--primary px-4">
                                {{\App\CPU\translate('Update')}}
                            </button>
                        </div>

                    </div>
                </div> -->

            </form>
        </div>
    </div>

    <!--modal-->
    @include('shared-partials.image-process._image-crop-modal',['modal_id'=>'employee-image-modal'])
    <!--modal-->
</div>

@php
    $schemeString   = trim($assessor->scheme_id ?? '', "\"'");
    $selectedSchemes = $schemeString ? explode(',', $schemeString) : [];

    $areaString = trim($assessor->area_id ?? '', "\"'");
    $selectedAreas = $areaString ? explode(',', $areaString) : [];

    $scopeString = trim($assessor->scope_id ?? '', "\"'");
    $selectedScopes = $scopeString ? explode(',', $scopeString) : [];
@endphp
@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


 <script>
$(document).ready(function () {
    $('.scheme_id, .area_id, .scope_id').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });

    
    let selectedSchemes = @json($selectedSchemes).map(Number || function(){});
    let selectedAreas   = @json($selectedAreas).map(Number || function(){});
    let selectedScopes  = @json($selectedScopes).map(Number || function(){});

    const csrf = "{{ csrf_token() }}";

    function populateSelect($select, items, selectedValues) {
        $select.empty();
        if (!items || items.length === 0) {
            $select.append('<option disabled value="">No options</option>');
        } else {
            items.forEach(function(item) {
             
                const isSelected = selectedValues && selectedValues.indexOf(Number(item.id)) !== -1;
                $select.append('<option value="' + item.id + '"' + (isSelected ? ' selected' : '') + '>' + item.title + '</option>');
            });
        }
     
        if (selectedValues && selectedValues.length > 0) {
            $select.val(selectedValues.map(Number));
        }
        $select.trigger('change.select2');
    }

   
    function loadAreasForSchemes(schemeIds, selectedAreaIds = []) {
        $('#area_id').prop('disabled', true);
        $.ajax({
            url: "{{ url('admin/employee/get-areas-by-schemes') }}",
            type: 'POST',
            data: { scheme_ids: schemeIds, _token: csrf },
            dataType: 'json',
            cache: false,
            success: function(response) {
                populateSelect($('#area_id'), response, selectedAreaIds);
                $('#area_id').prop('disabled', false);

                if (selectedAreaIds && selectedAreaIds.length > 0) {
                    loadScopesForAreas(selectedAreaIds, selectedScopes);
                } else {
               
                    populateSelect($('#scope_id'), [], []);
                }
            },
            error: function() {
                $('#area_id').prop('disabled', false);
                populateSelect($('#area_id'), [], []);
                populateSelect($('#scope_id'), [], []);
                console.error('Failed to load areas');
            }
        });
    }


    function loadScopesForAreas(areaIds, selectedScopeIds = []) {
        $('#scope_id').prop('disabled', true);
        $.ajax({
            url: "{{ url('admin/employee/get-scopes-by-areas') }}",
            type: 'POST',
            data: { area_ids: areaIds, _token: csrf },
            dataType: 'json',
            cache: false,
            success: function(response) {
                populateSelect($('#scope_id'), response, selectedScopeIds);
                $('#scope_id').prop('disabled', false);
            },
            error: function() {
                $('#scope_id').prop('disabled', false);
                populateSelect($('#scope_id'), [], []);
                console.error('Failed to load scopes');
            }
        });
    }

    
    $('#scheme_id').on('change', function () {
        let schemeIds = $(this).val() || [];
      
        if (schemeIds.length > 0) {
            loadAreasForSchemes(schemeIds, []);
        } else {
            populateSelect($('#area_id'), [], []);
            populateSelect($('#scope_id'), [], []);
        }
    });

    $('#area_id').on('change', function () {
        let areaIds = $(this).val() || [];
        if (areaIds.length > 0) {
            loadScopesForAreas(areaIds, []);
        } else {
            populateSelect($('#scope_id'), [], []);
        }
    });

  
    if (selectedSchemes && selectedSchemes.length > 0) {
        $('#scheme_id').val(selectedSchemes.map(Number)).trigger('change.select2');
      
        loadAreasForSchemes(selectedSchemes, selectedAreas);
    } else {
    
        populateSelect($('#area_id'), [], []);
        populateSelect($('#scope_id'), [], []);
    }
});
</script>


    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });
    </script>

    <script>
       
        $(document).on('click', '#add-section-row', function () {
            let newRow = `
            <div class="row section-row mb-3">

                <div class="col-md-2">
                    <div class="form-group mb-2">
                        <label class="form-label">Degree/Certificate</label>
                        <input type="text" name="qualifications[degree][]" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-2">
                        <label class="form-label">Institute</label>
                        <input type="text" name="qualifications[institute][]" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-2">
                        <label class="form-label">Completion Year</label>
                        <input type="number" name="qualifications[year][]" class="form-control form-control-sm"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group mb-2">
                        <label class="form-label">Upload Certificate</label>
                        <input type="file" name="file[]" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-2">
                        <label class="form-label">Remark</label>
                        <input type="text" name="qualifications[remark][]" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="col-md-1 d-flex align-items-end mb-2">
                    <button type="button" class="btn btn-danger remove-section-row w-100">X</button>
                </div>

            </div>`;

            $('#section-wrapper').append(newRow);
        });

        $(document).on('click', '.remove-section-row', function () {
            let totalRows = $('#section-wrapper .section-row').length;
            if (totalRows <= 1) {
                toastr.error("You must keep at least one row.");
                return;
            }
            $(this).closest('.section-row').remove();
            toastr.success("Removed successfully.");
        });

        
        $(document).on('click', '#add-experience-row', function () {
            let newExp = `
            <div class="row experience-row mb-3">

                <div class="col-md-3">
                    <div class="form-group mb-2">
                        <label class="form-label">Organization</label>
                        <input type="text" name="experience[organization][]" class="form-control form-control-sm" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group mb-2">
                        <label class="form-label">Position</label>
                        <input type="text" name="experience[position][]" class="form-control form-control-sm" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group mb-2">
                        <label class="form-label">Duration</label>
                        <input type="text" name="experience[duration][]" class="form-control form-control-sm" placeholder="e.g. 1 year" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-2">
                        <label class="form-label">Key Responsibilities</label>
                        <textarea name="experience[key_responsibilities][]" class="form-control form-control-sm" rows="1"></textarea>
                    </div>
                </div>

                <div class="col-md-1 d-flex align-items-end mb-2">
                    <button type="button" class="btn btn-danger remove-experience-row w-100">X</button>
                </div>

            </div>`;
            $('#experience-wrapper').append(newExp);
        });

      
        $(document).on('click', '.remove-experience-row', function () {
            let total = $('#experience-wrapper .experience-row').length;
            if (total <= 1) {
                toastr.error("You must keep at least one row.");
                return;
            }
            $(this).closest('.experience-row').remove();
            toastr.success("Removed successfully.");
        });
    </script>

    @include('shared-partials.image-process._script',[
        'id'=>'employee-image-modal',
        'height'=>200,
        'width'=>200,
        'multi_image'=>false,
        'route'=>route('image-upload')
    ])
@endpush
