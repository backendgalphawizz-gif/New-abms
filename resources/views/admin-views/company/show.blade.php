@extends('layouts.back-end.app')
{{--@section('title','Customer')--}}
@section('title', \App\CPU\translate('Company Details'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">

                    <!-- Page Title -->
                    <div class="mb-3">
                        <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                            <img width="20" src="{{asset('/public/assets/back-end/img/customer.png')}}" alt="">
                            {{\App\CPU\translate('company_details')}}
                        </h2>
                    </div>
                    <!-- End Page Title -->

                    <div class="d-sm-flex align-items-sm-center">
                        <h3 class="page-header-title">{{\App\CPU\translate('Company')}} #{{ $company['name'] }} ({{ $company['organization'] }})</h3>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i> {{\App\CPU\translate('Joined At')}} : {{date('d M Y H:i:s',strtotime($company['created_at']))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" id="printableArea">
            <div class="col-lg-9 mb-3 mb-lg-0">
                <div class="card">
                    <div class="p-3">
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom p-3">
                        <div class="media-body d-flex flex-column gap-1">
                            <div class="row">
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Name</strong>: {{ $company->name }}</label></span>
                                </div>
                                
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Organization</strong>: {{ $company->organization }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Phone</strong>: {{ $company->phone }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Email</strong>: {{ $company->email }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Address</strong>: {{ $company->address }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Address In Other Language</strong>: {{ $company->address_other_language }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>City</strong>: {{ $company->city }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Country</strong>: {{ $company->country }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Fax</strong>: {{ $company->fax }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Pincode</strong>: {{ $company->pincode }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Website</strong>: {{ $company->website }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Status</strong>: {{ $company->status }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Remark</strong>: {{ $company->remark }}</label></span>
                                </div>
                                <div class="col-lg-12">

                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold title-color fz-14">{{\App\CPU\translate('Company Status')}}</label>
                                        <select name="company_status" class="status form-control company_status" data-id="{{$company['id']}}">
                                            <option value="pending" {{ $company->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                            <option value="approve" {{ $company->status == 'approve' ? 'selected' : '' }}>{{ __('Approve') }}</option>
                                            <option value="reject" {{ $company->status == 'reject' ? 'selected' : '' }}>{{ __('Reject') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <hr>
                                    <div class="px-3 py-4">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                                <h3 class="customHeading mb-0">Contact Person Detail</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                        <tbody>
                                            @php $contactPerson = $company->contact_person_details; @endphp
                                            @foreach ($contactPerson as $cKey => $contact)
                                                <tr>
                                                    <th>{{ ucwords($cKey) }}</th>
                                                    <td>{{ $contact }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-12">
                                    <hr>
                                    <div class="px-3 py-4">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                                <h3 class="customHeading mb-0">Parent Organization</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                        <tbody>
                                            @php $parentOrganization = $company->parent_organization; @endphp
                                            @foreach ($parentOrganization as $pKey => $porg)
                                                <tr>
                                                    <th>{{ ucwords($pKey) }}</th>
                                                    <td>{{ $porg }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-12">
                                    <hr>
                                    <div class="px-3 py-4">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                                <h3 class="customHeading mb-0">Invoice Address</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                        <tbody>
                                            @php $parentOrganization = $company->invoice_address; @endphp
                                            @foreach ($parentOrganization as $pKey => $porg)
                                                <tr>
                                                    <th>{{ ucwords($pKey) }}</th>
                                                    <td>{{ $porg }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-12">
                                    <hr>
                                    <div class="px-3 py-4">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                                <h3 class="customHeading mb-0">Ownership Details</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                        <tbody>
                                            @php $parentOrganization = $company->ownership_details; @endphp
                                            @foreach ($parentOrganization as $pKey => $porg)
                                                <tr>
                                                    <th>{{ ucwords($pKey) }}</th>
                                                    <td>{{ $porg }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <!-- Card -->
                <div class="card h-100" >
                    @if($company->user)
                        <div class="card-body">
                            <h4 class="mb-4 d-flex align-items-center gap-2">
                                <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                                {{\App\CPU\translate('User')}}
                            </h4>

                            <div class="media">
                                <div class="mr-3">
                                    <img
                                        class="avatar rounded-circle avatar-70"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/profile/'.$company->user->image??'')}}"
                                        alt="Image">
                                </div>
                                <div class="media-body d-flex flex-column gap-1">
                                    <span class="title-color hover-c1"><strong>{{$company->user['f_name'].' '.$company->user['l_name']}}</strong></span>
                                    <span class="title-color">Email: <strong>{{ $company->user['email'] }}</strong></span>
                                    <span class="title-color">Mobile: <strong>{{ $company->user['phone'] }}</strong></span>                                    
                                </div>
                            </div>
                        </div>
                    @endif <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection

@push('script_2')
<script>
    $(document).on('change', '.company_status', function() {
        let status = $(this).val()

        Swal.fire({
            title: "Please add remark for update status",
            input: "text",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Update",
            showLoaderOnConfirm: true,
            preConfirm: async (login) => {

            },
            allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                console.log('result ------------ ', result)
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.company.status_update') }}",
                        data: {
                            id:"{{ $company->id }}",
                            status:status,
                            remarks:result.value,
                            '_token': "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function (response) {
                            Swal.fire('success', 'Status updated success', 'success').then(() => {
                                window.location.reload()
                            })
                        }
                    });
                }
            });
    })
</script>
@endpush
