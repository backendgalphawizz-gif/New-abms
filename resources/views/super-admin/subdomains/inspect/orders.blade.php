@extends('super-admin.subdomains.inspect-layout')

@section('inspect_content')
    <div class="card">
        <div class="card-header">
            <h2 class="h4 mb-0">{{ \App\CPU\translate('Orders') }}</h2>
        </div>
        <div class="card-body p-0">
            @if($orders === null)
                <p class="text-muted p-4 mb-0">{{ \App\CPU\translate('No data found') }}</p>
            @else
                <div class="table-responsive">
                    <table class="table table-thead-bordered table-nowrap table-align-middle card-table mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>{{ \App\CPU\translate('Customer') }}</th>
                            <th>{{ \App\CPU\translate('Order Amount') }}</th>
                            <th>{{ \App\CPU\translate('Order status') }}</th>
                            <th>{{ \App\CPU\translate('Payment status') }}</th>
                            <th>{{ \App\CPU\translate('Created at') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>
                                    @if($order->customer)
                                        {{ trim(($order->customer->f_name ?? '').' '.($order->customer->l_name ?? '')) ?: '—' }}<br>
                                        <small class="text-muted">{{ $order->customer->email ?? '—' }}</small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $order->order_amount ?? '—' }}</td>
                                <td><span class="badge badge-soft-info">{{ $order->order_status ?? '—' }}</span></td>
                                <td>{{ $order->payment_status ?? '—' }}</td>
                                <td>{{ $order->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">{{ \App\CPU\translate('No data found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $orders->links() }}</div>
            @endif
        </div>
    </div>
@endsection
