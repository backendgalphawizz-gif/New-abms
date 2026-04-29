@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Edit Role'))
@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-baseline gap-2">
                <!-- <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" alt=""> -->
                <!-- <a class="textfont-set" href="{{route('admin.dashboard.index')}}"> 
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="24" fill="none">
                    <path d="M10.2988 18.2985L4.24883 12.2745L10.2988 6.24951" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20px"/>
                </svg>Back</a> -->
                {{\App\CPU\translate('Role_Update')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="card">
            <div class="card-body">
                <form id="submit-create-role" action="{{route('admin.custom-role.update',[$role['id']])}}" method="post"
                      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label for="name" class="title-color">{{\App\CPU\translate('role_name')}}</label>
                                <input type="text" name="name" value="{{$role['name']}}" class="form-control" id="name"
                                       aria-describedby="emailHelp"
                                       placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('Store')}}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-4 flex-wrap">
                        <label for="module" class="title-color mb-0">{{\App\CPU\translate('module_permission')}}
                            : </label>
                        <div class="form-group d-flex gap-2">
                            <input type="checkbox" id="select_all">
                            <label class="title-color mb-0"
                                   for="select_all">{{\App\CPU\translate('Select_All')}}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-sm-6 d-none">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="dashboard" checked
                                       class="form-check-input module-permission"
                                       id="dashboard" {{in_array('dashboard',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="order">{{\App\CPU\translate('Dashboard')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission"
                                    name="modules[]" value="application_management"
                                    id="application_management"
                                    {{ in_array('application_management', (array) json_decode($role['module_access'])) ? 'checked' : '' }}>
                                <label class="title-color mb-0"
                                    style="{{ Session::get('direction') === 'rtl' ? 'margin-right: 1.25rem;' : '' }};"
                                    for="application_management">
                                    {{ \App\CPU\translate('application_management') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3 d-none">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission"
                                    name="modules[]" value="transaction_management"
                                    id="transaction_management"
                                    {{ in_array('transaction_management', (array) json_decode($role['module_access'])) ? 'checked' : '' }}>
                                <label class="title-color mb-0"
                                    style="{{ Session::get('direction') === 'rtl' ? 'margin-right: 1.25rem;' : '' }};"
                                    for="transaction_management">
                                    {{ \App\CPU\translate('transaction_management') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 d-none">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input module-permission" name="modules[]"
                                       {{in_array('pos_management',(array)json_decode($role['module_access']))?'checked':''}} value="pos_management"
                                       id="pos_management">
                                <label class="title-color mb-0"
                                       style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="pos_management">{{\App\CPU\translate('pos_management')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 d-none">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="order_management"
                                       class="form-check-input module-permission"
                                       id="order" {{in_array('order_management',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="order">{{\App\CPU\translate('Order_Management')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 d-none">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="product_management"
                                       class="form-check-input module-permission"
                                       id="product" {{in_array('product_management',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="product">{{\App\CPU\translate('Product_Management')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 d-none">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="promotion_management"
                                       class="form-check-input module-permission"
                                       id="promotion_management" {{in_array('promotion_management',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="promotion_management">{{\App\CPU\translate('Promotion_Management')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 d-none">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="support_section"
                                       class="form-check-input module-permission"
                                       id="support_section" {{in_array('support_section',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="support_section">{{\App\CPU\translate('Help_&_Support_Section')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 d-none">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="report"
                                       class="form-check-input module-permission"
                                       id="report" {{in_array('report',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="report">{{\App\CPU\translate('reports_and_analytics')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="user_section"
                                       class="form-check-input module-permission"
                                       id="user_section" {{in_array('user_section',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="user_section">{{\App\CPU\translate('user_management')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 d-none">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="system_settings"
                                       class="form-check-input module-permission"
                                       id="system_settings" {{in_array('system_settings',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="system_settings">{{\App\CPU\translate('System_Settings')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission"
                                    name="modules[]" value="employee_management"
                                    id="employee_management"
                                    {{ in_array('employee_management', (array) json_decode($role['module_access'])) ? 'checked' : '' }}>
                                <label class="title-color mb-0"
                                    style="{{ Session::get('direction') === 'rtl' ? 'margin-right: 1.25rem;' : '' }};"
                                    for="employee_management">
                                    {{ \App\CPU\translate('Employee_Management') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission"
                                    name="modules[]" value="training_management"
                                    id="training_management"
                                    {{ in_array('training_management', (array) json_decode($role['module_access'])) ? 'checked' : '' }}>
                                <label class="title-color mb-0"
                                    style="{{ Session::get('direction') === 'rtl' ? 'margin-right: 1.25rem;' : '' }};"
                                    for="training_management">
                                    {{ \App\CPU\translate('Training_Management') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission"
                                    name="modules[]" value="fee_management"
                                    id="fee_management"
                                    {{ in_array('fee_management', (array) json_decode($role['module_access'])) ? 'checked' : '' }}>
                                <label class="title-color mb-0"
                                    style="{{ Session::get('direction') === 'rtl' ? 'margin-right: 1.25rem;' : '' }};"
                                    for="fee_management">
                                    {{ \App\CPU\translate('Fee_Management') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary">{{\App\CPU\translate('reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>

        $('#submit-create-role').on('submit', function (e) {

            var fields = $("input[name='modules[]']").serializeArray();
            if (fields.length === 0) {
                toastr.warning('{{ \App\CPU\translate('select_minimum_one_selection_box') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
                return false;
            } else {
                $('#submit-create-role').submit();
            }
        });
    </script>

    <script>
        $("#select_all").on('change', function () {
            if ($("#select_all").is(":checked") === true) {
                console.log($("#select_all").is(":checked"));
                $(".module-permission").prop("checked", true);
            } else {
                $(".module-permission").removeAttr("checked");
            }
        });
    </script>
@endpush
