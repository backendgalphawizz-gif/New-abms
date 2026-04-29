@extends('layouts.back-end.app-customer')
@section('title', 'Apply for Certification - Agreement')

@push('css_or_js')
<style>
    .apply-wrap { max-width: 1100px; margin: 0 auto; }
    .apply-head h2 { font-size:2.2rem; font-weight:800; color:#111b35; margin:0 0 .45rem; }
    .apply-head p { color:#71839e; margin:0 0 1rem; max-width:760px; }
    .cardx { background:#fff; border:1px solid #e8edf5; border-radius:16px; padding:1.2rem 1.35rem; margin-bottom:1rem; }
    .agree-row { display:flex; justify-content:space-between; align-items:center; gap:1rem; }
    .sign-upload { border:1px dashed #d6e0ee; border-radius:18px; min-height:220px; display:grid; place-items:center; text-align:center; background:#fbfdff; }
    .sign-upload input[type=file] { display:none; }
    .browse-btn { display:inline-block; margin-top:.4rem; background:linear-gradient(90deg,#1e56d9,#0f83d8); color:#fff; border-radius:999px; padding:.5rem 1rem; font-weight:700; cursor:pointer; }
    .actions { margin-top:1rem; display:flex; justify-content:space-between; gap:1rem; }
    .prev-btn { border:1px solid #d6deeb; border-radius:999px; padding:.82rem 2.1rem; color:#51627b; font-weight:700; background:#fff; }
    .next-btn { border:none; border-radius:999px; padding:.82rem 2.1rem; color:#fff; font-weight:700; background:linear-gradient(90deg,#1e56d9 0%, #0f83d8 100%); box-shadow:0 10px 18px rgba(30,86,217,.25); }
</style>
@endpush

@section('content')
<div class="apply-wrap">
    <div class="apply-head">
        <h2>Agreement</h2>
        <p>Please provide the necessary organizational details for the certification assessment process. All fields marked are required.</p>
    </div>

    <div class="cardx">
        <div class="agree-row">
            <h3 class="mb-0" style="font-weight:700;">Certification Agreement</h3>
            <a href="javascript:;" class="text-primary font-weight-bold">View Full Agreement (PDF)</a>
        </div>
    </div>

    <form method="post" action="{{ route('portal.apply.agreement.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="cardx">
            <h3 style="font-weight:700;" class="mb-3">Digital Signature</h3>
            <div class="sign-upload">
                <div>
                    <div style="font-size:1.5rem;color:#1f63de;">&#128228;</div>
                    <div style="font-weight:700;color:#334761;">Upload Digital Signature</div>
                    <div class="text-muted">PNG, SVG or JPEG (Max 2MB)</div>
                    <label class="browse-btn mt-2" for="digital_signature">Browse Signature</label>
                    <input type="file" id="digital_signature" name="digital_signature">
                    @if($signature)
                        <div class="mt-2 text-success">Existing signature uploaded</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="actions">
            <a href="{{ route('portal.apply.documents') }}" class="text-decoration-none"><button class="prev-btn" type="button">&larr; Previous Step</button></a>
            <button class="next-btn" type="submit">Next Step &rarr;</button>
        </div>
    </form>
</div>
@endsection

