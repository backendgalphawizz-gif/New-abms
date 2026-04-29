<?php

namespace App\Http\Controllers\Web;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Application;
use App\Model\CompanyProfile;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerPortalController extends Controller
{
    public function applyForm()
    {
        $customer = auth('customer')->user();
        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $isoCertifications = Helpers::get_business_settings('entity_iso_certifications');
        if (!is_array($isoCertifications)) {
            $isoCertifications = [];
        }

        return view('cab.apply', [
            'customer' => $customer,
            'profile' => $profile,
            'isoCertifications' => $isoCertifications,
        ]);
    }

    public function applyStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:191',
            'address' => 'required|string|max:2000',
            'city_country' => 'nullable|string|max:191',
            'pincode' => 'nullable|string|max:64',
            'telephone' => 'nullable|string|max:64',
            'contact_no' => 'nullable|string|max:64',
            'fax' => 'nullable|string|max:191',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:191',
            'standards_to_be_assessed' => 'nullable|string|max:4000',
            'exclusion_requirements' => 'nullable|string|max:4000',
            'accreditation_required' => 'nullable|string|max:4000',
            'other_information' => 'nullable|string|max:4000',
            'activities' => 'nullable|string|max:6000',
            'additional_sites' => 'nullable|string|max:6000',
        ]);

        $city = null;
        $country = null;
        if (!empty($validated['city_country'])) {
            $parts = array_values(array_filter(array_map('trim', explode(',', $validated['city_country'], 2))));
            $city = $parts[0] ?? null;
            $country = $parts[1] ?? null;
        }

        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $profile->name = $validated['name'];
        $profile->organization = $validated['name'];
        $profile->address = $validated['address'];
        $profile->city = $city;
        $profile->country = $country;
        $profile->pincode = $validated['pincode'] ?? null;
        $profile->phone = $validated['telephone'] ?? null;
        $profile->fax = $validated['fax'] ?? null;
        $profile->website = $validated['website'] ?? null;
        $profile->email = $validated['email'] ?? null;
        $profile->contact_person_details = [
            'position' => $validated['position'] ?? null,
            'contact_no' => $validated['contact_no'] ?? null,
        ];
        $profile->ownership_details = [
            'standards_to_be_assessed' => $validated['standards_to_be_assessed'] ?? null,
            'exclusion_requirements' => $validated['exclusion_requirements'] ?? null,
            'accreditation_required' => $validated['accreditation_required'] ?? null,
            'other_information' => $validated['other_information'] ?? null,
            'activities' => $validated['activities'] ?? null,
            'additional_sites' => $validated['additional_sites'] ?? null,
        ];
        $profile->status = $profile->status ?: 'pending';
        $profile->save();

        Toastr::success('Company details saved successfully.');
        return redirect()->route('portal.apply.employee');
    }

    public function applyEmployeeForm()
    {
        $customer = auth('customer')->user();
        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];
        $saved = is_array(data_get($ownership, 'employee_distribution')) ? data_get($ownership, 'employee_distribution') : [];
        $departments = ['Design', 'Store', 'Production', 'Accounts', 'Sales', 'Quality / MS', 'Management'];
        $rows = [];
        foreach ($departments as $department) {
            $rows[] = [
                'department' => $department,
                'full_time' => (int) data_get($saved, $department.'.full_time', 0),
                'part_time' => (int) data_get($saved, $department.'.part_time', 0),
            ];
        }

        return view('cab.apply-employee', [
            'customer' => $customer,
            'profile' => $profile,
            'rows' => $rows,
        ]);
    }

    public function applyEmployeeStore(Request $request)
    {
        $validated = $request->validate([
            'employees' => 'required|array|min:1',
            'employees.*.department' => 'required|string|max:80',
            'employees.*.full_time' => 'nullable|integer|min:0|max:99999',
            'employees.*.part_time' => 'nullable|integer|min:0|max:99999',
        ]);

        $distribution = [];
        foreach ((array) $validated['employees'] as $row) {
            $department = trim((string) data_get($row, 'department', ''));
            if ($department === '') {
                continue;
            }
            $distribution[$department] = [
                'full_time' => (int) data_get($row, 'full_time', 0),
                'part_time' => (int) data_get($row, 'part_time', 0),
            ];
        }

        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }
        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];
        $ownership['employee_distribution'] = $distribution;
        $profile->ownership_details = $ownership;
        $profile->status = $profile->status ?: 'pending';
        $profile->save();

        Toastr::success('Employee details saved successfully.');
        return redirect()->route('portal.apply.employee.more');
    }

    public function applyEmployeeMoreForm()
    {
        $customer = auth('customer')->user();
        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];

        return view('cab.apply-employee-more', [
            'customer' => $customer,
            'profile' => $profile,
            'sub_contractors_avg' => data_get($ownership, 'sub_contractors_avg'),
            'subcontract_work_type' => data_get($ownership, 'subcontract_work_type'),
            'legal_statutory_requirements' => data_get($ownership, 'legal_statutory_requirements'),
            'certified_other_systems' => data_get($ownership, 'certified_other_systems'),
        ]);
    }

    public function applyEmployeeMoreStore(Request $request)
    {
        $validated = $request->validate([
            'sub_contractors_avg' => 'nullable|integer|min:0|max:99999',
            'subcontract_work_type' => 'nullable|string|max:4000',
            'legal_statutory_requirements' => 'nullable|string|max:4000',
            'certified_other_systems' => 'nullable|string|max:4000',
        ]);

        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }
        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];
        $ownership['sub_contractors_avg'] = $validated['sub_contractors_avg'] ?? null;
        $ownership['subcontract_work_type'] = $validated['subcontract_work_type'] ?? null;
        $ownership['legal_statutory_requirements'] = $validated['legal_statutory_requirements'] ?? null;
        $ownership['certified_other_systems'] = $validated['certified_other_systems'] ?? null;
        $profile->ownership_details = $ownership;
        $profile->status = $profile->status ?: 'pending';
        $profile->save();

        Toastr::success('Step 3 saved successfully.');
        return redirect()->route('portal.apply.audit');
    }

    public function applyAuditForm()
    {
        $customer = auth('customer')->user();
        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];
        $auditData = is_array(data_get($ownership, 'audit_compliance')) ? data_get($ownership, 'audit_compliance') : [];
        $isoCertifications = Helpers::get_business_settings('entity_iso_certifications');
        if (!is_array($isoCertifications)) {
            $isoCertifications = [];
        }

        return view('cab.apply-audit', [
            'customer' => $customer,
            'profile' => $profile,
            'isoCertifications' => $isoCertifications,
            'auditData' => $auditData,
        ]);
    }

    public function applyAuditStore(Request $request)
    {
        $validated = $request->validate([
            'audit_mode' => 'required|in:onsite,remote',
            'scopes' => 'nullable|array',
            'scopes.*.standard' => 'nullable|string|max:191',
            'scopes.*.sites' => 'nullable|in:single,multiple',
            'scopes.*.design_included' => 'nullable|in:yes,no',
            'scopes.*.outsourced_process' => 'nullable|in:yes,no',
            'scopes.*.sona_attached' => 'nullable|in:yes,no',
            'scopes.*.legal_obligation_note' => 'nullable|string|max:4000',
        ]);

        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];
        $ownership['audit_compliance'] = [
            'audit_mode' => $validated['audit_mode'],
            'scopes' => array_values(array_filter((array) ($validated['scopes'] ?? []), function ($scope) {
                return !empty(data_get($scope, 'standard'));
            })),
        ];
        $profile->ownership_details = $ownership;
        $profile->status = $profile->status ?: 'pending';
        $profile->save();

        Toastr::success('Step 4 saved successfully.');
        return redirect()->route('portal.apply.documents');
    }

    public function applyDocumentsForm()
    {
        $customer = auth('customer')->user();
        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];
        $documents = is_array(data_get($ownership, 'uploaded_documents')) ? data_get($ownership, 'uploaded_documents') : [];

        return view('cab.apply-documents', [
            'customer' => $customer,
            'profile' => $profile,
            'documents' => $documents,
        ]);
    }

    public function applyDocumentsStore(Request $request)
    {
        $request->validate([
            'quality_manual' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'procedures_policies' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'process_sops' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'business_legal_docs' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];
        $documents = is_array(data_get($ownership, 'uploaded_documents')) ? data_get($ownership, 'uploaded_documents') : [];

        $map = [
            'quality_manual' => 'Quality Manual',
            'procedures_policies' => 'Procedures & Policies',
            'process_sops' => 'Process Documents / SOPs',
            'business_legal_docs' => 'Business Legal Docs',
        ];

        foreach ($map as $field => $label) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('portal-documents', 'public');
                $documents[$field] = [
                    'label' => $label,
                    'path' => $path,
                    'name' => $request->file($field)->getClientOriginalName(),
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }
        }

        $ownership['uploaded_documents'] = $documents;
        $profile->ownership_details = $ownership;
        $profile->status = $profile->status ?: 'pending';
        $profile->save();

        Toastr::success('Documents uploaded successfully.');
        return redirect()->route('portal.apply.agreement');
    }

    public function applyAgreementForm()
    {
        $customer = auth('customer')->user();
        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];
        $signature = data_get($ownership, 'agreement.signature');

        return view('cab.apply-agreement', [
            'customer' => $customer,
            'profile' => $profile,
            'signature' => $signature,
        ]);
    }

    public function applyAgreementStore(Request $request)
    {
        $request->validate([
            'digital_signature' => 'nullable|file|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];
        if ($request->hasFile('digital_signature')) {
            $path = $request->file('digital_signature')->store('portal-signatures', 'public');
            $ownership['agreement'] = [
                'signature' => $path,
                'signature_name' => $request->file('digital_signature')->getClientOriginalName(),
                'signed_at' => now()->toDateTimeString(),
            ];
        }
        $profile->ownership_details = $ownership;
        $profile->status = $profile->status ?: 'pending';
        $profile->save();

        Toastr::success('Agreement step completed.');
        return redirect()->route('portal.apply.payment');
    }

    public function applyPaymentForm(Request $request)
    {
        $customer = auth('customer')->user();
        $paymentSuccess = (bool) $request->session()->pull('portal_payment_success', false);

        return view('cab.apply-payment', [
            'customer' => $customer,
            'paymentTotal' => 499.00,
            'currency' => 'USD',
            'paymentSuccess' => $paymentSuccess,
        ]);
    }

    public function applyPaymentStore(Request $request)
    {
        $profile = CompanyProfile::query()
            ->where('user_id', (int) auth('customer')->id())
            ->first();
        if (!$profile) {
            $profile = new CompanyProfile();
            $profile->user_id = (int) auth('customer')->id();
        }

        $ownership = is_array($profile->ownership_details) ? $profile->ownership_details : [];
        $ownership['payment'] = [
            'status' => 'paid',
            'amount' => 499.00,
            'currency' => 'USD',
            'paid_at' => now()->toDateTimeString(),
            'reference' => 'PAY-'.strtoupper(substr(md5((string) now()), 0, 10)),
        ];
        $profile->ownership_details = $ownership;
        $profile->status = 'submitted';
        $profile->save();

        $request->session()->put('portal_payment_success', true);
        return redirect()->route('portal.apply.payment');
    }

    public function home(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $userId = (int) auth('customer')->id();
        $isoCertifications = Helpers::get_business_settings('entity_iso_certifications');
        if (!is_array($isoCertifications)) {
            $isoCertifications = [];
        }
        $applicationsQuery = Application::query()->where('user_id', $userId);
        $totalApplications = (clone $applicationsQuery)->count();
        $approvedApplications = (clone $applicationsQuery)->where('is_accept', 1)->count();
        $pendingApplications = (clone $applicationsQuery)->where('is_accept', 0)->count();
        $latestApplications = (clone $applicationsQuery)->with('scheme')->latest()->take(6)->get();

        if (! CompanyProfile::tableAvailable()) {
            $companies = new LengthAwarePaginator([], 0, 12, (int) $request->get('page', 1), [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
            $customer = auth('customer')->user();

            return view('cab.home', [
                'companies' => $companies,
                'search' => $q,
                'customer' => $customer,
                'company_profiles_missing' => true,
                'isoCertifications' => $isoCertifications,
                'latestApplications' => $latestApplications,
                'totalApplications' => $totalApplications,
                'approvedApplications' => $approvedApplications,
                'pendingApplications' => $pendingApplications,
            ]);
        }

        $companyQuery = CompanyProfile::query()
            ->where('user_id', $userId);

        if ($q !== '') {
            $companyQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('organization', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%');
            });
        }

        $companies = $companyQuery->orderBy('name')->paginate(12)->appends($request->query());

        if ($companies->isEmpty() && $q === '') {
            $ids = Application::query()
                ->where('user_id', $userId)
                ->whereNotNull('company_id')
                ->distinct()
                ->pluck('company_id');
            if ($ids->isNotEmpty()) {
                $companies = CompanyProfile::query()
                    ->whereIn('id', $ids)
                    ->orderBy('name')
                    ->paginate(12);
            }
        }

        $customer = auth('customer')->user();

        return view('cab.home', [
            'companies' => $companies,
            'search' => $q,
            'customer' => $customer,
            'isoCertifications' => $isoCertifications,
            'latestApplications' => $latestApplications,
            'totalApplications' => $totalApplications,
            'approvedApplications' => $approvedApplications,
            'pendingApplications' => $pendingApplications,
        ]);
    }

    public function applications(Request $request)
    {
        $tab = $request->get('tab', 'certifications');
        if (!in_array($tab, ['certifications', 're-certifications', 'surveillance'], true)) {
            $tab = 'certifications';
        }

        $userId = (int) auth('customer')->id();
        $applications = Application::query()
            ->where('user_id', $userId)
            ->with(['scheme', 'payments'])
            ->latest()
            ->paginate(12)
            ->appends(['tab' => $tab]);

        $customer = auth('customer')->user();

        return view('cab.applications', [
            'applications' => $applications,
            'tab' => $tab,
            'customer' => $customer,
        ]);
    }

    public function applicationShow(Application $application)
    {
        if ((int) $application->user_id !== (int) auth('customer')->id()) {
            abort(403);
        }

        $application->load(['scheme', 'auditor', 'payments', 'assessmentstatus']);
        $customer = auth('customer')->user();

        return view('cab.application-detail', [
            'application' => $application,
            'customer' => $customer,
        ]);
    }

    public function respond(Request $request, Application $application)
    {
        if ((int) $application->user_id !== (int) auth('customer')->id()) {
            abort(403);
        }
        if ((int) $application->is_accept !== 0) {
            Toastr::warning('This application was already updated.');

            return back();
        }

        $request->validate([
            'action' => 'required|in:accept,reject',
        ]);

        if ($request->input('action') === 'accept') {
            $application->is_accept = 1;
            $application->save();
            Toastr::success('Application accepted.');
        } else {
            $application->is_accept = 2;
            $application->status = 'reject';
            $application->save();
            Toastr::info('Application rejected.');
        }

        return back();
    }
}
