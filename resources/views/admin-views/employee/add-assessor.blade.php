@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('assessor Add'))
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
</style>

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/add-new-assessor.png')}}" alt="">
                {{\App\CPU\translate('Add_New_Assessor')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('admin.assessor.add-assessor')}}" method="post" enctype="multipart/form-data"
                      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                                <i class="tio-user"></i>
                                {{\App\CPU\translate('General_Information')}}
                            </h5>
                            <div class="row">
                                <input type="hidden" name="role_id" value="3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name"
                                               class="title-color">{{\App\CPU\translate('Full Name')}}</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                               placeholder="{{\App\CPU\translate('Ex')}} : Jhon Doe"
                                               value="{{old('name')}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{\App\CPU\translate('Phone')}}</label>
                                        <input type="number" name="phone" value="{{old('phone')}}" class="form-control"
                                               id="phone"
                                               placeholder="{{\App\CPU\translate('Ex')}} : +88017********" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name"
                                               class="title-color">{{\App\CPU\translate('employee_image')}}</label>
                                        <span class="text-info">( {{\App\CPU\translate('ratio')}} 1:1 )</span>
                                        <div class="form-group">
                                            <div class="custom-file text-left">
                                                <input type="file" name="image" id="customFileUpload"
                                                       class="custom-file-input"
                                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                       required>
                                                <label class="custom-file-label"
                                                       for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img class="upload-img-view" id="viewer"
                                             src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}"
                                             alt="Product thumbnail"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                                <i class="tio-user"></i>
                                {{\App\CPU\translate('General_Information')}}
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{\App\CPU\translate('Email')}}</label>
                                        <input type="email" name="email" value="{{old('email')}}" class="form-control"
                                               id="email"
                                               placeholder="{{\App\CPU\translate('Ex')}} : ex@gmail.com" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password"
                                               class="title-color">{{\App\CPU\translate('password')}}</label>
                                        <input type="text" name="password" class="form-control" id="password"
                                               placeholder="{{\App\CPU\translate('Password')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="confirm_password"
                                               class="title-color">{{\App\CPU\translate('confirm_password')}}</label>
                                        <input type="text" name="confirm_password" class="form-control"
                                               id="confirm_password"
                                               placeholder="{{\App\CPU\translate('Confirm Password')}}" required>
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
                                        <label for="name" class="title-color">{{\App\CPU\translate('id_number')}}</label>
                                        <input type="text" name="id_number" value="{{old('id_number')}}" class="form-control"
                                               id="id_number" placeholder="{{\App\CPU\translate('Ex')}} : ABC1234" required>
                                    </div>
                                </div>
                               
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{\App\CPU\translate('applied_designation')}}</label>
                                        <input type="apply_designation" name="apply_designation" value="{{old('apply_designation')}}" class="form-control"
                                               id="apply_designation" >
                                    </div>
                                </div>
                                
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color">{{\App\CPU\translate('highest_qualification')}}</label>
                                            <input type="text" name="highest_qualification" value="{{ old('highest_qualification') }}" 
                                                class="form-control" placeholder="Ex: B.Tech, MBA" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color">{{\App\CPU\translate('technical_area')}}</label>
                                            <input type="text" name="technical_area" value="{{ old('technical_area') }}" 
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color">{{\App\CPU\translate('experience_(in_years)')}}</label>
                                            <input type="text" name="first_experience" value="{{ old('experience') }}" 
                                                class="form-control" placeholder="Ex: 5 years"  required>
                                        </div>
                                    </div>
                              
                               

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{\App\CPU\translate('home_address')}}</label>
                                        <input type="home_address" name="home_address" value="{{old('home_address')}}" class="form-control"
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
                                        <label class="title-color">{{ \App\CPU\translate('Select Scheme') }}</label>
                                        <select class="form-control scheme_id" name="scheme_id[]" id="scheme_id" multiple required>
                                            @foreach ($scheme as $value)
                                                <option value="{{ $value['id'] }}">{{ $value['title'] }}</option>
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

                            <h5>Qualification</h5>

                          <div id="section-wrapper" class="mt-3">

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
                                    <button type="button" class="btn btn-danger  remove-section-row ">X</button>
                                </div>

                            </div>

                         </div>

                        <button type="button" id="add-section-row" class="btn btn--primary px-4 mt-3">
                        + Add New Row </button>

                       <br><br>

                        
                                <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                                    
                                    Professional Experience
                                </h5>

                                <div id="experience-wrapper" class="mt-3">

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
                                                <label class="form-label">Duration(Years)</label>
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
                                            <button type="button" class="btn btn-danger remove-experience-row">X</button>
                                        </div>

                                    </div>

                                </div>

                                <button type="button" id="add-experience-row" class="btn btn--primary px-4 mt-2">+ Add Experience</button>
                             <div class="d-flex justify-content-end gap-3">
                                <button type="reset" id="reset" class="btn btn-secondary px-4">{{\App\CPU\translate('reset')}}</button>
                                 <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('submit')}}</button>
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

                            <table class="table table-bordered table-hover text-center align-middle">
                                <thead class="table-secondary">
                                    <tr>
                                        <th class="text-start">Competence Area</th>
                                        <th>Evaluator Remarks</th>
                                        <th>Rating (C/S/N)*</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $competenceList = [
                                            "Knowledge of ISO/IEC 17011",
                                            "Knowledge of Relevant Scheme Standards",
                                            "Assessment & Audit Skills",
                                            "Technical Expertise",
                                            "Report Writing & Communication"
                                        ];
                                    @endphp

                                    @foreach($competenceList as $area)
                                    <tr>
                                        <td class="text-start">{{ $area }}</td>
                                        <td><input type="text" name="remarks[]" class="form-control form-control-sm"></td>
                                        <td>
                                            <select name="rating[]" class="form-control  text-center">
                                                <option value="">Select</option>
                                                <option value="Competent">Competent</option>
                                                <option value="Satisfactory">Satisfactory</option>
                                                <option value="Needs Improvement">Needs Improvement</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                           
                        </div>
                    </div> -->

                </form>
            </div>
        </div>
    </div>
@endsection


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@push('script')
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

    <script>
  $(document).ready(function() {
    $('.scheme_id').select2({
      placeholder: "Select an option",
      allowClear: true
    });
  });
  $(document).ready(function() {
    $('.area_id').select2({
      placeholder: "Select an option",
      allowClear: true
    });
  });
  $(document).ready(function() {
    $('.scope_id').select2({
      placeholder: "Select an option",
      allowClear: true
    });
  });
</script>

    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
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

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).on('change', '#scheme_id', function () {
        var schemeIds = $(this).val(); 

        if (schemeIds && schemeIds.length > 0) {
            $.ajax({
                url: "{{ url('admin/employee/get-areas-by-schemes') }}", 
                type: 'POST',
                data: {
                    scheme_ids: schemeIds,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function (response) {
                    $('#area_id').empty().append('<option disabled>Select Area</option>');
                    $.each(response, function (key, area) {
                        $('#area_id').append('<option value="' + area.id + '">' + area.title + '</option>');
                    });
                },
                error: function () {
                    alert('Something went wrong!');
                }
            });
        } else {
            $('#area_id').empty().append('<option disabled>Select Area</option>');
        }
    });

    $(document).on('change', '#area_id', function () {
        var areaIds = $(this).val(); 

        if (areaIds && areaIds.length > 0) {
            $.ajax({
                url: "{{ url('admin/employee/get-scopes-by-areas') }}", 
                type: 'POST',
                data: {
                    area_ids: areaIds,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function (response) {
                    $('#scope_id').empty().append('<option disabled>Select Scope</option>');
                    $.each(response, function (key, scope) {
                        $('#scope_id').append('<option value="' + scope.id + '">' + scope.title + '</option>');
                    });
                },
                error: function () {
                    alert('Something went wrong!');
                }
            });
        } else {
            $('#scope_id').empty().append('<option disabled>Select Scope</option>');
        }
    });

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
                <button type="button" class="btn btn-danger remove-section-row ">X</button>
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

        let rowIndex = $(this).closest('.section-row').index() + 1;

        $(this).closest('.section-row').remove();

        toastr.success(" Removed successfully.");
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
            <button type="button" class="btn btn-danger remove-experience-row">X</button>
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
});


</script>    
@endpush
