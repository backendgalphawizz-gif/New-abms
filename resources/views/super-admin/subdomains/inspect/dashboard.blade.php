@extends('super-admin.subdomains.inspect-layout')

@section('inspect_content')
    <div class="card mb-3">
        <div class="card-header">
            <h2 class="h4 mb-0">{{ \App\CPU\translate('Dashboard') }}</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Total Application') }}</small>
                        <h3 class="mb-0">{{ $data['total_application'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Pending Application') }}</small>
                        <h3 class="mb-0">{{ $data['pending_application'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Certificate Issue') }}</small>
                        <h3 class="mb-0">{{ $data['complete_application'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Reject Applications') }}</small>
                        <h3 class="mb-0">{{ $data['rejected_application'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Total Customer') }}</small>
                        <h3 class="mb-0">{{ $data['total_customer'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Orders') }}</small>
                        <h3 class="mb-0">{{ $data['total_orders'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Products') }}</small>
                        <h3 class="mb-0">{{ $data['total_products'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Sellers') }}</small>
                        <h3 class="mb-0">{{ $data['total_sellers'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Quality') }} ({{ \App\CPU\translate('admins') }})</small>
                        <h3 class="mb-0">{{ $data['total_quality'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Assessor') }}</small>
                        <h3 class="mb-0">{{ $data['total_assessor'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Accreditation') }}</small>
                        <h3 class="mb-0">{{ $data['total_accreditation'] }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Total earning') }}</small>
                        <h3 class="mb-0">{{ $currencySymbol }}{{ number_format((float) ($data['total_earning'] ?? 0), 2) }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card card-body border h-100">
                        <small class="text-muted">{{ \App\CPU\translate('Pending amount') }}</small>
                        <h3 class="mb-0">{{ $currencySymbol }}{{ number_format((float) ($data['pending_amount'] ?? 0), 2) }}</h3>
                    </div>
                </div>
            </div>

            @if(!empty($isoCertifications))
                <hr>
                <h5 class="mb-2">{{ \App\CPU\translate('ISO certifications') }}</h5>
                <div class="d-flex flex-wrap" style="gap:.5rem;">
                    @foreach($isoCertifications as $iso)
                        <span class="badge badge-soft-primary px-3 py-2">{{ $iso }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
