@extends('layouts.back-end.app-customer')
@section('title', 'Apply for Certification - Step 4')

@push('css_or_js')
<style>
    .apply-wrap { max-width: 1100px; margin: 0 auto; }
    .apply-head h2 { font-size:2.2rem; font-weight:800; color:#111b35; margin:0 0 .45rem; }
    .apply-head p { color:#71839e; margin:0 0 1rem; max-width:760px; }
    .cardx { background:#fff; border:1px solid #e8edf5; border-radius:16px; padding:1.2rem 1.35rem; margin-bottom:1rem; }
    .mode-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:1rem; }
    .mode-option { border:2px solid #d7e2f1; border-radius:999px; padding:.9rem 1rem; display:flex; align-items:center; justify-content:space-between; }
    .mode-option.active { border-color:#1e56d9; }
    .mode-option .left { display:flex; align-items:center; gap:.7rem; }
    .mode-option .icon { width:34px; height:34px; border-radius:50%; background:#eef4ff; color:#1e56d9; display:grid; place-items:center; }
    .scope-item { border:1px solid #d8e2f1; border-radius:16px; margin-bottom:.8rem; overflow:hidden; }
    .scope-title { padding:.9rem 1rem; background:#fbfdff; font-weight:700; color:#1b2a44; display:flex; align-items:center; gap:.55rem; }
    .scope-body { padding:1rem 1rem .8rem; border-top:1px solid #e9eff8; }
    .q { margin-bottom:.95rem; }
    .q label.head { display:block; font-size:.78rem; text-transform:uppercase; color:#667c98; font-weight:700; margin-bottom:.45rem; }
    .pill-row { display:flex; gap:.65rem; flex-wrap:wrap; }
    .pill-row input { display:none; }
    .pill-row label {
        margin:0; cursor:pointer; border:1px solid #ccd9ed; border-radius:999px; padding:.5rem 1.5rem; font-weight:700; color:#5b6f8b; background:#fff;
    }
    .pill-row input:checked + label { border-color:#1e56d9; color:#1e56d9; background:#eef4ff; }
    .note-box { border:1px dashed #c8d7ee; border-radius:999px; padding:.6rem .85rem; display:flex; justify-content:space-between; align-items:center; gap:.6rem; }
    .note-box input { border:none; outline:none; width:100%; }
    .actions { margin-top:.9rem; display:flex; justify-content:space-between; gap:1rem; }
    .prev-btn { border:1px solid #d6deeb; border-radius:999px; padding:.82rem 2.1rem; color:#51627b; font-weight:700; background:#fff; }
    .next-btn { border:none; border-radius:999px; padding:.82rem 2.1rem; color:#fff; font-weight:700; background:linear-gradient(90deg,#1e56d9 0%, #0f83d8 100%); box-shadow:0 10px 18px rgba(30,86,217,.25); }
    @media (max-width: 767px) { .mode-grid { grid-template-columns:1fr; } .apply-head h2{font-size:1.7rem;} }
</style>
@endpush

@section('content')
@php
    $mode = old('audit_mode', data_get($auditData, 'audit_mode', 'onsite'));
    $scopes = old('scopes', data_get($auditData, 'scopes', []));
    if (empty($scopes)) {
        $scopes = collect($isoCertifications)->values()->take(10)->map(function ($iso) {
            return ['standard' => $iso, 'sites' => 'single', 'design_included' => 'yes', 'outsourced_process' => 'no', 'sona_attached' => 'yes', 'legal_obligation_note' => 'Yes'];
        })->toArray();
    }
@endphp
<div class="apply-wrap">
    <div class="apply-head">
        <h2>Audit &amp; Compliance Details</h2>
        <p>Please provide the necessary organizational details for the certification assessment process. All fields marked are required.</p>
    </div>

    <form method="post" action="{{ route('portal.apply.audit.store') }}">
        @csrf
        <div class="cardx">
            <h4 class="mb-3" style="font-weight:700;">Audit Mode Selection</h4>
            <div class="mode-grid">
                <label class="mode-option {{ $mode === 'onsite' ? 'active' : '' }}">
                    <div class="left">
                        <span class="icon">&#128205;</span>
                        <div><b>Physical/On-site</b><div class="text-muted">Auditor visits your physical facilities</div></div>
                    </div>
                    <input type="radio" name="audit_mode" value="onsite" {{ $mode === 'onsite' ? 'checked' : '' }}>
                </label>
                <label class="mode-option {{ $mode === 'remote' ? 'active' : '' }}">
                    <div class="left">
                        <span class="icon">&#128249;</span>
                        <div><b>Virtual/Remote</b><div class="text-muted">Conducted via secure digital conferencing</div></div>
                    </div>
                    <input type="radio" name="audit_mode" value="remote" {{ $mode === 'remote' ? 'checked' : '' }}>
                </label>
            </div>
        </div>

        <div class="cardx">
            <h4 class="mb-3" style="font-weight:700;">Compliance Scope</h4>
            @foreach($scopes as $i => $scope)
                <div class="scope-item">
                    <div class="scope-title">
                        <span style="width:24px;height:24px;border-radius:50%;background:#eef4ff;color:#1f63de;display:grid;place-items:center;font-size:.75rem;">✹</span>
                        <span>{{ $i+1 }}. {{ data_get($scope, 'standard', 'ISO Standard') }}</span>
                    </div>
                    <div class="scope-body">
                        <input type="hidden" name="scopes[{{ $i }}][standard]" value="{{ data_get($scope, 'standard') }}">
                        <div class="q">
                            <label class="head">Number of sites to be audited?</label>
                            <div class="pill-row">
                                <input id="sites_single_{{ $i }}" type="radio" name="scopes[{{ $i }}][sites]" value="single" {{ data_get($scope, 'sites', 'single') === 'single' ? 'checked' : '' }}><label for="sites_single_{{ $i }}">Single</label>
                                <input id="sites_multi_{{ $i }}" type="radio" name="scopes[{{ $i }}][sites]" value="multiple" {{ data_get($scope, 'sites') === 'multiple' ? 'checked' : '' }}><label for="sites_multi_{{ $i }}">Multiple</label>
                            </div>
                        </div>
                        <div class="q">
                            <label class="head">Is the clause "Design & Development" included in scope?</label>
                            <div class="pill-row">
                                <input id="design_yes_{{ $i }}" type="radio" name="scopes[{{ $i }}][design_included]" value="yes" {{ data_get($scope, 'design_included', 'yes') === 'yes' ? 'checked' : '' }}><label for="design_yes_{{ $i }}">Yes</label>
                                <input id="design_no_{{ $i }}" type="radio" name="scopes[{{ $i }}][design_included]" value="no" {{ data_get($scope, 'design_included') === 'no' ? 'checked' : '' }}><label for="design_no_{{ $i }}">No</label>
                            </div>
                        </div>
                        <div class="q">
                            <label class="head">Is any process outsourced affecting conformity?</label>
                            <div class="pill-row">
                                <input id="out_yes_{{ $i }}" type="radio" name="scopes[{{ $i }}][outsourced_process]" value="yes" {{ data_get($scope, 'outsourced_process') === 'yes' ? 'checked' : '' }}><label for="out_yes_{{ $i }}">Yes</label>
                                <input id="out_no_{{ $i }}" type="radio" name="scopes[{{ $i }}][outsourced_process]" value="no" {{ data_get($scope, 'outsourced_process', 'no') === 'no' ? 'checked' : '' }}><label for="out_no_{{ $i }}">No</label>
                            </div>
                        </div>
                        <div class="q">
                            <label class="head">Attach statement of non-applicability (SONA)</label>
                            <div class="pill-row">
                                <input id="sona_yes_{{ $i }}" type="radio" name="scopes[{{ $i }}][sona_attached]" value="yes" {{ data_get($scope, 'sona_attached', 'yes') === 'yes' ? 'checked' : '' }}><label for="sona_yes_{{ $i }}">Yes</label>
                                <input id="sona_no_{{ $i }}" type="radio" name="scopes[{{ $i }}][sona_attached]" value="no" {{ data_get($scope, 'sona_attached') === 'no' ? 'checked' : '' }}><label for="sona_no_{{ $i }}">No</label>
                            </div>
                        </div>
                        <div class="note-box">
                            <span>Legal Obligations if any:</span>
                            <input type="text" name="scopes[{{ $i }}][legal_obligation_note]" value="{{ data_get($scope, 'legal_obligation_note') }}">
                            <a href="javascript:;" style="font-weight:700;text-decoration:none;">Edit Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="actions">
            <a href="{{ route('portal.apply.employee.more') }}" class="text-decoration-none"><button class="prev-btn" type="button">&larr; Previous Step</button></a>
            <button class="next-btn" type="submit">Next Step &rarr;</button>
        </div>
    </form>
</div>
@endsection

