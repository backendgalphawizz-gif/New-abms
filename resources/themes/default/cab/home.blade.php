@extends('layouts.back-end.app-customer')
@section('title', \App\CPU\translate('Home'))

@push('css_or_js')
<style>
    .cab-hero { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; margin-bottom:1rem; }
    .cab-hero h1 { margin:0; font-size:3.1rem; font-weight:800; color:#111b35; }
    .cab-hero p { margin:.45rem 0 0; color:#6f7f95; max-width:620px; }
    .cab-status { font-size:.72rem; text-transform:uppercase; letter-spacing:.08em; color:#60738f; font-weight:700; display:flex; align-items:center; gap:.45rem; margin-bottom:.45rem; }
    .cab-status:before { content:''; width:8px; height:8px; border-radius:50%; background:#15b371; display:inline-block; }
    .cab-apply-btn {
        border:none; border-radius:999px; padding:.72rem 1.3rem; color:#fff; font-weight:600;
        background: linear-gradient(90deg, #1e56d9 0%, #0f83d8 100%);
        box-shadow: 0 10px 18px rgba(30,86,217,.2);
    }
    .cab-title-row { display:flex; justify-content:space-between; align-items:center; margin:1rem 0; }
    .cab-title-row h2 { margin:0; font-size:2.2rem; font-weight:800; color:#111b35; display:flex; align-items:center; gap:.65rem; }
    .cab-link { color:#1e56d9; font-weight:700; text-decoration:none; }
    .cert-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:1rem; }
    .cert-card { background:#fff; border:1px solid #e7edf5; border-radius:18px; padding:1rem; box-shadow:0 4px 14px rgba(16,46,96,.03); display:flex; flex-direction:column; }
    .cert-icon { width:42px; height:42px; border-radius:12px; background:#eef4ff; color:#1f63de; display:grid; place-items:center; font-size:1.2rem; }
    .cert-card h3 { margin:1rem 0 .4rem; font-size:1.4rem; line-height:1.2; font-weight:700; color:#111b35; }
    .cert-card p { margin:0; color:#60738f; min-height:3rem; font-size:.86rem; }
    .cert-card a { margin-top:1rem; color:#44566f; font-weight:700; text-decoration:none; font-size:.95rem; }
    .cert-card--green { border-bottom: 3px solid #12ad78; }
    .cert-card--orange { border-bottom: 3px solid #f0701f; }
    .cert-card--blue { border-bottom: 3px solid #2f6ee6; }
    .cert-card--purple { border-bottom: 3px solid #9a4ef3; }
    .stats-grid { margin-top:1rem; display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:1rem; }
    .stat-card { background:#fff; border:1px solid #e7edf5; border-radius:18px; padding:1rem 1.1rem; display:flex; align-items:center; gap:.85rem; }
    .stat-icon { width:42px; height:42px; border-radius:12px; background:#eef4ff; color:#1f63de; display:grid; place-items:center; font-size:1rem; }
    .stat-k { font-size:.7rem; color:#8a98ac; text-transform:uppercase; letter-spacing:.08em; font-weight:700; }
    .stat-v { margin-top:.1rem; font-size:2.2rem; font-weight:800; color:#111b35; }
    @media (max-width: 1199px) { .cert-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media (max-width: 767px) {
        .cab-hero { flex-direction:column; align-items:flex-start; }
        .cab-hero h1 { font-size:2rem; }
        .cab-title-row h2 { font-size:1.45rem; }
        .cert-grid, .stats-grid { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')
    <div class="cab-hero">
        <div>
            <div class="cab-status">System Status: Operational</div>
            <h1>Welcome back, {{ \Illuminate\Support\Str::words($customer->f_name ?? $customer->name ?? 'Commander', 1, '') }}</h1>
            <p>Centralized management for your global compliance standards and atmospheric certification protocols.</p>
        </div>
        <a href="{{ route('portal.apply') }}" class="text-decoration-none">
            <button type="button" class="cab-apply-btn">+ Apply for Certification</button>
        </a>
    </div>
    <div class="cab-title-row">
        <h2><span style="width:36px;height:36px;border-radius:12px;background:#eef4ff;color:#1f63de;display:grid;place-items:center;font-size:1.1rem;">✹</span> Certifications</h2>
        <a href="{{ route('portal.applications', ['tab' => 'certifications']) }}" class="cab-link">View All &rarr;</a>
    </div>

    <div class="cert-grid">
        @forelse($latestApplications as $app)
            @php
                $std = optional($app->scheme)->title ?? optional($app->scheme)->name ?? 'ISO 9001:2015';
                $desc = optional($app->scheme)->name ?? 'Quality management systems framework';
            @endphp
            <article class="cert-card cert-card--{{ ['green','orange','blue','purple'][$loop->index % 4] }}">
                <span class="cert-icon">✹</span>
                <h3>{{ $std }}</h3>
                <p>{{ \Illuminate\Support\Str::limit($desc, 74) }}</p>
                <a href="{{ route('portal.applications.show', $app) }}">Read More</a>
            </article>
        @empty
            @foreach(array_slice($isoCertifications, 0, 4) as $iso)
                <article class="cert-card cert-card--{{ ['green','orange','blue','purple'][$loop->index % 4] }}">
                    <span class="cert-icon">✹</span>
                    <h3>{{ $iso }}</h3>
                    <p>Allocated certification for this tenant.</p>
                    <a href="{{ route('portal.apply') }}">Read More</a>
                </article>
            @endforeach
        @endforelse
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-icon">&#128196;</span>
            <div><div class="stat-k">Pending</div><div class="stat-v">{{ $pendingApplications }}</div></div>
        </div>
        <div class="stat-card">
            <span class="stat-icon" style="background:#ebfbf2;color:#0ea85f;">&#10003;</span>
            <div><div class="stat-k">Active Certificates</div><div class="stat-v">{{ $approvedApplications }}</div></div>
        </div>
        <div class="stat-card">
            <span class="stat-icon" style="background:#fff4e8;color:#f48a2a;">&#128197;</span>
            <div><div class="stat-k">Total Applications</div><div class="stat-v">{{ $totalApplications }}</div></div>
        </div>
    </div>

@endsection
