@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Assesor List'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-baseline gap-2 backbtndiv w-100">
                <!-- <img src="{{asset('/public/assets/back-end/img/employee.png')}}" width="20" alt=""> -->
                <a class="textfont-set" href="{{route('admin.dashboard.index')}}"> 
                <i class="tio-chevron-left"></i>Back</a>
                {{\App\CPU\translate('Assessor_list')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header flex-wrap gap-10">
                        <h5 class="mb-0 d-flex gap-2 align-items-center">
                            {{\App\CPU\translate('employee_table')}}
                            <span class="badge badge-soft-dark radius-50 fz-12">{{$em->total()}}</span>
                        </h5>
                        <div>
                            <!-- Search -->
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group input-group-merge input-group-custom">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input type="search" name="search" class="form-control"
                                           placeholder="{{\App\CPU\translate('search_by_name_or_email_or_phone')}}"
                                           value="{{$search}}" required>
                                    <button type="submit"
                                            class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                </div>
                            </form>
                            <!-- End Search -->
                        </div>
                        <!-- <div class="d-none d-flex justify-content-end">
                            <a href="{{route('admin.employee.add-new')}}" class="btn btn--primary">
                                <i class="tio-add"></i>
                                <span class="text">{{\App\CPU\translate('Add')}} {{\App\CPU\translate('New')}}</span>
                            </a>
                        </div> -->
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                               style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                               class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize table-nowrap">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('Image')}}</th>
                                <th>{{\App\CPU\translate('Name')}}</th>
                                <th>{{\App\CPU\translate('Email')}}</th>
                                <th>{{\App\CPU\translate('Phone')}}</th>
                                <th>{{\App\CPU\translate('Designation')}}</th>
                                <th>{{\App\CPU\translate('Qualification')}}</th>
                                <th>{{\App\CPU\translate('Experience')}}</th>
                                <th>{{\App\CPU\translate('Status')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($em as $k=>$e)
                                    <tr>
                                        <th scope="row">{{$k+1}}</th>
                                        <td>
                                            <img src="{{ asset('storage/app/public/admin/' . ($e->image ?: 'def.png')) }}"
                                                 onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                 alt="{{ $e->name }}"
                                                 style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:1px solid #e9eef6;">
                                        </td>
                                        <td class="text-capitalize">{{$e['name']}}</td>
                                        <td>
                                            {{$e['email']}}
                                        </td>
                                        <td>{{$e['phone']}}</td>
                                        <td>
                                            {{ $hasAssessorsTable ? data_get($e, 'assessor.apply_designation', '—') : '—' }}
                                        </td>
                                        <td>
                                            {{ $hasAssessorsTable ? data_get($e, 'assessor.highest_qualification', '—') : '—' }}
                                        </td>
                                        <td>
                                            {{ $hasAssessorsTable ? (data_get($e, 'assessor.experience', 0) . ' yrs') : '—' }}
                                        </td>
                                        <td>
                                            @if((int)$e->status === 1)
                                                <span class="badge badge-soft-success">Active</span>
                                            @else
                                                <span class="badge badge-soft-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$em->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

