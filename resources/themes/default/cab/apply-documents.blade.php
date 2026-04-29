@extends('layouts.back-end.app-customer')
@section('title', 'Apply for Certification - Document Upload')

@push('css_or_js')
<style>
    .apply-wrap { max-width: 1100px; margin: 0 auto; }
    .apply-head h2 { font-size:2.2rem; font-weight:800; color:#111b35; margin:0 0 .45rem; }
    .apply-head p { color:#71839e; margin:0 0 1rem; max-width:760px; }
    .doc-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:1rem; }
    .doc-card { background:#fff; border:1px solid #e8edf5; border-radius:16px; padding:1.2rem 1.35rem; }
    .doc-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:.7rem; }
    .doc-icon { width:36px; height:36px; border-radius:50%; background:#eef4ff; color:#1f63de; display:grid; place-items:center; }
    .doc-tag { font-size:.62rem; letter-spacing:.08em; text-transform:uppercase; color:#7e91ab; font-weight:700; background:#f4f8ff; border-radius:999px; padding:.2rem .5rem; }
    .doc-card h4 { margin:0 0 .4rem; font-size:1.9rem; font-weight:700; color:#1b2943; }
    .doc-card p { color:#63758f; margin:0 0 .85rem; }
    .upload-box { border:1px dashed #d6e0ee; border-radius:16px; padding:1rem; text-align:center; background:#fbfdff; }
    .upload-box input[type=file] { display:none; }
    .browse-btn { display:inline-block; margin-top:.45rem; background:linear-gradient(90deg,#1e56d9,#0f83d8); color:#fff; border-radius:999px; padding:.5rem 1rem; font-weight:700; cursor:pointer; }
    .file-meta { margin-top:.45rem; font-size:.8rem; color:#4f6480; }
    .actions { margin-top:1rem; display:flex; justify-content:space-between; gap:1rem; }
    .prev-btn { border:1px solid #d6deeb; border-radius:999px; padding:.82rem 2.1rem; color:#51627b; font-weight:700; background:#fff; }
    .next-btn { border:none; border-radius:999px; padding:.82rem 2.1rem; color:#fff; font-weight:700; background:linear-gradient(90deg,#1e56d9 0%, #0f83d8 100%); box-shadow:0 10px 18px rgba(30,86,217,.25); }
    @media (max-width: 767px) { .doc-grid { grid-template-columns:1fr; } .apply-head h2{font-size:1.65rem;} }
</style>
@endpush

@section('content')
@php
    $cards = [
        ['field' => 'quality_manual', 'title' => 'Quality Manual', 'desc' => 'Management System Manual outlining core organizational quality policies and scope.', 'icon' => '&#128214;'],
        ['field' => 'procedures_policies', 'title' => 'Procedures & Policies', 'desc' => 'Internal governance documents, HR policies, and security protocols for assessment.', 'icon' => '&#128272;'],
        ['field' => 'process_sops', 'title' => 'Process Documents / SOPs', 'desc' => 'Standard Operating Procedures for core business processes and operations.', 'icon' => '&#128736;'],
        ['field' => 'business_legal_docs', 'title' => 'Business Legal Docs', 'desc' => 'License copy, articles of incorporation, or relevant legal business registration.', 'icon' => '&#9878;'],
    ];
@endphp
<div class="apply-wrap">
    <div class="apply-head">
        <h2>Document Upload</h2>
        <p>Please provide the necessary organizational details for the certification assessment process. All fields marked are required.</p>
    </div>

    <form method="post" action="{{ route('portal.apply.documents.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="doc-grid">
            @foreach($cards as $card)
                @php($existing = data_get($documents, $card['field']))
                <div class="doc-card">
                    <div class="doc-top">
                        <span class="doc-icon">{!! $card['icon'] !!}</span>
                        <span class="doc-tag">Required</span>
                    </div>
                    <h4>{{ $card['title'] }}</h4>
                    <p>{{ $card['desc'] }}</p>
                    <div class="upload-box">
                        <div style="font-size:1.3rem;color:#c2cedf;">&#128228;</div>
                        <div style="color:#8ea0b8;">Drag and drop your file here</div>
                        <label class="browse-btn" for="{{ $card['field'] }}">Browse Files</label>
                        <input type="file" id="{{ $card['field'] }}" name="{{ $card['field'] }}">
                        @if($existing)
                            <div class="file-meta">Uploaded: {{ data_get($existing, 'name') }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="actions">
            <a href="{{ route('portal.apply.audit') }}" class="text-decoration-none"><button class="prev-btn" type="button">&larr; Previous Step</button></a>
            <button class="next-btn" type="submit">Next Step &rarr;</button>
        </div>
    </form>
</div>
@endsection

