<?php

namespace App\Http\Controllers\Tenant\Regulatory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Tenant\Regulatory\ProductRegistration;
use App\Models\Tenant\Regulatory\CompanyRegistration;

class ProductRegistrationController extends Controller
{
    /**
     * Display a listing of the product registrations
     */
    public function index(Request $request)
    {
        $this->ensureAuthTenant();
        $tenantId = Auth::user()->tenant_id;

        $query = ProductRegistration::where('tenant_id', $tenantId)->latest();

        // Simple filters
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qq) use ($q) {
                $qq->where('product_name', 'like', "%$q%")
                   ->orWhere('registration_number', 'like', "%$q%")
                   ->orWhere('brand_name', 'like', "%$q%")
                   ->orWhere('generic_name', 'like', "%$q%")
                   ->orWhere('manufacturer', 'like', "%$q%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        $registrations = $query->paginate(15)->withQueryString();

        return view('tenant.regulatory.product-registrations.index', compact('registrations'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $this->ensureAuthTenant();
        $tenantId = Auth::user()->tenant_id;
        $companies = CompanyRegistration::where('tenant_id', $tenantId)->orderBy('company_name')->get(['id','company_name']);

        return view('tenant.regulatory.product-registrations.create', [
            'companies' => $companies,
            'productTypes' => ProductRegistration::PRODUCT_TYPES,
            'statusTypes' => ProductRegistration::STATUS_TYPES,
            'dosageForms' => ProductRegistration::DOSAGE_FORMS,
        ]);
    }

    /**
     * Store new registration
     */
    public function store(Request $request)
    {
        $this->ensureAuthTenant();
        $tenantId = Auth::user()->tenant_id;

        $data = $request->validate([
            'company_id' => 'required|exists:company_registrations,id',
            'product_name' => 'required|string|max:255',
            'product_name_en' => 'nullable|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'registration_number' => 'required|string|max:255|unique:product_registrations,registration_number',
            'batch_number' => 'nullable|string|max:255',
            'product_type' => 'required|in:pharmaceutical,vaccine,medical_device,supplement,cosmetic,herbal,biological',
            'therapeutic_class' => 'nullable|string|max:255',
            'dosage_form' => 'nullable|in:tablet,capsule,syrup,injection,cream,ointment,drops,inhaler,suppository,powder',
            'strength' => 'nullable|string|max:255',
            'pack_size' => 'nullable|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'country_of_origin' => 'required|string|max:255',
            'regulatory_authority' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'approval_date' => 'nullable|date',
            'expiry_date' => 'required|date|after:registration_date',
            'renewal_date' => 'nullable|date|after:registration_date',
            'status' => 'required|in:registered,pending,approved,rejected,suspended,withdrawn,expired',
            'notes' => 'nullable|string',
        ]);

        $registration = new ProductRegistration();
        $registration->id = Str::uuid();
        $registration->tenant_id = $tenantId;
        foreach ($data as $k => $v) { $registration->$k = $v; }
        $registration->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم تسجيل المنتج بنجاح', 'registration' => $registration]);
        }

        return redirect()->route('tenant.inventory.regulatory.product-registrations.index')
            ->with('success', 'تم تسجيل المنتج بنجاح');
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        $this->ensureAuthTenant();
        return view('tenant.regulatory.product-registrations.import');
    }

    /**
     * Import from CSV (simple implementation without external packages)
     */
    public function import(Request $request)
    {
        $this->ensureAuthTenant();
        $tenantId = Auth::user()->tenant_id;

        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return back()->with('error', 'تعذر قراءة الملف');
        }

        $header = fgetcsv($handle);
        $created = 0; $skipped = 0; $errors = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            if (!$data) { $errors++; continue; }
            try {
                // Minimal required fields
                $required = ['company_id','product_name','registration_number','product_type','manufacturer','country_of_origin','regulatory_authority','registration_date','expiry_date','status'];
                foreach ($required as $key) { if (empty($data[$key])) { throw new \Exception("حقل مفقود: $key"); } }

                $r = new ProductRegistration();
                $r->id = Str::uuid();
                $r->tenant_id = $tenantId;
                $r->company_id = $data['company_id'];
                $r->product_name = $data['product_name'];
                $r->registration_number = $data['registration_number'];
                $r->product_type = $data['product_type'];
                $r->manufacturer = $data['manufacturer'];
                $r->country_of_origin = $data['country_of_origin'];
                $r->regulatory_authority = $data['regulatory_authority'];
                $r->registration_date = $data['registration_date'];
                $r->expiry_date = $data['expiry_date'];
                $r->status = $data['status'] ?? 'pending';
                // Optional fields
                $r->product_name_en = $data['product_name_en'] ?? null;
                $r->generic_name = $data['generic_name'] ?? null;
                $r->brand_name = $data['brand_name'] ?? null;
                $r->batch_number = $data['batch_number'] ?? null;
                $r->therapeutic_class = $data['therapeutic_class'] ?? null;
                $r->dosage_form = $data['dosage_form'] ?? null;
                $r->strength = $data['strength'] ?? null;
                $r->pack_size = $data['pack_size'] ?? null;
                $r->approval_date = $data['approval_date'] ?? null;
                $r->renewal_date = $data['renewal_date'] ?? null;
                $r->notes = $data['notes'] ?? null;
                $r->save();
                $created++;
            } catch (\Throwable $e) {
                $skipped++; // keep going
            }
        }
        fclose($handle);

        return back()->with('success', "تم الاستيراد: {$created}، تم تجاوز: {$skipped}");
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            'company_id','product_name','product_name_en','generic_name','brand_name','registration_number','batch_number',
            'product_type','therapeutic_class','dosage_form','strength','pack_size','manufacturer','country_of_origin',
            'regulatory_authority','registration_date','approval_date','expiry_date','renewal_date','status','notes'
        ];

        return response()->streamDownload(function () use ($headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            fclose($out);
        }, 'product_registrations_template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export CSV of current tenant registrations
     */
    public function export(Request $request): StreamedResponse
    {
        $this->ensureAuthTenant();
        $tenantId = Auth::user()->tenant_id;
        $rows = ProductRegistration::where('tenant_id', $tenantId)->orderBy('created_at','desc')->get();

        $headers = [
            'company_id','product_name','product_name_en','generic_name','brand_name','registration_number','batch_number',
            'product_type','therapeutic_class','dosage_form','strength','pack_size','manufacturer','country_of_origin',
            'regulatory_authority','registration_date','approval_date','expiry_date','renewal_date','status','notes'
        ];

        return response()->streamDownload(function () use ($rows, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->company_id,
                    $r->product_name,
                    $r->product_name_en,
                    $r->generic_name,
                    $r->brand_name,
                    $r->registration_number,
                    $r->batch_number,
                    $r->product_type,
                    $r->therapeutic_class,
                    $r->dosage_form,
                    $r->strength,
                    $r->pack_size,
                    $r->manufacturer,
                    $r->country_of_origin,
                    $r->regulatory_authority,
                    optional($r->registration_date)->format('Y-m-d'),
                    optional($r->approval_date)->format('Y-m-d'),
                    optional($r->expiry_date)->format('Y-m-d'),
                    optional($r->renewal_date)->format('Y-m-d'),
                    $r->status,
                    $r->notes,
                ]);
            }
            fclose($out);
        }, 'product_registrations.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function ensureAuthTenant(): void
    {
        if (!Auth::check() || !Auth::user()->tenant_id) {
            abort(401);
        }
    }
}

