@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @if(auth('admin')->user()->admin_role_id==1 || \App\CPU\Helpers::module_permission_check('dashboard'))
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header pb-3 mb-0 border-0">
                <div class="flex-between align-items-center">
                    <div>
                        <h1 class="page-header-title" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"> <b>{{\App\CPU\translate('Dashboard')}}</b> </h1>
                        <p class="mb-0 d-none">{{ \App\CPU\translate('Welcome_message') }}.</p>
                    </div>
                    @if (!isset($request_status))
                        <a href="{{route('admin.product.add-new')}}" class="btn btn--primary d-none">
                            <i class="tio-add"></i>
                            <span class="text">{{\App\CPU\translate('Add_New_Product')}}</span>
                        </a>
                    @endif
                </div>
            </div>
            <!-- End Page Header -->
           

            <!-- Business Analytics -->
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row flex-between align-items-center g-2 mb-0">
                        <div class="col-sm-6">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                <img src="{{asset('/public/assets/back-end/img/business_analytics.png')}}" alt="">{{\App\CPU\translate('business_analytics')}}</h4>
                        </div>
                        <!-- <div class="col-sm-6 d-flex justify-content-sm-end d-none">
                            <select class="custom-select w-auto" name="statistics_type"
                                    onchange="order_stats_update(this.value)">
                                <option
                                    value="overall" {{session()->has('statistics_type') && session('statistics_type') == 'overall'?'selected':''}}>
                                    {{ \App\CPU\translate('Overall_statistics')}}
                                </option>
                                <option
                                    value="today" {{session()->has('statistics_type') && session('statistics_type') == 'today'?'selected':''}}>
                                    {{ \App\CPU\translate("Todays Statistics")}}
                                </option>
                                <option
                                    value="this_month" {{session()->has('statistics_type') && session('statistics_type') == 'this_month'?'selected':''}}>
                                    {{ \App\CPU\translate("This Months Statistics")}}
                                </option>
                            </select>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="card mb-3 fff">
                <div class="card-body">
                    <div class="row" id="order_stats">
                        @include('admin-views.partials._dashboard-order-stats',['data'=>$data])
                    </div>
                </div>
            </div>
            <!-- End Business Analytics -->

            @if(!empty($isoCertifications))
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-3">
                            <i class="tio-checkmark-square"></i> ISO Standards Accreditation
                        </h4>
                        <div class="d-flex flex-wrap" style="gap:.5rem;">
                            @foreach($isoCertifications as $iso)
                                <span class="badge badge-soft-primary px-3 py-2">{{ $iso }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Admin Wallet -->

            @if(auth('admin')->user()->admin_role_id==1)
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-3">
                        <img width="20" class="mb-1" src="{{ asset('/public/assets/back-end/img/admin-wallet.png') }}" alt=""> {{ \App\CPU\translate('admin_earnings') }}
                    </h4>

                    <div class="row g-2" id="order_stats">
                        @include('admin-views.partials._dashboard-wallet-stats',['data'=>$data])
                    </div>
                </div>
            </div>
            @endif
            <!-- End Admin Wallet -->

           
        </div>
    @else
        <div class="content container-fluid  d-none">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-12 mb-2 mb-sm-0">
                        <h3 class="text-center">{{\App\CPU\translate('hi')}} {{auth('admin')->user()->name}}, {{\App\CPU\translate('welcome_to_dashboard')}}.</h3>
                    </div>
                </div>
            </div>
            <!-- End Page Header -->
        </div>
    @endif
@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{asset('public/assets/back-end')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
@endpush


@push('script_2')
   

@endpush

