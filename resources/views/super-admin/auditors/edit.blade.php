@extends('layouts.super-admin.app')

@section('title', \App\CPU\translate('Super Admin') . ' | Edit Auditor')

@push('css_or_js')
    <style>
        .password-wrap {
            position: relative;
        }
        .password-wrap .form-control {
            padding-right: 2.25rem;
        }
        .password-toggle-btn {
            position: absolute;
            right: .5rem;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #9aa4b2;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        @php
            $rawImage = str_replace('\\', '/', (string) ($auditor->image ?? ''));
            if ($rawImage === '' || $rawImage === 'def.png') {
                $auditorImageUrl = asset('assets/back-end/img/400x400/img2.jpg');
            } elseif (\Illuminate\Support\Str::startsWith($rawImage, ['http://', 'https://'])) {
                $auditorImageUrl = $rawImage;
            } elseif (\Illuminate\Support\Str::startsWith($rawImage, ['storage/', 'assets/'])) {
                $auditorImageUrl = asset($rawImage);
            } else {
                $auditorImageUrl = asset('storage/app/public/auditor/' . ltrim($rawImage, '/'));
            }
        @endphp
        <div class="page-header pb-3 mb-3 border-0">
            <h1 class="page-header-title"><b>Edit Assessor</b></h1>
            <p class="text-muted mb-0">Update auditor profile information.</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('super-admin.auditors.update', $auditor->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h4 class="mb-3">General Information</h4>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Image (optional)</label>
                            <input type="file" name="image" id="auditor_image_input" class="form-control-file">
                            <div class="mt-2">
                                <img
                                    id="auditor_image_preview"
                                    src="{{ $auditorImageUrl }}"
                                    onerror="this.onerror=null;this.src='{{ asset('storage/app/public/admin/') }}/{{ ltrim((string)($auditor->image ?? ''), '/') }}';setTimeout(()=>{if(!this.complete||this.naturalWidth===0){this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}';}},100);"
                                    alt="{{ $auditor->name }}"
                                    style="width:72px;height:72px;border-radius:10px;object-fit:cover;border:1px solid #e9eef6;"
                                >
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $auditor->name) }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone', $auditor->phone) }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $auditor->email) }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Applied Designation</label>
                                    <input type="text" name="apply_designation" value="{{ old('apply_designation', data_get($assessor, 'apply_designation')) }}" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Set New Password (Optional)</label>
                                    <div class="password-wrap">
                                        <input type="password" name="password" id="auditor_password" class="form-control" minlength="8">
                                        <button type="button" class="password-toggle-btn" onclick="togglePassword('auditor_password', this)" aria-label="Show password">
                                            <i class="tio-visible-outlined"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Confirm New Password</label>
                                    <div class="password-wrap">
                                        <input type="password" name="password_confirmation" id="auditor_password_confirmation" class="form-control" minlength="8">
                                        <button type="button" class="password-toggle-btn" onclick="togglePassword('auditor_password_confirmation', this)" aria-label="Show confirm password">
                                            <i class="tio-visible-outlined"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">Personal Information</h4>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Highest Qualification</label>
                            <input type="text" name="highest_qualification" value="{{ old('highest_qualification', data_get($assessor, 'highest_qualification')) }}" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Technical Area</label>
                            <input type="text" name="technical_area" value="{{ old('technical_area', data_get($assessor, 'technical_area')) }}" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Experience (Years)</label>
                            <input type="number" min="0" name="experience" value="{{ old('experience', (int) data_get($assessor, 'experience', 0)) }}" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Home Address</label>
                            <textarea name="home_address" rows="3" class="form-control">{{ old('home_address', data_get($assessor, 'home_address')) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('super-admin.auditors.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn--primary">Update Auditor</button>
                    </div>
                    <div class="mt-2">
                        <label class="mb-0" style="font-size:.85rem;">
                            <input type="checkbox" id="show_passwords_toggle"> Show passwords
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function togglePassword(targetId, button) {
            var input = document.getElementById(targetId);
            var icon = button.querySelector('i');
            if (!input) {
                return;
            }

            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('tio-visible-outlined');
                    icon.classList.add('tio-hidden-outlined');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('tio-hidden-outlined');
                    icon.classList.add('tio-visible-outlined');
                }
            }
        }

        (function () {
            var input = document.getElementById('auditor_image_input');
            var preview = document.getElementById('auditor_image_preview');
            if (input && preview) {
                input.addEventListener('change', function (e) {
                    var file = e.target.files && e.target.files[0];
                    if (!file) {
                        return;
                    }
                    var objectUrl = URL.createObjectURL(file);
                    preview.src = objectUrl;
                });
            }

            var toggle = document.getElementById('show_passwords_toggle');
            var password = document.getElementById('auditor_password');
            var confirmPassword = document.getElementById('auditor_password_confirmation');
            if (toggle && password && confirmPassword) {
                toggle.addEventListener('change', function () {
                    var type = this.checked ? 'text' : 'password';
                    password.type = type;
                    confirmPassword.type = type;
                });
            }
        })();
    </script>
@endpush

