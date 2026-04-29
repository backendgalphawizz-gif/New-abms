@extends('theme-views.layouts.app')

@section('title', translate('My_Wallet').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">

                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="row g-0 g-md-3 h-100">
                        <div class="col-md-12 mt-md-3">
                            <div class="card h-100 profilecard">
                                <div class="card-body">
                                    <h5 class="mb-3">{{ translate('Transaction_History') }}</h5>
                                    <!-- is Transaction History Empty -->
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($wallet_transactio_list as $key=>$item)
                                            <div class="trasactionCard">
                                                <div class="">
                                                    <h6 class="mb-2">Order ID {{ $item['order_id'] }}</h6>
                                                    <h6 class="mb-2">Order Amount {{ \App\CPU\Helpers::currency_converter($item['order_amount']) }}</h6>
                                                    <p><small class="text-muted">Payment Method: {{ucwords(str_replace('_', ' ',$item['payment_method']))}}</small></p>
                                                    <p class="mb-0"><small>Transaction Id: {{$item['transaction_id']}}</small></p>
                                                </div>
                                                <div class="text-end">
                                                    <div class="text-muted mb-1">{{date('d M, Y H:i A',strtotime($item['created_at']))}} </div>
                                                    <p class="text-danger fs-12">{{translate('Order Placed')}}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="card-footer bg-transparent border-0 p-0 mt-3">
                                        {{$wallet_transactio_list->links()}}
                                    </div>
                                    <!-- End Transaction History Empty -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <!-- End Main Content -->
@endsection

@push('script')
   
@endpush
