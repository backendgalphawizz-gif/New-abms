@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Assign Bulk Orders'))

@push('css_or_js')
<link rel="stylesheet" href="//cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
@endpush



@section('content')
<div class="content container-fluid">
    <div class="card mb-2">
        <div class="card-body">
            <form action="{{ route('seller.delivery-man.assign-bulk-order-store') }}" method="POST" id="form-data">
                @csrf
                <div class="row gx-2 gy-3 align-items-center text-left">
                    <div class="col-sm-6 col-md-3">
                         <label><strong>Select Deliveryman</strong></label>
                        <select class="js-select2-custom form-control" name="driver_id" required>
                            <option value="">{{ \App\CPU\translate('select_driver') }}</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">
                                    {{ $driver->f_name }} {{ $driver->l_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-6">
                         <label><strong>Select Orders</strong></label>
                       <select class="js-select2-custom form-control" name="order_ids[]" multiple="multiple" required>
                        @foreach($unassigned_orders as $order)
                            <option value="{{ $order->id }}">{{ $order->id }}</option>
                        @endforeach
                    </select>
                    </div>

                    <div class="col-sm-6 col-md-3 submit-btn">
                        <button type="submit" class="btn btn--primary px-4 px-md-5">
                            {{ \App\CPU\translate('submit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="table-responsive">
            <table class="table table-hover table-borderless table-thead-bordered table-nowrap card-table">
                <thead class="thead-light thead-50 text-capitalize">
                <tr>
                    <th>{{ \App\CPU\translate('SL') }}</th>
                    <th>{{ \App\CPU\translate('Delivery Man') }}</th>
                    <th>{{ \App\CPU\translate('Order ID') }}</th>
                </tr>
                </thead>
                <tbody>
                      @php $serial = 1; @endphp
                 @forelse($orders as $key => $order)
                    @if($order->delivery_man && $order->delivery_man->seller_id == auth('seller')->id())
                         <tr>
                             <td>{{ $serial++ }}</td> 
                            <td>{{ $order->delivery_man->f_name }} {{ $order->delivery_man->l_name }}</td>
                            <td>{{ $order->id }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="3" class="text-center">{{ \App\CPU\translate('No_orders_found') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{-- <div class="pagination-container d-flex justify-content-end p-2">
            {!! $orders->links() !!}
        </div> --}}
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        $('.js-select2-custom').select2({
            placeholder: "{{ \App\CPU\translate('Select options') }}"
        });
    });
</script>
@endpush

