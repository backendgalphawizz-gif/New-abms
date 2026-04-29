@extends('layouts.back-end.app-customer')
@section('title', \App\CPU\translate('Applications'))

@push('css_or_js')
<style>
    .app-tabs { display:flex; gap:1.75rem; border-bottom:1px solid #e7edf5; margin-bottom:1.25rem; }
    .app-tabs a { padding:.7rem 0; text-decoration:none; color:#8a98ac; font-weight:600; border-bottom:2px solid transparent; }
    .app-tabs a.active { color:#1e56d9; border-bottom-color:#1e56d9; }
    .app-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:1.2rem; }
    .app-card {
        display:block; text-decoration:none; color:inherit; background:#fff; border:1px solid #e7edf5; border-radius:16px; padding:1rem 1rem .9rem;
        box-shadow:0 4px 14px rgba(16,46,96,.03); transition:all .2s ease;
    }
    .app-card:hover { transform:translateY(-2px); box-shadow:0 10px 24px rgba(16,46,96,.08); text-decoration:none; color:inherit; }
    .app-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:.9rem; }
    .badge-pending { font-size:.68rem; letter-spacing:.08em; text-transform:uppercase; font-weight:700; color:#b38b00; background:#fff8df; border-radius:999px; padding:.3rem .65rem; }
    .app-kicker { font-size:.68rem; color:#7f8fa7; letter-spacing:.12em; text-transform:uppercase; font-weight:700; margin-bottom:.35rem; }
    .app-title { font-size:2rem; line-height:1.05; font-weight:800; color:#0f1933; margin-bottom:.45rem; }
    .app-sub { color:#5f6f86; font-size:1.02rem; min-height:3rem; }
    .app-sep { border-top:1px solid #ecf1f8; margin:.9rem 0; }
    .app-meta { display:grid; grid-template-columns:1fr 1fr; gap:.5rem; }
    .app-meta .k { font-size:.62rem; color:#95a2b4; text-transform:uppercase; letter-spacing:.12em; font-weight:700; }
    .app-meta .v { font-size:1.3rem; font-weight:700; color:#192741; }
    .app-bottom { margin-top:.9rem; display:flex; justify-content:space-between; align-items:center; }
    .finding { border-radius:999px; padding:.26rem .65rem; font-size:.74rem; font-weight:600; }
    .finding.warn { color:#d93131; background:#fff2f2; }
    .finding.ok { color:#65748a; background:#f2f5fa; }
    .arrow { width:30px; height:30px; border-radius:50%; display:grid; place-items:center; background:#f4f7fc; color:#8a97ab; font-size:1rem; }
    @media (max-width: 1199px) { .app-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media (max-width: 767px) { .app-grid { grid-template-columns:1fr; } .app-title{font-size:1.6rem;} .app-meta .v{font-size:1.05rem;} }
</style>
@endpush

@section('content')
    <div class="app-tabs" role="tablist">
        <a href="{{ route('portal.applications', ['tab' => 'certifications']) }}" class="{{ $tab === 'certifications' ? 'active' : '' }}">Certifications</a>
        <a href="{{ route('portal.applications', ['tab' => 're-certifications']) }}" class="{{ $tab === 're-certifications' ? 'active' : '' }}">Re-certifications</a>
        <a href="{{ route('portal.applications', ['tab' => 'surveillance']) }}" class="{{ $tab === 'surveillance' ? 'active' : '' }}">Surveillance</a>
    </div>

    @if($applications->isEmpty())
        <p class="text-center text-muted py-4">{{ \App\CPU\translate('no_data_found') }}</p>
    @else
        <div class="app-grid">
            @foreach($applications as $app)
                @php
                    $std = optional($app->scheme)->title ?? optional($app->scheme)->name ?? 'ISO 9001:2015';
                    $framework = optional($app->scheme)->name ?? 'Quality Management Systems Framework';
                    $submission = optional($app->created_at)->format('M d, Y') ?? '—';
                    $scopeItems = max(1, (int) (data_get($app, 'application_scope_count') ?? 5));
                    $findings = max(0, (int) (data_get($app, 'finding_count') ?? ((int)$app->is_accept === 0 ? 2 : 0)));
                @endphp
                <a href="{{ route('portal.applications.show', $app) }}" class="app-card">
                    <div class="app-top">
                        <span style="width:42px;height:42px;border-radius:12px;background:#eef4ff;color:#1f63de;display:grid;place-items:center;font-size:1.2rem;">✹</span>
                        <span class="badge-pending">{{ ((int)$app->is_accept === 1) ? 'Approved' : 'Pending' }}</span>
                    </div>
                    <div class="app-kicker">Quality Standard</div>
                    <div class="app-title">{{ $std }}</div>
                    <div class="app-sub">{{ \Illuminate\Support\Str::limit($framework, 56) }}</div>
                    <div class="app-sep"></div>
                    <div class="app-meta">
                        <div>
                            <div class="k">Submission</div>
                            <div class="v">{{ $submission }}</div>
                        </div>
                        <div>
                            <div class="k">Process Scope</div>
                            <div class="v">{{ $scopeItems }} Scope Items</div>
                        </div>
                    </div>
                    <div class="app-bottom">
                        <span class="finding {{ $findings > 0 ? 'warn' : 'ok' }}">{{ $findings > 0 ? $findings.' Audit Findings' : '0 Findings' }}</span>
                        <span class="arrow">&#8594;</span>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="d-flex justify-content-center">{{ $applications->links() }}</div>
    @endif
@endsection
