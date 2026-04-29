@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Wallet'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

@endpush

@section('content')
<div class="content container-fluid">

    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{ asset('/public/assets/back-end/img/wallet.png') }}" alt="">
            {{ \App\CPU\translate('Wallet Transactions') }}
        </h2>
        <button class="btn btn--primary" data-toggle="modal" data-target="#add-money-modal">
            <i class="tio-add"></i> {{ \App\CPU\translate('Add Money') }}
        </button>
    </div>
    <!-- End Page Title -->

    <!-- Wallet Balance -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ \App\CPU\translate('Current Wallet Balance') }}</h5>
            <h4 class="mb-0 text-success">
                {{ number_format($wallet_amount, 2) }}
            </h4>
        </div>
    </div>

    <!-- Transaction Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ \App\CPU\translate('Transaction History') }}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-borderless table-thead-bordered table-nowrap card-table">
                <thead class="thead-light text-capitalize">
                    <tr>
                        <th>{{ \App\CPU\translate('SL') }}</th>
                        <th>{{ \App\CPU\translate('Transaction ID') }}</th>
                        <th>{{ \App\CPU\translate('Credit') }}</th>
                        <th>{{ \App\CPU\translate('Debit') }}</th>
                        <th>{{ \App\CPU\translate('Balance After') }}</th>
                        <th>{{ \App\CPU\translate('Type') }}</th>
                        <th>{{ \App\CPU\translate('Reference') }}</th>
                        <th>{{ \App\CPU\translate('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if($transactions->count() > 0)
                    @foreach($transactions as $key => $transaction)
                    <tr>
                        <td>{{ $transactions->firstItem() + $key }}</td>
                        <td>{{ $transaction->transaction_id ?? '-' }}</td>
                        <td class="text-success">
                            {{ $transaction->credit > 0 ? '+' . number_format($transaction->credit, 2) : '-' }}
                        </td>
                        <td class="text-danger">
                            {{ $transaction->debit > 0 ? '-' . number_format($transaction->debit, 2) : '-' }}
                        </td>
                        <td>
                            {{ number_format($transaction->balance, 2) }}
                        </td>

                        <td><label class="badge badge-soft-info">{{ $transaction->transaction_type }}</label></td>
                        <td>{{ $transaction->reference ?? '-' }}</td>
                        <td>{{ date('F j, Y, g:i a', strtotime($transaction->created_at)) }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="8" class="text-center">
                            <img class="mb-3 w-160" src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}" alt="No Data">
                            <p class="mb-0">{{ \App\CPU\translate('No transaction history found') }}</p>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-end">
                {!! $transactions->links() !!}
            </div>
        </div>
    </div>

</div>

<!-- Add Money Modal -->
<div class="modal fade" id="add-money-modal" tabindex="-1" role="dialog" aria-labelledby="addMoneyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="add-money-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ \App\CPU\translate('Add Money to Wallet') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ \App\CPU\translate('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="wallet_amount">{{ \App\CPU\translate('Amount') }}</label>
                        <input type="number" class="form-control" name="wallet_amount" id="wallet_amount" required min="0.01" step="0.01">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ \App\CPU\translate('Cancel') }}</button>
                    <button type="button" class="btn btn--primary" id="pay-wallet-btn">{{ \App\CPU\translate('Pay with Razorpay') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('script')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    $(document).ready(function () {
        $('#pay-wallet-btn').on('click', function () {
            let amount = parseFloat($('#wallet_amount').val());

            if (isNaN(amount) || amount <= 0) {
                Swal.fire('Invalid Amount', 'Please enter a valid amount greater than 0.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to add ₹${amount.toFixed(2)} to your wallet.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let options = {
                        "key": "rzp_test_1DP5mmOlF5G5ag", 
                        "amount": amount * 100,
                        "currency": "INR",
                        "name": "Prime Basket",
                        "description": "Wallet Top-up",
                        "image": '{{ $logo_url ?? asset("public/assets/back-end/img/logo.png") }}',
                        "handler": function (response) {
                            $.ajax({
                                url: '{{ route('seller.wallet.add-money') }}',
                                type: 'POST',
                                data: {
                                    wallet_amount: amount,
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (res) {
                                    Swal.fire('Success', res.message || 'Money added to your wallet successfully.', 'success')
                                        .then(() => {
                                            location.reload();
                                        });
                                },
                                error: function (xhr) {
                                    Swal.fire('Error', 'There was an issue processing your payment. Please contact support.', 'error');
                                }
                            });
                        },
                        "prefill": {
                            "name": "{{ auth('seller')->user()->name }}",
                            "email": "{{ auth('seller')->user()->email }}"
                        },
                        "notes": {
                            "purpose": "Wallet top-up"
                        },
                        "theme": {
                            "color": "#6777ef"
                        }
                    };

                    let rzp = new Razorpay(options);
                    rzp.open();
                }
            });
        });
    });
</script>
@endpush