@extends('layouts.super-admin.app')

@section('title', \App\CPU\translate('Super Admin') . ' | Create ISO Standard')

@push('script')
    <script>
        (function () {
            const templates = @json($checklistTemplates ?? []);
            const codeInput = document.getElementById('code');
            const preview = document.getElementById('iso-checklist-preview');
            if (!codeInput || !preview) return;

            const normalize = (value) => {
                const upper = String(value || '').toUpperCase();
                const match = upper.match(/ISO\s*\/?\s*(?:IEC\s*)?(\d{4,5})/);
                if (match) return 'ISO' + match[1];
                const digits = upper.replace(/\D+/g, '');
                return digits ? 'ISO' + digits : upper.replace(/\s+/g, '');
            };

            const render = () => {
                const key = normalize(codeInput.value);
                const template = templates[key];
                if (!template) {
                    preview.classList.add('text-muted');
                    preview.innerHTML = 'No predefined checklist for this code.';
                    return;
                }

                const items = (template.items || [])
                    .map((item) => '<li>' + String(item).replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</li>')
                    .join('');
                preview.classList.remove('text-muted');
                preview.innerHTML = '<div class="mb-2"><strong>Source:</strong> ' + template.source + '</div><ul class="mb-0 pl-3">' + items + '</ul>';
            };

            codeInput.addEventListener('input', render);
            render();
        })();
    </script>
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-3 mb-3 border-0">
            <h1 class="page-header-title"><b>Create ISO Standard</b></h1>
            <p class="text-muted mb-0">This option will appear in the entity checklist.</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('super-admin.iso-standards.store') }}">
                    @include('super-admin.iso-standards._form')
                </form>
            </div>
        </div>
    </div>
@endsection
