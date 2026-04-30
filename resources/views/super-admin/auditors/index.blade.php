@extends('layouts.super-admin.app')

@section('title', \App\CPU\translate('Super Admin') . ' | Auditors')

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-3 mb-3 border-0 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-header-title"><b>Auditor List</b></h1>
                <p class="text-muted mb-0">Manage all auditors created by Super Admin.</p>
            </div>
            <a href="{{ route('super-admin.auditors.create') }}" class="btn btn--primary">+ Add New Auditor</a>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form method="get" action="{{ route('super-admin.auditors.index') }}" class="form-inline">
                    <input type="text" class="form-control mr-2 mb-2" name="search" value="{{ $search }}" placeholder="Search by name/email/phone">
                    <button type="submit" class="btn btn-outline-primary mb-2">Search</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>SL</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Designation</th>
                        <th>Qualification</th>
                        <th>Experience</th>
                        <th>Profile</th>
                        <th>Remark</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($auditors as $key => $auditor)
                        <tr>
                            <td>{{ $auditors->firstItem() + $key }}</td>
                            <td>
                                <img
                                    src="{{ ($auditor->image && $auditor->image !== 'def.png') ? asset('storage/app/public/auditor/' . $auditor->image) : asset('assets/back-end/img/400x400/img2.jpg') }}"
                                    onerror="this.onerror=null;this.src='{{ asset('storage/app/public/admin/') }}/{{ $auditor->image }}';setTimeout(()=>{if(!this.complete||this.naturalWidth===0){this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}';}},100);"
                                    alt="{{ $auditor->name }}"
                                    style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:1px solid #e9eef6;"
                                >
                            </td>
                            <td>{{ $auditor->name }}</td>
                            <td>{{ $auditor->email }}</td>
                            <td>{{ $auditor->phone }}</td>
                            <td>{{ $hasAssessorsTable ? data_get($auditor, 'assessor.apply_designation', '—') : '—' }}</td>
                            <td>{{ $hasAssessorsTable ? data_get($auditor, 'assessor.highest_qualification', '—') : '—' }}</td>
                            <td>{{ $hasAssessorsTable ? (data_get($auditor, 'assessor.experience', 0) . ' yrs') : '—' }}</td>
                            <td>
                                @if($hasAssessorsTable)
                                    @php $ps = (int) data_get($auditor, 'assessor.profile_status', 0); @endphp
                                    @if($ps === 1)
                                        <span class="badge badge-soft-success">Approved</span>
                                    @elseif($ps === 2)
                                        <span class="badge badge-soft-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-soft-warning">Pending</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-muted small" style="max-width:200px;">
                                {{ $hasAssessorsTable ? \Illuminate\Support\Str::limit((string) data_get($auditor, 'assessor.remark'), 48) : '—' }}
                            </td>
                            <td>
                                @if((int)$auditor->status === 1)
                                    <span class="badge badge-soft-success">Active</span>
                                @else
                                    <span class="badge badge-soft-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('super-admin.auditors.status', [$auditor->id, (int) $auditor->status === 1 ? 0 : 1]) }}"
                                   class="btn btn-sm {{ (int) $auditor->status === 1 ? 'btn-warning' : 'btn-success' }} mr-1">
                                    {{ (int) $auditor->status === 1 ? 'In Active' : 'Active' }}
                                </a>
                                <a href="{{ route('super-admin.auditors.edit', $auditor->id) }}"
                                   class="btn btn-sm btn-primary mr-1">Edit</a>
                                <form action="{{ route('super-admin.auditors.destroy', $auditor->id) }}"
                                      method="post" class="d-inline"
                                      onsubmit="return confirm('Delete this auditor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">No auditors found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $auditors->links() }}
            </div>
        </div>
    </div>
@endsection

