@extends('super-admin.subdomains.inspect-layout')

@section('inspect_content')
    <div class="card">
        <div class="card-header">
            <h2 class="h4 mb-0">{{ \App\CPU\translate('Applications') }}</h2>
        </div>
        <div class="card-body p-0">
            @if($applications === null)
                <p class="text-muted p-4 mb-0">{{ \App\CPU\translate('No data found') }}</p>
            @else
                <div class="table-responsive">
                    <table class="table table-thead-bordered table-nowrap table-align-middle card-table mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>{{ \App\CPU\translate('Customer') }}</th>
                            <th>{{ \App\CPU\translate('Status') }}</th>
                            <th>{{ \App\CPU\translate('is_accept') }}</th>
                            <th>{{ \App\CPU\translate('Created at') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($applications as $app)
                            <tr>
                                <td>{{ $app->id }}</td>
                                <td>
                                    @if($app->user)
                                        {{ trim(($app->user->f_name ?? '').' '.($app->user->l_name ?? '')) ?: '—' }}<br>
                                        <small class="text-muted">{{ $app->user->email ?? '—' }}</small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td><span class="badge badge-soft-secondary">{{ $app->status ?? '—' }}</span></td>
                                <td>{{ $app->is_accept ?? '—' }}</td>
                                <td>{{ $app->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ \App\CPU\translate('No data found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $applications->links() }}</div>
            @endif
        </div>
    </div>
@endsection
