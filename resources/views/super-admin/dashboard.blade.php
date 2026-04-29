@extends('layouts.super-admin.app')

@section('title', \App\CPU\translate('Super Admin') . ' | ' . \App\CPU\translate('Dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-3 mb-0 border-0">
            <div class="flex-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <b>{{ \App\CPU\translate('Dashboard') }}</b>
                    </h1>
                    <p class="mb-0 text-muted">{{ \App\CPU\translate('Platform super admin') }}</p>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h4 class="d-flex align-items-center text-capitalize gap-10 mb-3">
                    <i class="tio-globe"></i>
                    {{ \App\CPU\translate('Entities') }}
                </h4>
                <p class="text-muted">{{ \App\CPU\translate('Active entities') }}: <strong>{{ $entityCount }}</strong></p>
                <a href="{{ route('super-admin.entities.index') }}" class="btn btn--primary">
                    <i class="tio-add"></i>
                    <span class="text">{{ \App\CPU\translate('Manage') }}</span>
                </a>
            </div>
        </div>
    </div>
@endsection
