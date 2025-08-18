<?php

namespace App\Http\Controllers\Tenant\Regulatory;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Regulatory\RegulatoryDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class RegulatoryDocumentController extends Controller
{
    /**
     * Display a listing of the documents
     */
    public function index()
    {
        $documents = RegulatoryDocument::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tenant.regulatory.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new document
     */
    public function create()
    {
        return view('tenant.regulatory.documents.create');
    }

    /**
     * Store a newly created document
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_title' => 'required|string|max:255',
            'document_type' => 'required|in:license,certificate,policy,procedure,legal,compliance',
            'document_number' => 'nullable|string|max:100',
            'issuing_authority' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'description' => 'nullable|string',
            'tags' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Upload file
            $file = $request->file('document_file');
            $filename = time() . '_' . Str::slug($request->document_title) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('regulatory-documents', $filename, 'public');

            // Build data only with existing columns to avoid SQL errors on missing columns
            $columns = Schema::getColumnListing('regulatory_documents');
            $data = [
                'id' => (string) Str::uuid(),
                'tenant_id' => Auth::user()->tenant_id,
            ];

            // Title mapping
            if (in_array('document_title', $columns, true)) {
                $data['document_title'] = $request->document_title;
            } elseif (in_array('title', $columns, true)) {
                $data['title'] = $request->document_title;
            }

            // Type & numbers
            if (in_array('document_type', $columns, true)) $data['document_type'] = $request->document_type;
            if (in_array('document_number', $columns, true)) $data['document_number'] = $request->document_number;

            // Authority mapping
            if (in_array('issuing_authority', $columns, true)) {
                $data['issuing_authority'] = $request->issuing_authority;
            } elseif (in_array('regulatory_authority', $columns, true)) {
                $data['regulatory_authority'] = $request->issuing_authority;
            }

            // Dates mapping
            if (in_array('issue_date', $columns, true)) $data['issue_date'] = $request->issue_date;
            if (in_array('submission_date', $columns, true) && !in_array('issue_date', $columns, true)) $data['submission_date'] = $request->issue_date;
            if (in_array('expiry_date', $columns, true)) $data['expiry_date'] = $request->expiry_date;

            // File fields
            if (in_array('file_path', $columns, true)) $data['file_path'] = $filePath;
            if (in_array('file_name', $columns, true)) $data['file_name'] = $file->getClientOriginalName();
            if (in_array('file_size', $columns, true)) $data['file_size'] = $file->getSize();
            if (in_array('file_type', $columns, true)) $data['file_type'] = $file->getClientOriginalExtension();

            // Optional fields
            if (in_array('description', $columns, true)) $data['description'] = $request->description;
            if (in_array('tags', $columns, true)) $data['tags'] = $request->tags;
            if (in_array('status', $columns, true)) $data['status'] = 'active';

            // Always try to include document_title (fallback to title if column doesn't exist)
            $data['document_title'] = $request->document_title;

            try {
                RegulatoryDocument::create($data);
            } catch (\Exception $ex) {
                if (str_contains($ex->getMessage(), "Unknown column 'document_title'")) {
                    unset($data['document_title']);
                    $data['title'] = $request->document_title;
                    RegulatoryDocument::create($data);
                } else {
                    throw $ex;
                }
            }

            return redirect()->route('tenant.inventory.regulatory.documents.index')
                ->with('success', 'تم إضافة الوثيقة بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء رفع الوثيقة: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Download a document
     */
    public function download($id)
    {
        $document = RegulatoryDocument::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'الملف غير موجود');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Show bulk upload form
     */
    public function showBulkUpload()
    {
        return view('tenant.regulatory.documents.bulk-upload');
    }

    /**
     * Handle bulk upload
     */
    public function bulkUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulk_files.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_type' => 'required|in:license,certificate,policy,procedure,legal,compliance',
            'issuing_authority' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $uploaded = 0;
        $errors = [];

        foreach ($request->file('bulk_files') as $file) {
            try {
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('regulatory-documents', $filename, 'public');

                $columns = Schema::getColumnListing('regulatory_documents');
                $data = [
                    'id' => (string) Str::uuid(),
                    'tenant_id' => Auth::user()->tenant_id,
                ];

                // Title mapping from filename
                $titleValue = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                if (in_array('document_title', $columns, true)) {
                    $data['document_title'] = $titleValue;
                } elseif (in_array('title', $columns, true)) {
                    $data['title'] = $titleValue;
                }

                if (in_array('document_type', $columns, true)) $data['document_type'] = $request->document_type;
                if (in_array('issuing_authority', $columns, true)) {
                    $data['issuing_authority'] = $request->issuing_authority;
                } elseif (in_array('regulatory_authority', $columns, true)) {
                    $data['regulatory_authority'] = $request->issuing_authority;
                }

                if (in_array('issue_date', $columns, true)) $data['issue_date'] = now();
                if (in_array('submission_date', $columns, true) && !in_array('issue_date', $columns, true)) $data['submission_date'] = now();

                if (in_array('file_path', $columns, true)) $data['file_path'] = $filePath;
                if (in_array('file_name', $columns, true)) $data['file_name'] = $file->getClientOriginalName();
                if (in_array('file_size', $columns, true)) $data['file_size'] = $file->getSize();
                if (in_array('file_type', $columns, true)) $data['file_type'] = $file->getClientOriginalExtension();
                if (in_array('status', $columns, true)) $data['status'] = 'active';

                // Always try to include document_title; fallback to title
                $data['document_title'] = $titleValue;
                try {
                    RegulatoryDocument::create($data);
                } catch (\Exception $ex) {
                    if (str_contains($ex->getMessage(), "Unknown column 'document_title'")) {
                        unset($data['document_title']);
                        $data['title'] = $titleValue;
                        RegulatoryDocument::create($data);
                    } else {
                        throw $ex;
                    }
                }

                $uploaded++;
            } catch (\Exception $e) {
                $errors[] = "خطأ في رفع الملف {$file->getClientOriginalName()}: " . $e->getMessage();
            }
        }

        $message = "تم رفع {$uploaded} ملف بنجاح";
        if (!empty($errors)) {
            return back()->with('warning', $message)->with('upload_errors', $errors);
        }

        return redirect()->route('tenant.inventory.regulatory.documents.index')
            ->with('success', $message);
    }

    /**
     * Export documents list to Excel
     */
    public function exportToExcel()
    {
        $documents = RegulatoryDocument::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'regulatory_documents_' . date('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse(function() use ($documents) {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($handle, "\xEF\xBB\xBF");
            
            // CSV Headers
            fputcsv($handle, [
                'عنوان الوثيقة',
                'نوع الوثيقة',
                'رقم الوثيقة',
                'الجهة المصدرة',
                'تاريخ الإصدار',
                'تاريخ الانتهاء',
                'اسم الملف',
                'حجم الملف (KB)',
                'نوع الملف',
                'الحالة',
                'الوصف',
                'العلامات',
                'تاريخ الإضافة'
            ]);

            // Data rows
            foreach ($documents as $document) {
                fputcsv($handle, [
                    $document->document_title,
                    $this->getDocumentTypeLabel($document->document_type),
                    $document->document_number,
                    $document->issuing_authority,
                    $document->issue_date,
                    $document->expiry_date,
                    $document->file_name,
                    round($document->file_size / 1024, 2),
                    strtoupper($document->file_type),
                    $this->getStatusLabel($document->status),
                    $document->description,
                    $document->tags,
                    $document->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    /**
     * Archive management
     */
    public function showArchive()
    {
        $archivedDocuments = RegulatoryDocument::where('tenant_id', Auth::user()->tenant_id)
            ->where('status', 'archived')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('tenant.regulatory.documents.archive', compact('archivedDocuments'));
    }

    /**
     * Archive a document
     */
    public function archive($id)
    {
        $document = RegulatoryDocument::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        $document->update(['status' => 'archived']);

        return back()->with('success', 'تم أرشفة الوثيقة بنجاح');
    }

    /**
     * Restore from archive
     */
    public function restore($id)
    {
        $document = RegulatoryDocument::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        $document->update(['status' => 'active']);

        return back()->with('success', 'تم استعادة الوثيقة من الأرشيف بنجاح');
    }

    /**
     * Download all documents as ZIP
     */
    public function downloadAll()
    {
        $documents = RegulatoryDocument::where('tenant_id', Auth::user()->tenant_id)
            ->where('status', 'active')
            ->get();

        if ($documents->isEmpty()) {
            return back()->with('error', 'لا توجد وثائق للتحميل');
        }

        $zipFileName = 'regulatory_documents_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($documents as $document) {
                $filePath = storage_path('app/public/' . $document->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $document->file_name);
                }
            }
            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'حدث خطأ أثناء إنشاء ملف ZIP');
    }

    /**
     * Helper methods
     */
    private function getDocumentTypeLabel($type)
    {
        $types = [
            'license' => 'ترخيص',
            'certificate' => 'شهادة',
            'policy' => 'سياسة',
            'procedure' => 'إجراء',
            'legal' => 'قانوني',
            'compliance' => 'امتثال'
        ];
        
        return $types[$type] ?? $type;
    }

    private function getStatusLabel($status)
    {
        $statuses = [
            'active' => 'نشط',
            'archived' => 'مؤرشف',
            'expired' => 'منتهي الصلاحية'
        ];
        
        return $statuses[$status] ?? $status;
    }
}
