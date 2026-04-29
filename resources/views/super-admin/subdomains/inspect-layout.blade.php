@extends('layouts.super-admin.app')

@section('title', $pageTitle ?? \App\CPU\translate('Super Admin'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="alert alert-soft-secondary border mb-3 mb-md-4" role="alert">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge badge-soft-dark">{{ \App\CPU\translate('Read-only') }}</span>
                <span>{{ \App\CPU\translate('Tenant data preview in Super Admin. No changes are applied on the tenant.') }}</span>
            </div>
        </div>

        @yield('inspect_content')
    </div>
@endsection
