@extends('layouts.back-end.app-customer')
@section('title', 'Apply for Certification')

@push('css_or_js')
<style>
    .apply-wrap { max-width: 1100px; margin: 0 auto; }
    .apply-card { background:#fff; border:1px solid #e8edf5; border-radius:16px; padding:1.25rem 1.5rem; }
    .progress-card { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; }
    .progress-title { font-size:.72rem; font-weight:700; letter-spacing:.08em; color:#1e56d9; text-transform:uppercase; }
    .progress-step { background:#edf4ff; border:1px solid #d9e6ff; color:#1e56d9; border-radius:999px; padding:.35rem .8rem; font-weight:600; }
    .progress-bar-shell { margin-top:.75rem; background:#ebeff5; border-radius:999px; height:6px; overflow:hidden; }
    .progress-bar-fill { height:100%; width:25%; background:#1e56d9; }
    .apply-head h2 { font-size:2.2rem; font-weight:800; color:#111b35; margin:0 0 .45rem; }
    .apply-head p { color:#71839e; margin:0 0 1rem; max-width:760px; }
    .grid-2 { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:1rem; }
    .grid-3-1 { display:grid; grid-template-columns:2fr 1fr; gap:1rem; }
    .apply-block { background:#fff; border:1px solid #e8edf5; border-radius:14px; padding:1.3rem 1.35rem; margin-bottom:1rem; }
    .apply-field label { display:block; font-size:.75rem; font-weight:700; color:#60738f; text-transform:uppercase; margin-bottom:.45rem; }
    .apply-input, .apply-textarea, .apply-select {
        width:100%; border:1px solid #d9e1ee; border-radius:10px; padding:.72rem .85rem; color:#2b3b53; background:#f9fbff;
    }
    .apply-textarea { min-height:86px; resize:vertical; }
    .apply-actions { display:flex; justify-content:flex-end; margin-top:1.25rem; }
    .next-btn {
        border:none; border-radius:999px; padding:.82rem 2.1rem; color:#fff; font-weight:700;
        background:linear-gradient(90deg,#1e56d9 0%, #0f83d8 100%);
        box-shadow:0 10px 18px rgba(30,86,217,.25);
    }
    .help-card { position:sticky; top:84px; background:#eef4ff; border:1px solid #dbe7ff; border-radius:16px; padding:1rem; margin-top:1rem; max-width:220px; }
    .help-card p { margin:0 0 .45rem; color:#667996; font-size:.83rem; }
    .help-card .btn { border-radius:999px; font-weight:600; width:100%; }
    @media (max-width: 991px) {
        .grid-2, .grid-3-1 { grid-template-columns:1fr; }
        .apply-head h2 { font-size:1.75rem; }
        .help-card { position:static; max-width:none; }
    }
</style>
@endpush

@section('content')
@php
    $cc = trim(($profile->city ?? '').(($profile->city && $profile->country) ? ', ' : '').($profile->country ?? ''));
    $cpd = is_array($profile->contact_person_details ?? null) ? $profile->contact_person_details : [];
    $od = is_array($profile->ownership_details ?? null) ? $profile->ownership_details : [];
@endphp
<div class="apply-wrap">
    <div class="apply-card progress-card mb-3">
        <div style="flex:1;">
            <div class="progress-title">Registration Progress</div>
            <h4 class="mb-0" style="font-weight:700;color:#111b35;">Information About Company</h4>
            <div class="progress-bar-shell"><div class="progress-bar-fill"></div></div>
        </div>
        <div class="progress-step">Step 1 / 4</div>
    </div>

    <div class="apply-head">
        <h2>Company Details</h2>
        <p>Please provide the necessary organizational details for the certification assessment process. All fields marked are required.</p>
    </div>

    <form method="post" action="{{ route('portal.apply.store') }}">
        @csrf
        <div class="apply-block">
            <div class="grid-2">
                <div class="apply-field">
                    <label>Company Name</label>
                    <input class="apply-input" type="text" name="name" value="{{ old('name', $profile->name) }}" placeholder="Enter Company Name" required>
                </div>
                <div class="apply-field">
                    <label>Position</label>
                    <input class="apply-input" type="text" name="position" value="{{ old('position', data_get($cpd, 'position')) }}" placeholder="Enter Position">
                </div>
            </div>
        </div>

        <div class="grid-3-1">
            <div class="apply-block">
                <div class="apply-field mb-3">
                    <label>Address</label>
                    <input class="apply-input" type="text" name="address" value="{{ old('address', $profile->address) }}" placeholder="Enter Company Address" required>
                </div>
                <div class="grid-2">
                    <div class="apply-field">
                        <label>City &amp; Country</label>
                        <input class="apply-input" type="text" name="city_country" value="{{ old('city_country', $cc) }}" placeholder="City, Country">
                    </div>
                    <div class="apply-field">
                        <label>PO Box / Code</label>
                        <input class="apply-input" type="text" name="pincode" value="{{ old('pincode', $profile->pincode) }}" placeholder="Enter Code">
                    </div>
                </div>
            </div>
            <div class="apply-block">
                <div class="apply-field mb-3">
                    <label>Telephone No.</label>
                    <input class="apply-input" type="text" name="telephone" value="{{ old('telephone', $profile->phone) }}" placeholder="Enter Tel. No.">
                </div>
                <div class="apply-field mb-3">
                    <label>Contact No.</label>
                    <input class="apply-input" type="text" name="contact_no" value="{{ old('contact_no', data_get($cpd, 'contact_no')) }}" placeholder="Enter Contact No.">
                </div>
                <div class="apply-field">
                    <label>Fax No.</label>
                    <input class="apply-input" type="text" name="fax" value="{{ old('fax', $profile->fax) }}" placeholder="Enter Fax No.">
                </div>
            </div>
        </div>

        <div class="grid-2">
            <div class="apply-block">
                <div class="apply-field">
                    <label>Website</label>
                    <input class="apply-input" type="text" name="website" value="{{ old('website', $profile->website) }}" placeholder="Enter Website">
                </div>
            </div>
            <div class="apply-block">
                <div class="apply-field">
                    <label>Email Address</label>
                    <input class="apply-input" type="email" name="email" value="{{ old('email', $profile->email) }}" placeholder="Enter Email Address">
                </div>
            </div>
        </div>

        <div class="apply-block">
            <div class="grid-2">
                <div class="apply-field">
                    <label>Standard(s) To Be Assessed</label>
                    <textarea class="apply-textarea" name="standards_to_be_assessed" placeholder="Write">{{ old('standards_to_be_assessed', data_get($od, 'standards_to_be_assessed', !empty($isoCertifications) ? implode(', ', $isoCertifications) : '')) }}</textarea>
                </div>
                <div class="apply-field">
                    <label>Any Exclusion Of The Standard Requirements</label>
                    <textarea class="apply-textarea" name="exclusion_requirements" placeholder="Write">{{ old('exclusion_requirements', data_get($od, 'exclusion_requirements')) }}</textarea>
                </div>
                <div class="apply-field">
                    <label>Accreditation Required</label>
                    <textarea class="apply-textarea" name="accreditation_required" placeholder="Write">{{ old('accreditation_required', data_get($od, 'accreditation_required')) }}</textarea>
                </div>
                <div class="apply-field">
                    <label>Other Information</label>
                    <textarea class="apply-textarea" name="other_information" placeholder="Write">{{ old('other_information', data_get($od, 'other_information')) }}</textarea>
                </div>
            </div>
        </div>

        <div class="apply-block">
            <div class="apply-field">
                <label>Please Describe What Activities Your Organisation Carries Out</label>
                <textarea class="apply-textarea" name="activities" style="min-height:110px;" placeholder="Write">{{ old('activities', data_get($od, 'activities')) }}</textarea>
            </div>
        </div>

        <div class="apply-block">
            <div class="apply-field">
                <label>Please List Any Additional Site(s) To Be Included In The Scope Of Registration</label>
                <textarea class="apply-textarea" name="additional_sites" style="min-height:110px;" placeholder="Write">{{ old('additional_sites', data_get($od, 'additional_sites')) }}</textarea>
            </div>
        </div>

        <div class="apply-actions">
            <button class="next-btn" type="submit">Next Step &rarr;</button>
        </div>
    </form>
</div>
@endsection

