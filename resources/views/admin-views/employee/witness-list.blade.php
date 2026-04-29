@extends('layouts.back-end.app')
@section('title', 'Witness List')

@section('content')
<div class="content container-fluid">

    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-baseline gap-2 backbtndiv w-100">
            <a class="textfont-set" href="{{route('admin.dashboard.index')}}">
            <i class="tio-chevron-left"></i>Back</a>
            Witness Reports
        </h2>
    </div>

    <!-- STATUS COUNTERS -->
    <div class="row mb-3">
        <div class="col-sm-4">
            <a href="{{ request()->fullUrlWithQuery(['status'=>0]) }}">
                <div class="card card-body text-center border-warning">
                    <h4 class="text-warning mb-0">{{ $counts['pending'] }} Pending</h4>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a href="{{ request()->fullUrlWithQuery(['status'=>1]) }}">
                <div class="card card-body text-center border-success">
                    <h4 class="text-success mb-0">{{ $counts['approved'] }} Approved</h4>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a href="{{ request()->fullUrlWithQuery(['status'=>2]) }}">
                <div class="card card-body text-center border-danger">
                    <h4 class="text-danger mb-0">{{ $counts['rejected'] }} Rejected</h4>
                </div>
            </a>
        </div>
    </div>

    <!-- Card -->
    <div class="card">
        <div class="card-header flex-wrap gap-10">
            <h5 class="mb-0 d-flex gap-2 align-items-center">
                Witness Table
                <span class="badge badge-soft-dark radius-50 fz-12">{{ $reports->total() }}</span>
            </h5>

            <!-- Search -->
            <form action="{{ url()->current() }}" method="GET">
                <div class="input-group input-group-merge input-group-custom">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="tio-search"></i></div>
                    </div>
                    <input type="search" name="search" class="form-control"
                           placeholder="Search by Assessor or User" value="{{ request('search') }}">
                    <button type="submit" class="btn btn--primary">Search</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100">
                <thead class="thead-light thead-50 text-capitalize table-nowrap">
                    <tr>
                        <th>SL</th>
                        <th>Assessor</th>
                        <th>Filled By</th>
                        <th>Scheme</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($reports as $key => $r)
                    <tr>
                        <th scope="row">{{ $reports->firstItem() + $key }}</th>

                        <td>{{ $r->assessor->admin->name ?? '-' }}</td>
                        <td>{{ $r->user->name ?? '-' }}</td>
                        <td>{{ $r->scheme->title ?? '-' }}</td>
                        <td>{{ $r->witness_date ? \Carbon\Carbon::parse($r->witness_date)->format('d M Y') : '-' }}</td>

                        <td>
                            @if($r->status == 0)
                                <span class="badge badge-warning">Pending</span>
                            @elseif($r->status == 1)
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('admin.assessor.view-witness', $r->id) }}"
                               class="btn btn-outline-info btn-sm square-btn" title="View Report">
                                <i class="tio-user"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No witness reports found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-responsive mt-4">
            <div class="px-4 d-flex justify-content-lg-end">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
