@csrf
@if(isset($standard))
    @method('PUT')
@endif

<div class="form-group">
    <label for="code">ISO code</label>
    <input type="text"
           id="code"
           name="code"
           value="{{ old('code', $standard->code ?? '') }}"
           class="form-control @error('code') is-invalid @enderror"
           placeholder="ISO 9001:2015"
           required>
    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <small class="form-text text-muted">For ISO 9001/14001/45001, 22000, 13485, 21001 checklist headings are auto-detected from uploaded mapping files.</small>
</div>

<div class="form-group">
    <label for="name">Standard name</label>
    <input type="text"
           id="name"
           name="name"
           value="{{ old('name', $standard->name ?? '') }}"
           class="form-control @error('name') is-invalid @enderror"
           placeholder="Quality Management System"
           required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="sort_order">Sort order</label>
    <input type="number"
           id="sort_order"
           name="sort_order"
           value="{{ old('sort_order', $standard->sort_order ?? 0) }}"
           class="form-control @error('sort_order') is-invalid @enderror"
           min="0">
    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="form-group form-check">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox"
           id="is_active"
           name="is_active"
           value="1"
           class="form-check-input"
           {{ old('is_active', isset($standard) ? (int) $standard->is_active : 1) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active (show in create entity form)</label>
</div>

<div class="form-group">
    <label>Detected checklist headings</label>
    <div id="iso-checklist-preview" class="border rounded p-3 bg-light text-muted">
        Enter an ISO code to preview matching checklist headings.
    </div>
</div>

<button type="submit" class="btn btn--primary">{{ isset($standard) ? 'Update' : 'Create' }}</button>
<a href="{{ route('super-admin.iso-standards.index') }}" class="btn btn-secondary">Cancel</a>
