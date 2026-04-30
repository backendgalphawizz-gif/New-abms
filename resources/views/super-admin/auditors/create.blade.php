@extends('layouts.super-admin.app')

@section('title', \App\CPU\translate('Super Admin') . ' | Create Auditor')

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-3 mb-3 border-0">
            <h1 class="page-header-title"><b>Add / Edit Assessor</b></h1>
            <p class="text-muted mb-0">Register a new enterprise assessor to the system.</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('super-admin.auditors.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h4 class="mb-3">General Information</h4>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Image (optional)</label>
                            <input type="file" name="image" class="form-control-file">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Applied Designation</label>
                                    <input type="text" name="apply_designation" value="{{ old('apply_designation') }}" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Set Initial Password</label>
                                    <input type="password" name="password" class="form-control" required minlength="8">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">Personal Information</h4>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Highest Qualification</label>
                            <input type="text" name="highest_qualification" value="{{ old('highest_qualification') }}" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Technical Area</label>
                            <input type="text" name="technical_area" value="{{ old('technical_area') }}" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Experience (Years)</label>
                            <input type="number" min="0" name="experience" value="{{ old('experience', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Home Address</label>
                            <textarea name="home_address" rows="3" class="form-control">{{ old('home_address') }}</textarea>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">Extended profile <small class="text-muted">(same as mobile update-profile API)</small></h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Residence telephone</label>
                            <input type="text" name="residence_tel" value="{{ old('residence_tel') }}" class="form-control" placeholder="e.g. 01122334455">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Training</label>
                            <input type="text" name="training" value="{{ old('training') }}" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Specific knowledge gained</label>
                            <textarea name="specific_knowledge_gained" rows="2" class="form-control">{{ old('specific_knowledge_gained') }}</textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Additional information</label>
                            <textarea name="additional_information" rows="2" class="form-control">{{ old('additional_information') }}</textarea>
                        </div>
                    </div>

                    @php
                        $peRows = old('professional_experience');
                        if (!is_array($peRows)) {
                            $peRows = [[]];
                        } elseif (count($peRows) === 0) {
                            $peRows = [[]];
                        }
                        $asRows = old('assessment_summery');
                        if (!is_array($asRows)) {
                            $asRows = [[]];
                        } elseif (count($asRows) === 0) {
                            $asRows = [[]];
                        }
                    @endphp
                    @include('super-admin.auditors.partials.profile-experience-assessment', compact('peRows', 'asRows'))
                    @include('super-admin.auditors.partials.profile-documents', ['assessor' => null])

                    <div class="form-group mt-3">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="sync_to_tenants" value="1" checked>
                            Sync this auditor to all tenant subdomains (so sub-admin can assign assessments).
                        </label>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('super-admin.auditors.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn--primary">Submit Auditor Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

