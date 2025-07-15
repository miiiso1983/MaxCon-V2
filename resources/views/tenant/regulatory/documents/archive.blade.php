@extends('layouts.modern')

@section('title', 'أرشيف الوثائق التنظيمية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-archive"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">أرشيف الوثائق التنظيمية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إدارة الوثائق المؤرشفة والمنتهية الصلاحية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.documents.index') }}" style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للوثائق النشطة
                </a>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div style="background: rgba(72, 187, 120, 0.1); border: 2px solid #48bb78; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle" style="color: #48bb78; font-size: 20px;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid #f56565; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-times-circle" style="color: #f56565; font-size: 20px;"></i>
                <strong>{{ session('error') }}</strong>
            </div>
        </div>
    @endif

    <!-- Archive Content -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        
        <!-- Statistics -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%); color: white; border-radius: 15px; padding: 20px; text-align: center;">
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">{{ $archivedDocuments->total() }}</div>
                <div style="font-size: 14px; opacity: 0.9;">إجمالي الوثائق المؤرشفة</div>
            </div>
            
            <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; border-radius: 15px; padding: 20px; text-align: center;">
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">
                    {{ \App\Models\Tenant\Regulatory\RegulatoryDocument::where('tenant_id', Auth::user()->tenant_id)->where('expiry_date', '<', now())->count() }}
                </div>
                <div style="font-size: 14px; opacity: 0.9;">وثائق منتهية الصلاحية</div>
            </div>
        </div>

        @if($archivedDocuments->count() > 0)
            <!-- Documents List -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-list" style="margin-left: 10px; color: #4ecdc4;"></i>
                    الوثائق المؤرشفة
                </h3>
                
                <div style="overflow-x: auto; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                    <table style="width: 100%; border-collapse: collapse; background: white;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white;">
                                <th style="padding: 15px; text-align: right; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.2);">عنوان الوثيقة</th>
                                <th style="padding: 15px; text-align: center; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.2);">النوع</th>
                                <th style="padding: 15px; text-align: center; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.2);">الجهة المصدرة</th>
                                <th style="padding: 15px; text-align: center; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.2);">تاريخ الأرشفة</th>
                                <th style="padding: 15px; text-align: center; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.2);">الحالة</th>
                                <th style="padding: 15px; text-align: center; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.2);">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedDocuments as $document)
                                <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s;" 
                                    onmouseover="this.style.backgroundColor='#f7fafc'" 
                                    onmouseout="this.style.backgroundColor='white'">
                                    
                                    <td style="padding: 15px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <i class="{{ $document->getFileIcon() }}" style="color: {{ $document->getFileIconColor() }}; font-size: 20px;"></i>
                                            <div>
                                                <div style="font-weight: 600; color: #2d3748;">{{ $document->document_title }}</div>
                                                @if($document->document_number)
                                                    <div style="font-size: 12px; color: #718096;">رقم: {{ $document->document_number }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td style="padding: 15px; text-align: center;">
                                        <span style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            {{ $document->getDocumentTypeLabel() }}
                                        </span>
                                    </td>
                                    
                                    <td style="padding: 15px; text-align: center; color: #4a5568;">
                                        {{ $document->issuing_authority }}
                                    </td>
                                    
                                    <td style="padding: 15px; text-align: center; color: #4a5568;">
                                        {{ $document->updated_at->format('Y-m-d') }}
                                    </td>
                                    
                                    <td style="padding: 15px; text-align: center;">
                                        @if($document->isExpired())
                                            <span style="background: rgba(245, 101, 101, 0.1); color: #f56565; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                <i class="fas fa-exclamation-triangle" style="margin-left: 5px;"></i>
                                                منتهي الصلاحية
                                            </span>
                                        @else
                                            <span style="background: rgba(113, 128, 150, 0.1); color: #718096; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                <i class="fas fa-archive" style="margin-left: 5px;"></i>
                                                مؤرشف
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td style="padding: 15px; text-align: center;">
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <!-- Download Button -->
                                            @if($document->fileExists())
                                                <a href="{{ $document->getDownloadUrl() }}" 
                                                   style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; display: flex; align-items: center; gap: 5px;"
                                                   title="تحميل الوثيقة">
                                                    <i class="fas fa-download"></i>
                                                    تحميل
                                                </a>
                                            @endif
                                            
                                            <!-- Restore Button -->
                                            <form action="{{ route('tenant.inventory.regulatory.documents.restore', $document->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" 
                                                        style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 8px 12px; border: none; border-radius: 8px; cursor: pointer; font-size: 12px; display: flex; align-items: center; gap: 5px;"
                                                        title="استعادة من الأرشيف"
                                                        onclick="return confirm('هل أنت متأكد من استعادة هذه الوثيقة من الأرشيف؟')">
                                                    <i class="fas fa-undo"></i>
                                                    استعادة
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($archivedDocuments->hasPages())
                    <div style="margin-top: 20px; display: flex; justify-content: center;">
                        {{ $archivedDocuments->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div style="text-align: center; padding: 60px 20px; color: #718096;">
                <div style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;">
                    <i class="fas fa-archive"></i>
                </div>
                <h3 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 700; color: #4a5568;">
                    لا توجد وثائق مؤرشفة
                </h3>
                <p style="margin: 0; font-size: 16px;">
                    لم يتم أرشفة أي وثائق بعد. الوثائق المؤرشفة ستظهر هنا.
                </p>
                <div style="margin-top: 30px;">
                    <a href="{{ route('tenant.inventory.regulatory.documents.index') }}" 
                       style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 15px 30px; border-radius: 15px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 10px;">
                        <i class="fas fa-arrow-right"></i>
                        العودة للوثائق النشطة
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Pagination Styling */
.pagination {
    display: flex;
    justify-content: center;
    gap: 5px;
}

.pagination .page-item .page-link {
    background: rgba(78, 205, 196, 0.1);
    border: 2px solid #4ecdc4;
    color: #4ecdc4;
    padding: 10px 15px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    color: white;
    border-color: #4ecdc4;
}

.pagination .page-item .page-link:hover {
    background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    color: white;
    transform: translateY(-2px);
}

.pagination .page-item.disabled .page-link {
    background: #f7fafc;
    border-color: #e2e8f0;
    color: #a0aec0;
    cursor: not-allowed;
}
</style>

@endsection
