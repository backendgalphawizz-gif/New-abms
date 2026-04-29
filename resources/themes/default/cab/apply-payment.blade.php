@extends('layouts.back-end.app-customer')
@section('title', 'Apply for Certification - Payment')

@push('css_or_js')
<style>
    .apply-wrap { width: 100%; max-width: none; margin: 0; padding: 0 .4rem; }
    .apply-head h2 { font-size:2.2rem; font-weight:800; color:#111b35; margin:0 0 .45rem; }
    .apply-head p { color:#71839e; margin:0 0 1rem; max-width:760px; }
    .pay-card { width: 100%; background:#fff; border:1px solid #e8edf5; border-radius:20px; padding:1.5rem; }
    .pay-total { font-size:3rem; font-weight:900; color:#111b35; margin:.4rem 0 1rem; }
    .pay-btn { width:100%; border:none; border-radius:999px; padding:.85rem 1rem; color:#fff; font-weight:800; background:linear-gradient(90deg,#1e56d9,#0f83d8); box-shadow:0 10px 18px rgba(30,86,217,.25); }
    .modal-backdropx {
        position: fixed;
        inset: 0;
        background: rgba(10, 22, 45, .48);
        backdrop-filter: blur(2px);
        display: grid;
        place-items: center;
        z-index: 99999;
        padding: 1rem;
    }
    .success-modal {
        width: min(92vw, 420px);
        background: #fff;
        border-radius: 28px;
        padding: 1.75rem 1.7rem;
        text-align: center;
        box-shadow: 0 30px 70px rgba(13, 26, 50, .35);
    }
    .success-modal .icon {
        width: 78px;
        height: 78px;
        border-radius: 50%;
        margin: 0 auto 1rem;
        display: grid;
        place-items: center;
        color: #fff;
        background: linear-gradient(135deg, #1f63de, #19c0e6);
        font-size: 2rem;
        box-shadow: 0 10px 22px rgba(31, 99, 222, .35);
    }
    .success-modal h3 { font-size: 2rem; margin-bottom: .45rem; color: #15233c; }
    .success-modal p { line-height: 1.45; }
    .dash-btn {
        border: none;
        border-radius: 999px;
        padding: .78rem 1.2rem;
        min-width: 235px;
        color: #fff;
        font-weight: 700;
        background: linear-gradient(90deg, #1e56d9, #12b7df);
        box-shadow: 0 10px 18px rgba(30, 86, 217, .25);
    }
    @media (max-width: 767px) {
        .apply-wrap { padding: 0; }
        .apply-head h2 { font-size: 1.7rem; }
        .pay-card { padding: 1rem; border-radius: 14px; }
        .pay-total { font-size: 2.2rem; }
        .pay-btn { padding: .75rem .9rem; }
    }
</style>
@endpush

@section('content')
<div class="apply-wrap">
    <div class="apply-head">
        <h2>Payment</h2>
        <p>Please provide the necessary organizational details for the certification assessment process. All fields marked are required.</p>
    </div>

    <div class="pay-card">
        <div class="d-flex justify-content-between align-items-center text-muted">
            <span>Payment Total</span>
            <span style="font-weight:700;">{{ $currency }}</span>
        </div>
        <div class="pay-total">${{ number_format((float) $paymentTotal, 2) }}</div>
        <form method="post" action="{{ route('portal.apply.payment.store') }}">
            @csrf
            <button class="pay-btn" type="submit">Pay Now &rarr;</button>
        </form>
    </div>
</div>

@if($paymentSuccess)
    <div class="modal-backdropx">
        <div class="success-modal" role="dialog" aria-modal="true" aria-label="Payment Successful">
            <div class="icon">&#10003;</div>
            <h3 style="font-weight:800;">Payment Successful!</h3>
            <p class="text-muted mb-4">Your transaction has been processed successfully. Your certification application is now being reviewed.</p>
            <a href="{{ route('portal.home') }}"><button class="dash-btn" type="button">Go to Dashboard</button></a>
        </div>
    </div>
@endif
@endsection

