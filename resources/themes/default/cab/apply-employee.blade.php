@extends('layouts.back-end.app-customer')
@section('title', 'Apply for Certification - Step 2')

@push('css_or_js')
<style>
    .apply-wrap { max-width: 1100px; margin: 0 auto; }
    .apply-card { background:#fff; border:1px solid #e8edf5; border-radius:16px; padding:1.25rem 1.5rem; }
    .progress-card { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; margin-bottom:1.15rem; }
    .progress-title { font-size:.72rem; font-weight:700; letter-spacing:.08em; color:#1e56d9; text-transform:uppercase; }
    .progress-step { background:#edf4ff; border:1px solid #d9e6ff; color:#1e56d9; border-radius:999px; padding:.35rem .8rem; font-weight:600; }
    .progress-bar-shell { margin-top:.75rem; background:#ebeff5; border-radius:999px; height:6px; overflow:hidden; }
    .progress-bar-fill { height:100%; width:50%; background:#1e56d9; }
    .apply-head h2 { font-size:2.2rem; font-weight:800; color:#111b35; margin:0 0 .45rem; }
    .apply-head p { color:#71839e; margin:0 0 1rem; max-width:760px; }
    .table-shell { border:1px solid #e8edf5; border-radius:14px; overflow:hidden; background:#fff; }
    .table-head { padding:1.1rem 1.35rem; border-bottom:1px solid #e8edf5; }
    .table-head h4 { margin:0 0 .35rem; font-size:1.6rem; font-weight:700; color:#17233b; }
    .table-sub { color:#6e7f96; }
    table.emp { width:100%; border-collapse:collapse; }
    table.emp th { background:#f2f6fc; color:#6e7f96; font-size:.8rem; text-transform:uppercase; letter-spacing:.06em; text-align:left; padding:.85rem 1.25rem; }
    table.emp td { padding:.95rem 1.25rem; border-top:1px solid #edf2f8; }
    .type-wrap { display:flex; align-items:center; gap:1rem; flex-wrap:wrap; }
    .chip { display:flex; align-items:center; gap:.5rem; color:#5f7088; }
    .chip input { width:74px; border:1px solid #d9e1ee; border-radius:999px; padding:.35rem .5rem; text-align:center; background:#f9fbff; }
    .apply-actions { display:flex; justify-content:space-between; margin-top:1.25rem; gap:1rem; }
    .prev-btn { border:1px solid #d6deeb; border-radius:999px; padding:.82rem 2.1rem; color:#51627b; font-weight:700; background:#fff; }
    .next-btn { border:none; border-radius:999px; padding:.82rem 2.1rem; color:#fff; font-weight:700; background:linear-gradient(90deg,#1e56d9 0%, #0f83d8 100%); box-shadow:0 10px 18px rgba(30,86,217,.25); }
    @media (max-width: 767px) {
        .apply-head h2 { font-size:1.65rem; }
        table.emp th, table.emp td { padding:.75rem .7rem; }
        .type-wrap { gap:.5rem; }
    }
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
        <div class="progress-step">Step 2 / 4</div>
    </div>

    <div class="apply-head">
        <h2>Employee Details</h2>
        <p>Please provide the necessary organizational details for the certification assessment process. All fields marked are required.</p>
    </div>

    <form method="post" action="{{ route('portal.apply.employee.store') }}">
        @csrf
        <div class="table-shell">
            <div class="table-head">
                <h4>Departmental Distribution</h4>
                <div class="table-sub">Check all relevant types of employment active in each sector.</div>
            </div>
            <table class="emp">
                <thead>
                    <tr>
                        <th style="width:30%;">Department</th>
                        <th>Employment Types</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($rows as $i => $row)
                    <tr>
                        <td>
                            <b>{{ $row['department'] }}</b>
                            <input type="hidden" name="employees[{{ $i }}][department]" value="{{ $row['department'] }}">
                        </td>
                        <td>
                            <div class="type-wrap">
                                <label class="chip">Full Time
                                    <input type="number" min="0" name="employees[{{ $i }}][full_time]" value="{{ old('employees.'.$i.'.full_time', $row['full_time']) }}">
                                </label>
                                <label class="chip">Part Time
                                    <input type="number" min="0" name="employees[{{ $i }}][part_time]" value="{{ old('employees.'.$i.'.part_time', $row['part_time']) }}">
                                </label>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="apply-actions">
            <a href="{{ route('portal.apply') }}" class="text-decoration-none"><button class="prev-btn" type="button">&larr; Previous Step</button></a>
            <button class="next-btn" type="submit">Next Step &rarr;</button>
        </div>
    </form>
</div>
@endsection

