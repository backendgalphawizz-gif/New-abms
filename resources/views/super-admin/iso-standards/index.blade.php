@extends('layouts.super-admin.app')

@section('title', \App\CPU\translate('Super Admin') . ' | ISO Standards')

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-3 mb-3 border-0 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-header-title"><b>ISO Standards</b></h1>
                <p class="text-muted mb-0">Manage checklist options shown in create entity form.</p>
            </div>
            <a href="{{ route('super-admin.iso-standards.create') }}" class="btn btn--primary">+ Add ISO Standard</a>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>SL</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Sort</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($standards as $key => $standard)
                        <tr>
                            <td>{{ $standards->firstItem() + $key }}</td>
                            <td><strong>{{ $standard->code }}</strong></td>
                            <td>{{ $standard->name }}</td>
                            <td>{{ $standard->sort_order }}</td>
                            <td>
                                @if($standard->is_active)
                                    <span class="badge badge-soft-success">Active</span>
                                @else
                                    <span class="badge badge-soft-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('super-admin.iso-standards.edit', $standard) }}"
                                   class="btn btn-sm btn-primary mr-1">Edit</a>
                                <form action="{{ route('super-admin.iso-standards.destroy', $standard) }}"
                                      method="post"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this ISO standard?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No ISO standards found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $standards->links() }}
            </div>
        </div>
    </div>
@endsection
