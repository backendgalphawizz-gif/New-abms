{{--
  $peRows, $asRows: arrays of row data (may contain one empty row for new entry)
--}}
<hr>
<h4 class="mb-3">Professional experience <small class="text-muted">(audit records)</small></h4>
<p class="text-muted small mb-2">Add one or more rows. Empty rows are ignored when saving.</p>
<div id="pe-wrap" class="mb-4" data-next-index="{{ count($peRows) }}">
    @foreach($peRows as $idx => $row)
        @php $row = is_array($row) ? $row : []; @endphp
        <div class="border rounded p-3 mb-3 pe-item">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="pe-item-title">Experience #{{ $idx + 1 }}</strong>
                <button type="button" class="btn btn-sm btn-outline-danger pe-remove" @if(count($peRows) < 2) style="display:none" @endif>Remove</button>
            </div>
            <div class="row">
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Date of audit</label>
                    <input type="text" name="professional_experience[{{ $idx }}][date_of_audit]" value="{{ old('professional_experience.'.$idx.'.date_of_audit', $row['date_of_audit'] ?? '') }}" class="form-control form-control-sm" placeholder="YYYY-MM-DD">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Organization name</label>
                    <input type="text" name="professional_experience[{{ $idx }}][organization_name]" value="{{ old('professional_experience.'.$idx.'.organization_name', $row['organization_name'] ?? '') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Product / service</label>
                    <input type="text" name="professional_experience[{{ $idx }}][product_service]" value="{{ old('professional_experience.'.$idx.'.product_service', $row['product_service'] ?? '') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Role in audit</label>
                    <input type="text" name="professional_experience[{{ $idx }}][role_in_audit]" value="{{ old('professional_experience.'.$idx.'.role_in_audit', $row['role_in_audit'] ?? '') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Type of audit</label>
                    <input type="text" name="professional_experience[{{ $idx }}][type_of_audit]" value="{{ old('professional_experience.'.$idx.'.type_of_audit', $row['type_of_audit'] ?? '') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Number of days</label>
                    <input type="text" name="professional_experience[{{ $idx }}][number_of_days]" value="{{ old('professional_experience.'.$idx.'.number_of_days', $row['number_of_days'] ?? '') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Certification body</label>
                    <input type="text" name="professional_experience[{{ $idx }}][certification_body]" value="{{ old('professional_experience.'.$idx.'.certification_body', $row['certification_body'] ?? '') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Standard</label>
                    <input type="text" name="professional_experience[{{ $idx }}][standard]" value="{{ old('professional_experience.'.$idx.'.standard', $row['standard'] ?? '') }}" class="form-control form-control-sm">
                </div>
            </div>
        </div>
    @endforeach
</div>
<button type="button" class="btn btn-sm btn-outline-primary mb-4" id="pe-add">+ Add experience</button>

<hr>
<h4 class="mb-3">Assessment summary <small class="text-muted">(consultancy / summary records)</small></h4>
<p class="text-muted small mb-2">Add one or more rows. Empty rows are ignored when saving.</p>
<div id="as-wrap" class="mb-4" data-next-index="{{ count($asRows) }}">
    @foreach($asRows as $idx => $row)
        @php $row = is_array($row) ? $row : []; @endphp
        <div class="border rounded p-3 mb-3 as-item">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="as-item-title">Summary #{{ $idx + 1 }}</strong>
                <button type="button" class="btn btn-sm btn-outline-danger as-remove" @if(count($asRows) < 2) style="display:none" @endif>Remove</button>
            </div>
            <div class="row">
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Year</label>
                    <input type="text" name="assessment_summery[{{ $idx }}][year]" value="{{ old('assessment_summery.'.$idx.'.year', $row['year'] ?? '') }}" class="form-control form-control-sm" placeholder="YYYY">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Organization name</label>
                    <input type="text" name="assessment_summery[{{ $idx }}][organization_name]" value="{{ old('assessment_summery.'.$idx.'.organization_name', $row['organization_name'] ?? '') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Management system standard</label>
                    <input type="text" name="assessment_summery[{{ $idx }}][management_system_standard]" value="{{ old('assessment_summery.'.$idx.'.management_system_standard', $row['management_system_standard'] ?? '') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Product / service</label>
                    <input type="text" name="assessment_summery[{{ $idx }}][product_service]" value="{{ old('assessment_summery.'.$idx.'.product_service', $row['product_service'] ?? '') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-4 form-group mb-2">
                    <label class="small mb-0">Certified by</label>
                    <input type="text" name="assessment_summery[{{ $idx }}][certified_by]" value="{{ old('assessment_summery.'.$idx.'.certified_by', $row['certified_by'] ?? '') }}" class="form-control form-control-sm">
                </div>
            </div>
        </div>
    @endforeach
</div>
<button type="button" class="btn btn-sm btn-outline-primary mb-3" id="as-add">+ Add summary</button>

@push('script')
<script>
(function () {
    function renumberTitles(container, selItem, titleSel, label) {
        container.querySelectorAll(selItem).forEach(function (el, i) {
            var t = el.querySelector(titleSel);
            if (t) t.textContent = label + ' #' + (i + 1);
        });
    }
    function toggleRemoveButtons(container, selItem, selRemove) {
        var items = container.querySelectorAll(selItem);
        var show = items.length > 1;
        items.forEach(function (el) {
            var b = el.querySelector(selRemove);
            if (b) b.style.display = show ? '' : 'none';
        });
    }
    function addRepeaterRow(wrapId, itemSel, addId, removeSel, titleSel, label) {
        var wrap = document.getElementById(wrapId);
        var addBtn = document.getElementById(addId);
        if (!wrap || !addBtn) return;
        var items = wrap.querySelectorAll(itemSel);
        if (!items.length) return;
        var template = items[items.length - 1];
        var idx = parseInt(wrap.getAttribute('data-next-index') || String(items.length), 10);
        var clone = template.cloneNode(true);
        clone.querySelectorAll('input').forEach(function (inp) {
            var n = inp.getAttribute('name');
            if (n) {
                inp.setAttribute('name', n.replace(/\[\d+\]/, '[' + idx + ']'));
            }
            inp.value = '';
        });
        wrap.appendChild(clone);
        wrap.setAttribute('data-next-index', String(idx + 1));
        renumberTitles(wrap, itemSel, titleSel, label);
        toggleRemoveButtons(wrap, itemSel, removeSel);
    }
    function bindRemove(wrapId, itemSel, removeSel, titleSel, label) {
        var wrap = document.getElementById(wrapId);
        if (!wrap) return;
        wrap.addEventListener('click', function (e) {
            var btn = e.target.closest(removeSel);
            if (!btn || !wrap.contains(btn)) return;
            var item = btn.closest(itemSel);
            if (!item) return;
            var items = wrap.querySelectorAll(itemSel);
            if (items.length < 2) return;
            item.remove();
            renumberTitles(wrap, itemSel, titleSel, label);
            toggleRemoveButtons(wrap, itemSel, removeSel);
        });
    }
    document.getElementById('pe-add') && document.getElementById('pe-add').addEventListener('click', function () {
        addRepeaterRow('pe-wrap', '.pe-item', 'pe-add', '.pe-remove', '.pe-item-title', 'Experience');
    });
    document.getElementById('as-add') && document.getElementById('as-add').addEventListener('click', function () {
        addRepeaterRow('as-wrap', '.as-item', 'as-add', '.as-remove', '.as-item-title', 'Summary');
    });
    bindRemove('pe-wrap', '.pe-item', '.pe-remove', '.pe-item-title', 'Experience');
    bindRemove('as-wrap', '.as-item', '.as-remove', '.as-item-title', 'Summary');
})();
</script>
@endpush
