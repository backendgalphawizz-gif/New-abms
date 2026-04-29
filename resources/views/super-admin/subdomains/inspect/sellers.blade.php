@extends('super-admin.subdomains.inspect-layout')

@section('inspect_content')
    <div class="card">
        <div class="card-header">
            <h2 class="h4 mb-0">{{ \App\CPU\translate('Sellers') }}</h2>
        </div>
        <div class="card-body p-0">
            @if($sellers === null)
                <p class="text-muted p-4 mb-0">{{ \App\CPU\translate('No data found') }}</p>
            @else
                <div class="table-responsive">
                    <table class="table table-thead-bordered table-nowrap table-align-middle card-table mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>{{ \App\CPU\translate('Name') }}</th>
                            <th>{{ \App\CPU\translate('email') }}</th>
                            <th>{{ \App\CPU\translate('Status') }}</th>
                            <th>{{ \App\CPU\translate('Shop') }}</th>
                            <th>{{ \App\CPU\translate('Created at') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sellers as $seller)
                            <tr>
                                <td>{{ $seller->id }}</td>
                                <td>{{ trim(($seller->f_name ?? '').' '.($seller->l_name ?? '')) ?: '—' }}</td>
                                <td>{{ $seller->email ?? '—' }}</td>
                                <td><span class="badge badge-soft-secondary">{{ $seller->status ?? '—' }}</span></td>
                                <td>{{ optional($seller->shop)->name ?? '—' }}</td>
                                <td>{{ $seller->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">{{ \App\CPU\translate('No data found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $sellers->links() }}</div>
            @endif
        </div>
    </div>
@endsection
