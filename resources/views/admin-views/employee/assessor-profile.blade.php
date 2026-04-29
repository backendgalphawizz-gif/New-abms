@extends('layouts.back-end.app')
@section('title', 'Assessor Profile')

@section('content')


<div class="content container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h1 mb-0">
            <a class="textfont-set" href="{{ route('admin.assessor.assessor-list') }}">
                <i class="tio-chevron-left"></i> Back
            </a>
            Assessor Profile 
        </h2>

        <a href="{{ route('admin.assessor.update-assessor', [$admin->id]) }}" class="btn btn--primary">
            <i class="tio-edit"></i> Edit
        </a>
    </div>

    <div class="card mb-4 shadow-sm border-0">
    <div class="card-body">

        <div class="row align-items-center">

            {{-- IMAGE --}}
            <div class="col-md-3 text-center p-3 border-end">
                <img src="{{ asset('storage/app/public/admin/'.$admin->image) }}"
                     width="140" height="140" class="rounded-circle border"
                     style="object-fit: cover;"
                     onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'">
            </div>

            {{-- DETAILS --}}
            <div class="col-md-9 ps-4">

                <h4 class="mb-3 text-capitalize fw-bold">{{ $admin->name }}</h4>

                <div class="row gy-2">

                    <div class="col-md-6"><strong>Email:</strong> {{ $admin->email }}</div>
                    <div class="col-md-6"><strong>Phone:</strong> {{ $admin->phone }}</div>
                    <div class="col-md-6"><strong>Address:</strong> {{ $assessor->home_address ?? '-' }}</div>
                    <div class="col-md-6"><strong>ID Number:</strong> {{ $assessor->id_number ?? '-' }}</div>
                    <div class="col-md-6"><strong>Designation:</strong> {{ $assessor->apply_designation ?? '-' }}</div>

                    <div class="col-md-6">
                        <strong>Account Status:</strong>
                        @if($admin->status == 0)
                            <span class="badge bg-danger text-white">Inactive</span>
                        @else
                            <span class="badge bg-success text-white ">Active</span>
                        @endif
                    </div>

                    <div class="col-md-6 mt-2">
                        <strong>Witness Status:</strong>
                        @if($assessor->witness_status == 0)
                            <span class="badge bg-info text-white">Not Yet</span>
                        @elseif($assessor->witness_status == 1)
                            <span class="badge bg-warning text-white">Pending</span>
                        @elseif($assessor->witness_status == 2)
                            <span class="badge bg-success text-white">Approved</span>
                        @else
                            <span class="badge bg-danger text-white">Rejected</span>
                        @endif
                    </div>

                </div>

               
                <div class="card mt-3 shadow-sm border">
                    <div class="card-header bg-light py-2">
                        <strong>{{ \App\CPU\translate('Profile Status') }}</strong>
                    </div>

                    <div class="card-body">
                        <form id="statusForm">
                            @csrf
                            <div class="d-flex flex-wrap gap-4">

                                <label class="form-check d-flex align-items-center">
                                    <input class="form-check-input me-2" type="radio" name="profile_status" value="0"
                                        {{ $assessor->profile_status==0 ? 'checked' : '' }}>
                                    <span class="text-warning fw-semibold">{{ \App\CPU\translate('Pending') }}</span>
                                </label>

                                <label class="form-check d-flex align-items-center">
                                    <input class="form-check-input me-2" type="radio" name="profile_status" value="1"
                                        {{ $assessor->profile_status==1 ? 'checked' : '' }}>
                                    <span class="text-success fw-semibold">{{ \App\CPU\translate('Approved') }}</span>
                                </label>

                                <label class="form-check d-flex align-items-center">
                                    <input class="form-check-input me-2" type="radio" name="profile_status" value="2"
                                        {{ $assessor->profile_status==2 ? 'checked' : '' }}>
                                    <span class="text-danger fw-semibold">{{ \App\CPU\translate('Rejected') }}</span>
                                </label>

                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>




   
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active fw-bold" data-bs-toggle="tab" href="#resumeTab" role="tab">
                <i class="tio-file"></i> Resume
            </a>
        </li>

       <li class="nav-item">
            <a class="nav-link fw-bold" data-bs-toggle="tab" href="#competenceTab" role="tab">
            ABMS Evaluation
            </a>
        </li>


        
    </ul>

  
    <div class="tab-content">

        
        <div class="tab-pane fade show active" id="resumeTab">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">

                    
                    <h5 class="mb-3 border-bottom pb-2"><i class="tio-user"></i> Personal Information</h5>
                    <table class="table table-bordered table-striped">
                        <tr><th width="30%">ID Number</th><td>{{ $assessor->id_number ?? '-' }}</td></tr>
                        <tr><th>Applied Designation</th><td>{{ $assessor->apply_designation ?? '-' }}</td></tr>
                        <tr><th>Technical Area</th><td>{{ $assessor->technical_area ?? '-' }}</td></tr>
                        <tr><th>Higher Qualification</th><td>{{ $assessor->highest_qualification ?? '-' }}</td></tr>
                        <tr><th>Year of Experience</th><td>{{ $assessor->experience ?? '-' }}</td></tr>
                        <!-- <tr><th>Home Address</th><td>{{ $assessor->home_address ?? '-' }}</td></tr> -->
                        <tr><th>Passport Number</th><td>{{ $assessor->passport_number  ?? '-' }}</td></tr>
                        <tr><th>Passport Validity</th><td>{{ $assessor->passport_validity  ?? '-' }}</td></tr>
                        <tr>
                            <th>Password Image</th>
                            <td>
                                @if(!empty($assessor->passport_image))
                                    {!! '<a href="'.asset($assessor->passport_image).'" class="btn btn-info" target="_blank">View</a>' !!}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>

                     <h5 class="mt-4 mb-3 border-bottom pb-2"></i> Scheme / Area / Scope Details</h5>
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th width="30%">Title</th>
                                <th>Expertise</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Scheme</strong></td>
                                <td>{{ $assessor->schemeNames ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Area</strong></td>
                                <td>{{ $assessor->areaNames ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Scope</strong></td>
                                <td>{{ $assessor->scopeNames ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="tio-book"></i>Competence Relevent Qualification</h5>

                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-secondary">
                        <tr>
                            <th>Scheme</th>
                            <th>Area</th>
                            <th>Scope</th>
                            <th>Qualification</th>
                            <th>Status</th>
                            <th>Update By</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($assessor->area_qualifications))
                            @foreach($assessor->area_qualifications as $key=>$value)
                                <tr>
                                    <td>{{ $value['scheme_title'] ?? '-' }}</td>
                                    <td>{{ $value['area_title'] ?? '-' }}</td>
                                    <td>{{ $value['scope_title'] ?? '-' }}</td>
                                    <td>{{ $value['qualification'] ?? '-' }}</td>
                                    <td>
                                        @if ($value['status'] == 0)
                                            <select class="form-control qualificationStatus" data-type="area" data-key="{{ $key }}" data-value='{!! json_encode($value) !!}' name="status">
                                                <option value="0">Pending</option>
                                                <option value="1">Approve</option>
                                                <option value="2">Reject</option>
                                            </select>
                                        @elseif($value['status'] == 1)    
                                            <span class="badge bg-success text-white">Approved</span>
                                        @else    
                                            <span class="badge bg-danger text-white">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $value['updated_by'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(!empty($assessor->scope_qualifications))
                            @foreach($assessor->scope_qualifications as $key=> $value)
                                <tr>
                                    <td>{{ $value['scheme_title'] ?? '-' }}</td>
                                    <td>{{ $value['area_title'] ?? '-' }}</td>
                                    <td>{{ $value['scope_title'] ?? '-' }}</td>
                                    <td>
                                        @if ($value['status'] == 0)
                                            <select class="form-control qualificationStatus" data-type="scope" data-key="{{ $key }}" data-value="{!! json_encode($value) !!}" name="status">
                                                <option value="0">Pending</option>
                                                <option value="1">Approve</option>
                                                <option value="2">Reject</option>
                                            </select>
                                        @elseif($value['status'] == 1)    
                                            <span class="badge bg-success text-white">Approved</span>
                                        @else    
                                            <span class="badge bg-danger text-white">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $value['updated_by'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(empty($assessor->area_qualifications) && empty($assessor->scope_qualifications))
                            <tr><td colspan="5" class="text-center text-muted">No Qualifications Found</td></tr>
                        @endif
                        </tbody>
                    </table>


                    <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="tio-book"></i> Qualification Details</h5>
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-secondary">
                        <tr>
                            <th>Degree/Certificate</th>
                            <th>Institute</th>
                            <th>Year</th>
                            <th>Remark</th>
                            <!-- <th>Certificate</th> -->
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($qualifications) && is_array($qualifications))
                            @foreach($qualifications as $q)
                                <tr>
                                    <td>{{ $q['qualification'] ?? '-' }}</td>
                                    <td>{{ $q['institution'] ?? '-' }}</td>
                                    <td>{{ $q['year'] ?? '-' }}</td>
                                    <td>{{ $q['remarks'] ?? '-' }}</td>
                                    <!-- <td>
                                        @if(!empty($q['file']))
                                            <a href="{{ asset($q['file']) }}" class="btn btn-sm btn-primary" target="_blank">View</a>
                                        @else <span class="text-muted">No File</span> @endif
                                    </td> -->
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="5" class="text-center text-muted">No Qualifications Found</td></tr>
                        @endif
                        </tbody>
                    </table>

                 
                    <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="tio-briefcase"></i> Professional Experience</h5>
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-secondary">
                        <tr>
                            <th>Organization</th>
                            <th>Position</th>
                            <th>Duration</th>
                            <th>Key Responsibilities</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($experience) && is_array($experience))
                            @foreach($experience as $exp)
                                <tr>
                                    <td>{{ $exp['organization'] ?? '-' }}</td>
                                    <td>{{ $exp['position'] ?? '-' }}</td>
                                    <td>{{ $exp['duration'] ?? '-' }}</td>
                                    <td>{{ $exp['key_responsibilities'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="4" class="text-center text-muted">No Experience Found</td></tr>
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="competenceTab">
            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    <h4 class="fw-bold mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                        <i class="tio-award"></i> Competence Assessment Summary
                    </h4>

                    <p class="small text-muted mb-3">
                        <strong>*Rating:</strong> C – Competent | S – Satisfactory | N – Needs Improvement
                    </p>

                    <form action="{{ route('admin.assessor.save-competence-evaluation', [$admin->id]) }}" method="POST">

                        @csrf

                       
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-start">Competence Area</th>
                                    <th>Evaluator Remarks</th>
                                    <th>Rating (C/S/N)</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $competenceList = [
                                    "Knowledge of ISO/IEC 17011",
                                    "Knowledge of Relevant Scheme Standards",
                                    "Assessment & Audit Skills",
                                    "Technical Expertise",
                                    "Report Writing & Communication"
                                ];
                            @endphp

                            @foreach($competenceList as $index => $area)
                                <tr>
                                    <td class="text-start">{{ $area }}</td>
                                    <td>
                                        <input type="text" name="remarks[]"
                                            value="{{ $assessment[$index]['remark'] ?? '' }}"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <select name="rating[]" class="form-control form-control-sm text-center">
                                            <option value="">Select</option>
                                            <option value="Competent" {{ ($assessment[$index]['rating'] ?? '') == 'Competent' ? 'selected' : '' }}>Competent</option>
                                            <option value="Satisfactory" {{ ($assessment[$index]['rating'] ?? '') == 'Satisfactory' ? 'selected' : '' }}>Satisfactory</option>
                                            <option value="Needs Improvement" {{ ($assessment[$index]['rating'] ?? '') == 'Needs Improvement' ? 'selected' : '' }}>Needs Improvement</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                       
                        <h4 class="fw-bold mb-3 mt-4 d-flex align-items-center gap-2 border-bottom pb-3">
                            <i class="tio-user"></i> Evaluation Decision
                        </h4>

                        <div class="card bg-light shadow-sm border-0 mb-4">
                            <div class="card-body">

                                @php
                                    $eval = $assessor->evaluation_details[0] ?? null;
                                @endphp

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="fw-semibold text-dark mb-1">
                                            Evaluator Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="evaluator_name"
                                            value="{{ $eval['evaluator_name'] ?? '' }}"
                                            class="form-control" placeholder="Enter evaluator name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-semibold text-dark mb-1">
                                            Date of Evaluation <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="evaluation_date"
                                            value="{{ $eval['evaluation_date'] ?? '' }}"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <label class="fw-semibold text-dark mb-2">
                                    Overall Recommendation <span class="text-danger">*</span>
                                </label>

                                @php 
                                    $rec = $eval['overall_recommendation'] ?? '';
                                @endphp

                                <div class="d-flex flex-wrap gap-3 ps-2">

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" required
                                            name="evaluation_recommendation" value="Competent"
                                            {{ $rec=='Competent'?'checked':'' }}>
                                        <label class="form-check-label fw-semibold text-success">
                                            Competent
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" required
                                            name="evaluation_recommendation" value="Needs Improvement"
                                            {{ $rec=='Needs Improvement'?'checked':'' }}>
                                        <label class="form-check-label fw-semibold text-warning">
                                            Needs Improvement
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" required
                                            name="evaluation_recommendation" value="Not Approved"
                                            {{ $rec=='Not Approved'?'checked':'' }}>
                                        <label class="form-check-label fw-semibold text-danger">
                                            Not Approved
                                        </label>
                                    </div>

                                </div>

                                <div class="mt-4">
                                    <label class="fw-semibold text-dark mb-1">
                                        Comments <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="evaluation_comment" class="form-control" rows="3" required
                                        placeholder="Write brief comments...">{{ $eval['comment'] ?? '' }}</textarea>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                                        Save
                                    </button>
                                </div>

                            </div>
                        </div>




                    </form>

                </div>
            </div>
        </div>


       

    </div>

</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@push('script')
<script>
$(document).on("change", "input[name='profile_status']", function () {

    let status = $(this).val();

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to change the assessor status?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Change",
        cancelButtonText: "No"
    }).then((result) => {

        if (result.value) {

            $.ajax({
                url: "{{ route('admin.assessor.update-status', $admin->id) }}",
                type: "POST",
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                data: { profile_status: status },
                success: function () {
                    Swal.fire({
                        icon: "success",
                        title: "Updated!",
                        text: "Profile status updated successfully",
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => location.reload());
                },
                error: function () {
                    Swal.fire("Error", "Something went wrong!", "error");
                }
            });

        } else {
            location.reload(); // Reset selected radio back
        }

    });

});

$(document).on("change", ".qualificationStatus", function () {

    let status = $(this).val();
    let type = $(this).data('type');
    let key = $(this).data('key');
    let value = $(this).data('value'); 

    console.log('status', status);
    console.log('type', type);
    console.log('key', key);
    console.log('value', value);

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to change the qualification status?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Change",
        cancelButtonText: "No"
    }).then((result) => {

        if (result.value) {

            $.ajax({
                url: "{{ route('admin.assessor.update-qualification-status', $admin->id) }}",
                type: "POST",
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                data: { 
                        status: status, 
                        type: type, 
                        key: key, 
                        value: value 
                    },
                success: function () {
                    Swal.fire({
                        icon: "success",
                        title: "Updated!",
                        text: "Qualification status updated successfully",
                        // timer: 1200,
                        showConfirmButton: true
                    }).then(() => location.reload());
                },
                error: function () {
                    Swal.fire("Error", "Something went wrong!", "error");
                }
            });

        } else {
            location.reload();
        }

    });

});
</script>
@endpush





