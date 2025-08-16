<?php

namespace App\Http\Controllers\Tenant\Regulatory;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Regulatory\CompanyRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompanyRegistrationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of company registrations
     */
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        
        $query = CompanyRegistration::where('tenant_id', $tenantId);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('license_type')) {
            $query->where('license_type', $request->license_type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%");
            });
        }
        
        $companies = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get statistics
        $stats = [
            'total' => CompanyRegistration::where('tenant_id', $tenantId)->count(),
            'active' => CompanyRegistration::where('tenant_id', $tenantId)->where('status', 'active')->count(),
            'expired' => CompanyRegistration::where('tenant_id', $tenantId)->expired()->count(),
            'expiring_soon' => CompanyRegistration::where('tenant_id', $tenantId)->expiringSoon()->count(),
        ];
        
        return view('tenant.regulatory.companies.index', compact('companies', 'stats'));
    }

    /**
     * Show the form for creating a new company registration
     */
    public function create()
    {
        return view('tenant.regulatory.companies.create');
    }

    /**
     * Store a newly created company registration
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'company_name' => 'required|string|max:255',
                'company_name_en' => 'nullable|string|max:255',
                'registration_number' => 'required|string|unique:company_registrations,registration_number',
                'license_number' => 'required|string|unique:company_registrations,license_number',
                'license_type' => 'required|in:manufacturing,import,export,distribution,wholesale,retail,research',
                'regulatory_authority' => 'required|string|max:255',
                'registration_date' => 'required|date',
                'license_issue_date' => 'required|date',
                'license_expiry_date' => 'required|date|after:license_issue_date',
                'company_address' => 'required|string',
                'contact_person' => 'required|string|max:255',
                'contact_email' => 'required|email|max:255',
                'contact_phone' => 'required|string|max:20',
                'business_activities' => 'nullable|array',
                'authorized_products' => 'nullable|array',
                'compliance_status' => 'required|in:compliant,non_compliant,under_investigation,corrective_action',
                'next_inspection_date' => 'nullable|date|after:today',
                'notes' => 'nullable|string'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle AJAX validation errors
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل التحقق من البيانات',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        $company = CompanyRegistration::create([
            'id' => Str::uuid(),
            'tenant_id' => Auth::user()->tenant_id,
            'company_name' => $request->company_name,
            'company_name_en' => $request->company_name_en,
            'registration_number' => $request->registration_number,
            'license_number' => $request->license_number,
            'license_type' => $request->license_type,
            'regulatory_authority' => $request->regulatory_authority,
            'registration_date' => $request->registration_date,
            'license_issue_date' => $request->license_issue_date,
            'license_expiry_date' => $request->license_expiry_date,
            'status' => 'active',
            'company_address' => $request->company_address,
            'contact_person' => $request->contact_person,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'business_activities' => $request->business_activities,
            'authorized_products' => $request->authorized_products,
            'compliance_status' => $request->compliance_status,
            'next_inspection_date' => $request->next_inspection_date,
            'notes' => $request->notes
        ]);

        // Handle AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الشركة بنجاح',
                'company' => $company
            ]);
        }

        return redirect()->route('tenant.inventory.regulatory.companies.index')
                        ->with('success', 'تم تسجيل الشركة بنجاح');
    }

    /**
     * Display the specified company registration
     */
    public function show(CompanyRegistration $company)
    {
        $this->authorize('view', $company);
        
        // Load related data
        $company->load(['inspections', 'products', 'regulatoryDocuments']);
        
        return view('tenant.regulatory.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified company registration
     */
    public function edit(CompanyRegistration $company)
    {
        $this->authorize('update', $company);
        
        return view('tenant.regulatory.companies.edit', compact('company'));
    }

    /**
     * Update the specified company registration
     */
    public function update(Request $request, CompanyRegistration $company)
    {
        $this->authorize('update', $company);
        
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_name_en' => 'nullable|string|max:255',
            'registration_number' => 'required|string|unique:company_registrations,registration_number,' . $company->id,
            'license_number' => 'required|string|unique:company_registrations,license_number,' . $company->id,
            'license_type' => 'required|in:manufacturing,import,export,distribution,wholesale,retail,research',
            'regulatory_authority' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'license_issue_date' => 'required|date',
            'license_expiry_date' => 'required|date|after:license_issue_date',
            'status' => 'required|in:active,suspended,expired,under_review,cancelled',
            'company_address' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'business_activities' => 'nullable|array',
            'authorized_products' => 'nullable|array',
            'compliance_status' => 'required|in:compliant,non_compliant,under_investigation,corrective_action',
            'last_inspection_date' => 'nullable|date',
            'next_inspection_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string'
        ]);

        $company->update($request->all());

        return redirect()->route('tenant.inventory.regulatory.companies.show', $company)
                        ->with('success', 'تم تحديث بيانات الشركة بنجاح');
    }

    /**
     * Remove the specified company registration
     */
    public function destroy(CompanyRegistration $company)
    {
        $this->authorize('delete', $company);
        
        $company->delete();

        return redirect()->route('tenant.inventory.regulatory.companies.index')
                        ->with('success', 'تم حذف تسجيل الشركة بنجاح');
    }

    /**
     * Get companies expiring soon
     */
    public function expiringSoon()
    {
        $tenantId = Auth::user()->tenant_id;
        
        $companies = CompanyRegistration::where('tenant_id', $tenantId)
                                      ->expiringSoon()
                                      ->orderBy('license_expiry_date')
                                      ->get();
        
        return view('tenant.regulatory.companies.expiring-soon', compact('companies'));
    }

    /**
     * Renew company license
     */
    public function renew(Request $request, CompanyRegistration $company)
    {
        $this->authorize('update', $company);
        
        $request->validate([
            'new_expiry_date' => 'required|date|after:today',
            'new_license_number' => 'nullable|string|unique:company_registrations,license_number,' . $company->id,
            'notes' => 'nullable|string'
        ]);

        $company->update([
            'license_expiry_date' => $request->new_expiry_date,
            'license_number' => $request->new_license_number ?? $company->license_number,
            'status' => 'active',
            'notes' => $request->notes
        ]);

        return redirect()->route('tenant.inventory.regulatory.companies.show', $company)
                        ->with('success', 'تم تجديد ترخيص الشركة بنجاح');
    }
}
