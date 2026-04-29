@extends('layouts.back-end.app-customer')
@section('title', 'Application Detail')

@push('css_or_js')
<style>
    .ad-top { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; margin-bottom:1rem; }
    .ad-top h1 { margin:.2rem 0 .25rem; font-size:2rem; font-weight:800; color:#111b35; }
    .ad-sub { color:#6f7f95; max-width:740px; }
    .ad-badge { border-radius:999px; padding:.42rem .9rem; font-size:.85rem; font-weight:700; }
    .ad-badge.ok { background:#e8faf1; color:#0e8a54; }
    .ad-badge.wait { background:#fff8df; color:#a57d00; }
    .ad-grid { display:grid; grid-template-columns:2fr 1fr; gap:1rem; }
    .ad-card { background:#fff; border:1px solid #e7edf5; border-radius:16px; padding:1.25rem 1.3rem; }
    .ad-card h3 { font-size:1.35rem; font-weight:700; color:#17233b; margin:0 0 1rem; }
    .ad-mini-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    .ad-k { color:#8a98ad; font-size:.72rem; text-transform:uppercase; letter-spacing:.08em; font-weight:700; margin-bottom:.2rem; }
    .ad-v { color:#17233b; font-size:1.5rem; font-weight:700; }
    .pay-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:.75rem; }
    .pay-box { background:#f7fafe; border:1px solid #e8eef8; border-radius:12px; padding:.75rem; }
    .pay-box .amt { font-size:1.6rem; font-weight:800; color:#17233b; margin:.15rem 0; }
    .paid { color:#0fa060; font-size:.72rem; font-weight:700; }
    .pay-btn { border:none; background:#1f63de; color:#fff; border-radius:999px; padding:.4rem .95rem; font-weight:600; font-size:.82rem; }
    .finding-card { background:linear-gradient(130deg,#1f63de,#2b7df8); color:#fff; border:none; }
    .finding-card .n { font-size:3rem; font-weight:900; line-height:1; margin:.5rem 0 .1rem; }
    .full-btn { width:100%; border:none; border-radius:999px; background:#fff; color:#1f63de; font-weight:700; padding:.65rem .9rem; }
    .full-app-btn { margin:1.35rem auto 0; display:block; border:none; border-radius:999px; background:#1f63de; color:#fff; font-weight:700; padding:.8rem 2.2rem; box-shadow:0 10px 18px rgba(31,99,222,.26); }
    @media (max-width: 1199px) { .ad-grid{grid-template-columns:1fr;} .pay-grid{grid-template-columns:repeat(2,minmax(0,1fr));} .ad-v{font-size:1.2rem;} }
    @media (max-width: 767px) { .ad-top{flex-direction:column;} .ad-top h1{font-size:1.5rem;} .ad-mini-grid,.pay-grid{grid-template-columns:1fr;} }
</style>
@endpush

@section('content')
@php
    $schemeTitle = data_get($application, 'scheme.title') ?? data_get($application, 'scheme.name') ?? 'Quality Management System';
    $standard = data_get($application, 'scheme.name', 'ISO 9001:2015');
    $submissionDate = optional(data_get($application, 'created_at'))->format('F d, Y') ?? '—';
    $refNo = data_get($application, 'reference_number') ?: str_pad((string) data_get($application, 'id'), 7, '0', STR_PAD_LEFT);
    $auditFindings = max(0, (int) data_get($application, 'finding_count', ((int)data_get($application, 'is_accept') === 1 ? 0 : 3)));
    $p = data_get($application, 'payments');
    $fees = [
        ['label' => 'Application Fee', 'amount' => data_get($p, 'application_fee', 250), 'paid' => (int) data_get($p, 'application_fee_status', 0) === 1],
        ['label' => 'Document Fee', 'amount' => data_get($p, 'document_fee', 120), 'paid' => (int) data_get($p, 'document_fee_status', 0) === 1],
        ['label' => 'Assessment Fee', 'amount' => data_get($p, 'assessment_fee', 800), 'paid' => (int) data_get($p, 'assessment_fee_status', 0) === 1],
        ['label' => 'Technical Review', 'amount' => data_get($p, 'technical_assessment_fee', 450), 'paid' => (int) data_get($p, 'technical_assessment_fee_status', 0) === 1],
    ];
@endphp
<a href="{{ route('portal.applications', ['tab' => 'certifications']) }}" class="text-decoration-none" style="font-weight:600;">&larr; Back to List</a>
<div class="ad-top">
    <div>
        <h1>Application Detail</h1>
        <div class="ad-sub">Manage and track the certification journey for accreditation cycles.</div>
    </div>
    <div class="d-flex align-items-center" style="gap:.75rem;">
        <div class="text-muted">Ref. No. #{{ $refNo }}</div>
        <span class="ad-badge {{ (int)data_get($application, 'is_accept') === 1 ? 'ok' : 'wait' }}">{{ (int)data_get($application, 'is_accept') === 1 ? 'Approved' : 'Pending' }}</span>
    </div>
</div>

<div class="ad-grid">
    <div>
        <div class="ad-card mb-3">
            <h3>Application Overview</h3>
            <div class="ad-mini-grid">
                <div><div class="ad-k">Type</div><div class="ad-v">{{ (int)data_get($application, 'is_renewal') === 1 ? 'Re-certification' : 'Initial Accreditation' }}</div></div>
                <div><div class="ad-k">Submission Date</div><div class="ad-v">{{ $submissionDate }}</div></div>
            </div>
        </div>
        <div class="ad-card mb-3">
            <h3>Accreditation Scheme</h3>
            <div class="ad-mini-grid">
                <div><div class="ad-k">Title</div><div class="ad-v">{{ $schemeTitle }}</div></div>
                <div><div class="ad-k">Standard</div><div class="ad-v">{{ $standard }}</div></div>
                <div><div class="ad-k">Assessment Progress</div><div class="ad-v">{{ data_get($application, 'status', 'Witness Assessment') }}</div></div>
            </div>
        </div>
        <div class="ad-card mb-3">
            <h3>Payment Status</h3>
            <div class="pay-grid">
                @foreach($fees as $fee)
                    <div class="pay-box">
                        <div class="ad-k">{{ $fee['label'] }}</div>
                        <div class="amt">${{ number_format((float)$fee['amount'], 2) }}</div>
                        @if($fee['paid'])
                            <span class="paid">PAID</span>
                        @else
                            <button type="button" class="pay-btn">Pay Now</button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div>
        <div class="ad-card mb-3">
            <h3>Assessor Assignment</h3>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Lead Assessor</span><b>{{ data_get($application, 'auditor.name', 'Unassigned') }}</b></div>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Allotment Date</span><b>{{ optional(data_get($application, 'updated_at'))->format('M d, Y') ?? '—' }}</b></div>
            <div class="d-flex justify-content-between mb-3"><span class="text-muted">Mode</span><b>Onsite</b></div>
            <button class="btn btn-outline-primary btn-block rounded-pill">View Detail</button>
        </div>
        <div class="ad-card finding-card mb-3">
            <h3 class="text-white">Audit Findings</h3>
            <div class="n">{{ $auditFindings }}</div>
            <div style="letter-spacing:.08em;text-transform:uppercase;font-weight:700;">Critical Points Found</div>
            <button class="full-btn mt-3">View Full Findings</button>
        </div>
        <div class="ad-card">
            <div class="ad-k mb-2">Remarks &amp; Notes</div>
            <div style="color:#4d5d76;line-height:1.6;">{{ data_get($application, 'remark') ?: 'Initial documentation was missing Annexure B-4. Applicant resubmitted during the technical review phase. Currently verified and awaiting final board signature.' }}</div>
        </div>
    </div>
</div>

<button type="button" class="full-app-btn">See Full Application &#8599;</button>
@endsection

