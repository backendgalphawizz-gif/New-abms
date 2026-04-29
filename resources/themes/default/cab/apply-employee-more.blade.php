@extends('layouts.back-end.app-customer')
@section('title', 'Apply for Certification - Step 3')

@push('css_or_js')
<style>
    .apply-wrap { max-width: 1100px; margin: 0 auto; }
    .apply-card { background:#fff; border:1px solid #e8edf5; border-radius:16px; padding:1.25rem 1.5rem; }
    .progress-card { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; margin-bottom:1.15rem; }
    .progress-title { font-size:.72rem; font-weight:700; letter-spacing:.08em; color:#1e56d9; text-transform:uppercase; }
    .progress-step { background:#edf4ff; border:1px solid #d9e6ff; color:#1e56d9; border-radius:999px; padding:.35rem .8rem; font-weight:600; }
    .progress-bar-shell { margin-top:.75rem; background:#ebeff5; border-radius:999px; height:6px; overflow:hidden; }
    .progress-bar-fill { height:100%; width:75%; background:#1e56d9; }
    .apply-head h2 { font-size:2.2rem; font-weight:800; color:#111b35; margin:0 0 .45rem; }
    .apply-head p { color:#71839e; margin:0 0 1rem; max-width:760px; }
    .apply-block { background:#fff; border:1px solid #e8edf5; border-radius:14px; padding:1.3rem 1.35rem; margin-bottom:1rem; }
    .apply-field label { display:block; font-size:.75rem; font-weight:700; color:#60738f; text-transform:uppercase; margin-bottom:.45rem; }
    .apply-input, .apply-textarea { width:100%; border:1px solid #d9e1ee; border-radius:10px; padding:.72rem .85rem; color:#2b3b53; background:#f9fbff; }
    .apply-textarea { min-height:86px; resize:vertical; }
    .apply-actions { display:flex; justify-content:space-between; margin-top:1.25rem; gap:1rem; }
    .prev-btn { border:1px solid #d6deeb; border-radius:999px; padding:.82rem 2.1rem; color:#51627b; font-weight:700; background:#fff; }
    .next-btn { border:none; border-radius:999px; padding:.82rem 2.1rem; color:#fff; font-weight:700; background:linear-gradient(90deg,#1e56d9 0%, #0f83d8 100%); box-shadow:0 10px 18px rgba(30,86,217,.25); }
    @media (max-width: 767px) { .apply-head h2 { font-size:1.65rem; } }
</style>
@endpush

@section('content')
<div class="apply-wrap">
    <div class="apply-card progress-card">
        <div style="flex:1;">
            <div class="progress-title">Registration Progress</div>
            <h4 class="mb-0" style="font-weight:700;color:#111b35;">Information About Company</h4>
            <div class="progress-bar-shell"><div class="progress-bar-fill"></div></div>
        </div>
        <div class="progress-step">Step 3 / 4</div>
    </div>

    <div class="apply-head">
        <h2>Employee Details</h2>
        <p>Please provide the necessary organizational details for the certification assessment process. All fields marked are required.</p>
    </div>

    <form method="post" action="{{ route('portal.apply.employee.more.store') }}">
        @csrf
        <div class="apply-block">
            <div class="apply-field">
                <label>Approx. Number of Sub - Contractors Used On Average (If Applicable)</label>
                <input class="apply-input" type="number" min="0" name="sub_contractors_avg" value="{{ old('sub_contractors_avg', $sub_contractors_avg) }}" placeholder="e.g. 15">
            </div>
        </div>

        <div class="apply-block">
            <div class="apply-field">
                <label>Describe The Type Of Work Subcontracted (If Applicable)</label>
                <input class="apply-input" type="text" name="subcontract_work_type" value="{{ old('subcontract_work_type', $subcontract_work_type) }}" placeholder="Enter">
            </div>
        </div>

        <div class="apply-block">
            <div class="apply-field">
                <label>Legal and Statutory Requirements</label>
                <input class="apply-input" type="text" name="legal_statutory_requirements" value="{{ old('legal_statutory_requirements', $legal_statutory_requirements) }}" placeholder="Enter">
            </div>
        </div>

        <div class="apply-block">
            <div class="apply-field">
                <label>Certified In Other Systems</label>
                <input class="apply-input" type="text" name="certified_other_systems" value="{{ old('certified_other_systems', $certified_other_systems) }}" placeholder="Enter">
            </div>
        </div>

        <div class="apply-actions">
            <a href="{{ route('portal.apply.employee') }}" class="text-decoration-none"><button class="prev-btn" type="button">&larr; Previous Step</button></a>
            <button class="next-btn" type="submit">Next Step &rarr;</button>
        </div>
    </form>
</div>
@endsection

