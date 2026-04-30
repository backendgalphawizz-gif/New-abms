@extends('layouts.super-admin.app')

@section('title', \App\CPU\translate('Super Admin') . ' | Create entity')

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .iso-grid { display:grid; grid-template-columns:repeat(1,minmax(0,1fr)); gap:.75rem; }
        @media (min-width: 768px) { .iso-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (min-width: 1200px) { .iso-grid { grid-template-columns:repeat(3,minmax(0,1fr)); } }
        .iso-card {
            border:1px solid #d7dee8;
            border-radius:.5rem;
            padding:.75rem .9rem;
            background:#f8fbff;
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:.75rem;
        }
        .iso-card .code { font-weight:600; color:#0b7edb; }
        .iso-card .name { color:#4f5b67; margin-top:.2rem; font-size:.9rem; }
        .iso-card input[type=checkbox] { margin-top:.15rem; width:16px; height:16px; }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-3 mb-3 border-0">
            <h1 class="page-header-title"><b>Create entity</b></h1>
            <p class="text-muted mb-0">{{ \App\CPU\translate('Creates a tenant database and subdomain') }}.</p>
        </div>

        <div class="card w-100">
            <div class="card-body">
                @if ($errors->has('provision'))
                    <div class="alert alert-danger mb-4">{{ $errors->first('provision') }}</div>
                @endif

                <form method="post" action="{{ route('super-admin.entities.store') }}">
                    @csrf
                    <h5 class="mb-3">{{ \App\CPU\translate('Entity') }}</h5>
                    <div class="form-group">
                        <label for="name">{{ \App\CPU\translate('Name') }} / business</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="subdomain">Subdomain</label>
                        <div class="input-group">
                            <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}"
                                   class="form-control @error('subdomain') is-invalid @enderror" required
                                   pattern="[a-z0-9]([a-z0-9\-]{0,61}[a-z0-9])?"
                                   title="Lowercase letters, numbers, hyphens">
                            <div class="input-group-append">
                                <span class="input-group-text">.{{ $domainSuffix }}</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Example: <strong>acme.{{ $domainSuffix }}</strong>. After creation, sign in on that host at <strong>/admin/auth/login</strong> (same port as this site, e.g. <code>http://acme.{{ $domainSuffix }}:8000/admin/auth/login</code>).</small>
                        @error('subdomain')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-2 text-primary"><b>ISO Standards Accreditation</b></h5>
                    <p class="text-muted mb-3">{{ \App\CPU\translate('Select all ISO certifications applicable to this company entity') }}.</p>
                    <div class="iso-grid mb-2">
                        @forelse($isoStandards as $code => $label)
                            <label class="iso-card mb-0">
                                <div>
                                    <div class="code">{{ $code }}</div>
                                    <div class="name">{{ $label }}</div>
                                </div>
                                <input type="checkbox" name="iso_certifications[]" value="{{ $code }}"
                                       {{ in_array($code, (array) old('iso_certifications', []), true) ? 'checked' : '' }}>
                            </label>
                        @empty
                            <div class="alert alert-warning mb-0">
                                No active ISO standards found. Please add them from Super Admin &gt; ISO Standards.
                            </div>
                        @endforelse
                    </div>
                    @error('iso_certifications')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                    @error('iso_certifications.*')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                    <div id="iso-checklist-sections" class="mt-3"></div>

                    <hr class="my-4">
                    <h5 class="mb-3">{{ \App\CPU\translate('Tenant admin') }} <span class="text-muted font-weight-normal">({{ \App\CPU\translate('first login for this entity') }})</span></h5>
                    <div class="form-group">
                        <label for="admin_name">{{ \App\CPU\translate('Admin name') }}</label>
                        <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" class="form-control @error('admin_name') is-invalid @enderror" required autocomplete="name">
                        @error('admin_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="admin_email">{{ \App\CPU\translate('Admin email') }}</label>
                        <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" class="form-control @error('admin_email') is-invalid @enderror" required autocomplete="email">
                        @error('admin_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="admin_phone">{{ \App\CPU\translate('Admin phone') }} <span class="text-muted">({{ \App\CPU\translate('optional') }})</span></label>
                        <input type="text" name="admin_phone" id="admin_phone" value="{{ old('admin_phone') }}" class="form-control @error('admin_phone') is-invalid @enderror" autocomplete="tel">
                        @error('admin_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="admin_password">{{ \App\CPU\translate('Admin password') }}</label>
                        <input type="password" name="admin_password" id="admin_password" class="form-control @error('admin_password') is-invalid @enderror" required minlength="8" autocomplete="new-password">
                        @error('admin_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="admin_password_confirmation">{{ \App\CPU\translate('Confirm password') }}</label>
                        <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" class="form-control" required minlength="8" autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn--primary">{{ \App\CPU\translate('Submit') }}</button>
                    <a href="{{ route('super-admin.entities.index') }}" class="btn btn-secondary">{{ \App\CPU\translate('Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function () {
            const templatesByCode = @json($isoChecklistTemplates ?? []);
            const checkboxes = Array.from(document.querySelectorAll('input[name="iso_certifications[]"]'));
            const sectionsRoot = document.getElementById('iso-checklist-sections');
            if (!sectionsRoot || !checkboxes.length) return;

            const esc = (value) => String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');

            const render = () => {
                const selected = checkboxes.filter((box) => box.checked).map((box) => box.value);
                const blocks = selected.map((code) => {
                    const template = templatesByCode[code];
                    if (!template) return '';

                    const list = (template.items || []).map((item) => '<li>' + esc(item) + '</li>').join('');
                    return [
                        '<div class="card mt-3">',
                        '<div class="card-body py-3">',
                        '<h6 class="mb-1"><b>' + esc(code) + ' Checklist</b></h6>',
                        '<p class="text-muted mb-2">Source: ' + esc(template.source || 'N/A') + '</p>',
                        '<ul class="mb-0 pl-3">' + list + '</ul>',
                        '</div>',
                        '</div>',
                    ].join('');
                }).filter(Boolean);

                sectionsRoot.innerHTML = blocks.join('');
            };

            checkboxes.forEach((box) => box.addEventListener('change', render));
            render();
        })();
    </script>
@endpush
