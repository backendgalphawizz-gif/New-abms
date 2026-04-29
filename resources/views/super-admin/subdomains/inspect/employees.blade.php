@extends('super-admin.subdomains.inspect-layout')

@section('inspect_content')
    <div class="card">
        <div class="card-header">
            <h2 class="h4 mb-0">{{ \App\CPU\translate('Employees') }}</h2>
        </div>
        <div class="card-body p-0">
            @if($employees === null)
                <p class="text-muted p-4 mb-0">{{ \App\CPU\translate('No data found') }}</p>
            @else
                <div class="table-responsive">
                    <table class="table table-thead-bordered table-nowrap table-align-middle card-table mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>{{ \App\CPU\translate('Name') }}</th>
                            <th>{{ \App\CPU\translate('email') }}</th>
                            <th>{{ \App\CPU\translate('Phone') }}</th>
                            <th>{{ \App\CPU\translate('Role') }} ID</th>
                            <th>{{ \App\CPU\translate('Status') }}</th>
                            <th>{{ \App\CPU\translate('Created at') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($employees as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->name ?? '—' }}</td>
                                <td>{{ $admin->email ?? '—' }}</td>
                                <td>{{ $admin->phone ?? '—' }}</td>
                                <td>{{ $admin->admin_role_id ?? '—' }}</td>
                                <td>{{ $admin->status ?? '—' }}</td>
                                <td>{{ $admin->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">{{ \App\CPU\translate('No data found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $employees->links() }}</div>
            @endif
        </div>
    </div>
@endsection
