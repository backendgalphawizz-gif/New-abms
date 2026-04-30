<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\IsoStandard;
use App\Support\IsoChecklistTemplates;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IsoStandardController extends Controller
{
    public function index()
    {
        $standards = IsoStandard::query()
            ->orderBy('sort_order')
            ->orderBy('code')
            ->paginate(20);

        return view('super-admin.iso-standards.index', compact('standards'));
    }

    public function create()
    {
        return view('super-admin.iso-standards.create', [
            'checklistTemplates' => IsoChecklistTemplates::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:central.iso_standards,code'],
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        IsoStandard::query()->create([
            'code' => trim($validated['code']),
            'name' => trim($validated['name']),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('super-admin.iso-standards.index')
            ->with('success', 'ISO standard created successfully.');
    }

    public function edit(IsoStandard $iso_standard)
    {
        return view('super-admin.iso-standards.edit', [
            'standard' => $iso_standard,
            'checklistTemplates' => IsoChecklistTemplates::all(),
        ]);
    }

    public function update(Request $request, IsoStandard $iso_standard)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:80',
                Rule::unique('central.iso_standards', 'code')->ignore($iso_standard->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $iso_standard->update([
            'code' => trim($validated['code']),
            'name' => trim($validated['name']),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('super-admin.iso-standards.index')
            ->with('success', 'ISO standard updated successfully.');
    }

    public function destroy(IsoStandard $iso_standard)
    {
        $iso_standard->delete();

        return redirect()
            ->route('super-admin.iso-standards.index')
            ->with('success', 'ISO standard deleted successfully.');
    }
}
