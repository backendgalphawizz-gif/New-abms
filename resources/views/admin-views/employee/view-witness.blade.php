@extends('layouts.back-end.app')
@section('title','Witness Report Details')

@section('content')
<div class="content container-fluid">


 <div class="d-flex align-items-center mb-3">
    <h2 class="h1 mb-0 d-flex align-items-center">
        <a class="textfont-set" href="{{ route('admin.assessor.witness-list') }}" style="margin-right:15px;">
            <i class="tio-chevron-left"></i> Back
        </a>
        <span class="fw-bold">
            Witness Report 
        </span>
    </h2>
</div>



  
 <div class="card shadow-sm mb-3">
    <div class="card-header bg-light py-2">
        <strong class="fs-6">Witness Status</strong>
    </div>

    <div class="card-body pt-3 pb-2">
        <form id="statusForm" class="d-flex flex-wrap align-items-center gap-5 ps-2">
            @csrf

            <label class="d-flex align-items-center mb-0" style="cursor:pointer;">
                <input class="form-check-input me-2" type="radio" name="status"
                       value="0" {{ $report->status==0?'checked':'' }}>
                <span class="text-warning fw-semibold">Pending</span>
            </label>

            <label class="d-flex align-items-center mb-0" style="cursor:pointer;">
                <input class="form-check-input me-2" type="radio" name="status"
                       value="1" {{ $report->status==1?'checked':'' }}>
                <span class="text-success fw-semibold">Approved</span>
            </label>

            <label class="d-flex align-items-center mb-0" style="cursor:pointer;">
                <input class="form-check-input me-2" type="radio" name="status"
                       value="2" {{ $report->status==2?'checked':'' }}>
                <span class="text-danger fw-semibold">Rejected</span>
            </label>

        </form>
    </div>
</div>



    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold border-bottom pb-2 mb-3">Witness Report Summary</h5>

            <div class="row g-3">

                <div class="col-md-6">
                    <strong>Assessor Name:</strong>
                    <p class="mb-0">{{ $report->assessor->admin->name ?? 'N/A' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Witness Filled By:</strong>
                    <p class="mb-0">{{ $report->user->name ?? 'N/A' }}</p>
                </div>
                
                <div class="col-md-6">
                    <strong>Scheme:</strong>
                    <p class="mb-0">{{ $report->scheme->title ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Role During Assessment:</strong>
                    <p class="mb-0">{{ $report->role_during_assessment ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Location:</strong>
                    <p class="mb-0">{{ $report->location ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Witness Date :</strong>
                    <p class="mb-0">{{ $report->witness_date ? \Carbon\Carbon::parse($report->witness_date)->format('d M Y') : '-'}}</p>
                </div>

                <div class="col-md-6">
                    <strong>Training Required:</strong>
                    <p class="mb-0">{{ $report->training_required ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Overall Recommendation:</strong>
                    <p class="mb-0">{{ $report->overall_recommendation ?? '-' }}</p>
                </div>

                <div class="col-6">
                    <strong>Summary:</strong>
                    <p class="mb-0">{{ $report->summary ?? '-' }}</p>
                </div>

                <div class="col-6">
                    <strong>Accredited Body / Client:</strong>
                    <p class="mb-0">{{ $report->cab_name ?? '-' }}</p>
                </div>

            </div>

        </div>
    </div>


    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold border-bottom pb-2"><i class="tio-file-text"></i> Evaluation Criteria</h5>

            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th class="text-start">Area</th>
                        <th>Comment</th>
                        <th>Rating</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report->evaluation_criteria ?? [] as $row)
                    <tr>
                        <td class="text-start">{{ $row['area'] ?? '-' }}</td>
                        <td>{{ $row['comment'] ?? '-' }}</td>
                        <td><span class="badge bg-info">{{ $row['rating'] ?? '-' }}</span></td>
                        <td>{{ $row['remark'] ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).on("change", "input[name='status']", function () {

    let status = $(this).val();

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to update witness status?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Change",
        cancelButtonText: "No"
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('admin.assessor.update-witness-status', $report->id) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status,
                },
                success: function () {
                    Swal.fire({
                        icon: "success",
                        title: "Updated!",
                        text: "Witness status updated successfully",
                        timer: 1200,
                        showConfirmButton: false
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
