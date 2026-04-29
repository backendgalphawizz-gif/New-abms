@extends('super-admin.subdomains.inspect-layout')

@section('inspect_content')
    <div class="card">
        <div class="card-header">
            <h2 class="h4 mb-0">{{ \App\CPU\translate('Customers') }}</h2>
        </div>
        <div class="card-body p-0">
            @if($customers === null)
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
                            <th>{{ \App\CPU\translate('Created at') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($customers as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ trim(($user->f_name ?? '').' '.($user->l_name ?? '')) ?: ($user->name ?? '—') }}</td>
                                <td>{{ $user->email ?? '—' }}</td>
                                <td>{{ $user->phone ?? '—' }}</td>
                                <td>{{ $user->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ \App\CPU\translate('No data found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $customers->links() }}</div>
            @endif
        </div>
    </div>
@endsection
