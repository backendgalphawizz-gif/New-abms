@php
    $assessor = $assessor ?? null;
    $docViewUrl = static function (?string $filename): ?string {
        if ($filename === null || $filename === '') {
            return null;
        }

        return asset('storage/media/' . ltrim($filename, '/'));
    };
@endphp

<hr>
<h4 class="mb-3">Documents <small class="text-muted">(optional — upload replaces stored file)</small></h4>
<div class="row">
    @foreach([
        'qualification_document' => 'Qualification document',
        'work_experience_document' => 'Work experience document',
        'consultancy_document' => 'Consultancy document',
        'audit_document' => 'Audit document',
        'training_document' => 'Training document',
    ] as $field => $label)
        @php
            $current = $assessor ? data_get($assessor, $field) : null;
            $viewUrl = $docViewUrl($current);
        @endphp
        <div class="col-md-6 form-group">
            <label class="mb-1">{{ $label }}</label>
            @if($current)
                <div class="d-flex align-items-center border rounded px-2 py-1 mb-2 bg-light">
                    <span class="small text-muted text-truncate flex-grow-1 pr-2" title="{{ $current }}">{{ $current }}</span>
                    @if($viewUrl)
                        <a
                            href="{{ $viewUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-secondary flex-shrink-0 px-2 py-0 doc-view-btn"
                            title="{{ __('View document') }}"
                            aria-label="{{ __('View document') }}"
                        >
                            <i class="tio-visible-outlined" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            @endif
            <input type="file" name="{{ $field }}" class="form-control-file">
        </div>
    @endforeach
</div>
