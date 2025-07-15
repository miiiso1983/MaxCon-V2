@extends('layouts.modern')

@section('title', 'تجديد الشهادات')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">تجديد الشهادات</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إدارة وتجديد الشهادات المنتهية الصلاحية أو القريبة من الانتهاء</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.certificates.create') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-plus"></i>
                    شهادة جديدة
                </a>
                <a href="{{ route('tenant.inventory.regulatory.certificates.index') }}" style="background: rgba(255,255,255,0.2); color: #4ecdc4; padding: 15px 25px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للشهادات
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <!-- Expired Certificates -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-times-circle"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 700;">
                {{ $expiringCertificates->where('certificate_status', 'expired')->count() }}
            </h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">شهادات منتهية الصلاحية</p>
        </div>

        <!-- Expiring Soon -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 700;">
                {{ $expiringCertificates->where('certificate_status', '!=', 'expired')->count() }}
            </h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">شهادات تحتاج تجديد</p>
        </div>

        <!-- Total Certificates -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-certificate"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 700;">
                {{ $expiringCertificates->count() }}
            </h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجمالي الشهادات</p>
        </div>

        <!-- Renewal Actions -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;"
             onclick="renewAllExpired()"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-magic"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">تجديد جماعي</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">تجديد جميع الشهادات</p>
        </div>
    </div>

    <!-- Certificates List -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
            <i class="fas fa-list" style="margin-left: 10px; color: #4ecdc4;"></i>
            الشهادات التي تحتاج تجديد
        </h3>

        @if($expiringCertificates->isEmpty())
            <!-- No Certificates -->
            <div style="text-align: center; padding: 60px 20px;">
                <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 700;">ممتاز!</h3>
                <p style="color: #718096; margin: 0; font-size: 16px;">جميع الشهادات سارية المفعول ولا تحتاج تجديد حالياً</p>
                <a href="{{ route('tenant.inventory.regulatory.certificates.create') }}" style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 15px 30px; border: none; border-radius: 15px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; margin-top: 20px;">
                    <i class="fas fa-plus"></i>
                    إضافة شهادة جديدة
                </a>
            </div>
        @else
            <!-- Certificates Table -->
            <div style="overflow-x: auto; border-radius: 15px; border: 1px solid #e2e8f0;">
                <table style="width: 100%; border-collapse: collapse; background: white;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white;">
                            <th style="padding: 15px; text-align: right; font-weight: 700;">اسم الشهادة</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700;">النوع</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700;">تاريخ الانتهاء</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700;">الحالة</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700;">الأولوية</th>
                            <th style="padding: 15px; text-align: center; font-weight: 700;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expiringCertificates as $certificate)
                            <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s;" 
                                onmouseover="this.style.backgroundColor='#f7fafc'" 
                                onmouseout="this.style.backgroundColor='white'">
                                
                                <td style="padding: 15px; color: #2d3748; font-weight: 600;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="background: {{ method_exists($certificate, 'getCertificateTypeColor') ? $certificate->getCertificateTypeColor() : '#4ecdc4' }}; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                            <i class="fas fa-certificate"></i>
                                        </div>
                                        <div>
                                            <div>{{ $certificate->certificate_name }}</div>
                                            <div style="font-size: 12px; color: #718096;">{{ $certificate->certificate_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td style="padding: 15px; color: #4a5568;">
                                    <span style="background: {{ method_exists($certificate, 'getCertificateTypeColor') ? $certificate->getCertificateTypeColor() : '#4ecdc4' }}; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ method_exists($certificate, 'getCertificateTypeLabel') ? $certificate->getCertificateTypeLabel() : ucfirst($certificate->certificate_type) }}
                                    </span>
                                </td>
                                
                                <td style="padding: 15px; color: #4a5568;">
                                    <div>{{ $certificate->expiry_date ? $certificate->expiry_date->format('Y-m-d') : 'غير محدد' }}</div>
                                    @if($certificate->expiry_date)
                                        <div style="font-size: 12px; color: {{ (method_exists($certificate, 'isExpired') ? $certificate->isExpired() : (isset($certificate->expiry_date) && $certificate->expiry_date < now())) ? '#f56565' : '#ed8936' }};">
                                            @if(method_exists($certificate, 'isExpired') ? $certificate->isExpired() : (isset($certificate->expiry_date) && $certificate->expiry_date < now()))
                                                منتهي منذ {{ method_exists($certificate, 'getDaysUntilExpiry') ? abs($certificate->getDaysUntilExpiry()) : abs(now()->diffInDays($certificate->expiry_date)) }} يوم
                                            @else
                                                باقي {{ method_exists($certificate, 'getDaysUntilExpiry') ? $certificate->getDaysUntilExpiry() : now()->diffInDays($certificate->expiry_date) }} يوم
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                
                                <td style="padding: 15px;">
                                    <span style="background: {{ method_exists($certificate, 'getCertificateStatusColor') ? $certificate->getCertificateStatusColor() : '#48bb78' }}; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                        {{ method_exists($certificate, 'getCertificateStatusLabel') ? $certificate->getCertificateStatusLabel() : ucfirst($certificate->certificate_status) }}
                                    </span>
                                </td>
                                
                                <td style="padding: 15px;">
                                    @php
                                        $isExpired = method_exists($certificate, 'isExpired') ? $certificate->isExpired() : (isset($certificate->expiry_date) && $certificate->expiry_date < now());
                                        $daysUntilExpiry = method_exists($certificate, 'getDaysUntilExpiry') ? $certificate->getDaysUntilExpiry() : (isset($certificate->expiry_date) ? now()->diffInDays($certificate->expiry_date, false) : 0);
                                    @endphp

                                    @if($isExpired)
                                        <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-exclamation-circle" style="margin-left: 4px;"></i>
                                            عاجل
                                        </span>
                                    @elseif($daysUntilExpiry <= 30)
                                        <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-clock" style="margin-left: 4px;"></i>
                                            عالي
                                        </span>
                                    @else
                                        <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-check" style="margin-left: 4px;"></i>
                                            عادي
                                        </span>
                                    @endif
                                </td>
                                
                                <td style="padding: 15px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <button onclick="renewCertificate('{{ $certificate->id }}')" 
                                                style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: transform 0.2s;"
                                                onmouseover="this.style.transform='scale(1.05)'" 
                                                onmouseout="this.style.transform='scale(1)'">
                                            <i class="fas fa-sync-alt" style="margin-left: 4px;"></i>
                                            تجديد
                                        </button>
                                        
                                        <button onclick="viewCertificate('{{ $certificate->id }}')" 
                                                style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 8px 12px; border: 1px solid #4ecdc4; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: transform 0.2s;"
                                                onmouseover="this.style.transform='scale(1.05)'" 
                                                onmouseout="this.style.transform='scale(1)'">
                                            <i class="fas fa-eye" style="margin-left: 4px;"></i>
                                            عرض
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Renewal Modal -->
<div id="renewalModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; backdrop-filter: blur(5px);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;">
                <i class="fas fa-sync-alt" style="margin-left: 10px; color: #4ecdc4;"></i>
                تجديد الشهادة
            </h3>
            <button onclick="closeRenewalModal()" style="background: none; border: none; font-size: 24px; color: #718096; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="renewalForm" onsubmit="submitRenewal(event)">
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-calendar-plus" style="margin-left: 8px; color: #4ecdc4;"></i>
                    تاريخ الإصدار الجديد *
                </label>
                <input type="date" name="new_issue_date" required 
                       style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-calendar-times" style="margin-left: 8px; color: #4ecdc4;"></i>
                    تاريخ الانتهاء الجديد *
                </label>
                <input type="date" name="new_expiry_date" required 
                       style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-hashtag" style="margin-left: 8px; color: #4ecdc4;"></i>
                    رقم الشهادة الجديد
                </label>
                <input type="text" name="new_certificate_number" 
                       style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;"
                       placeholder="اتركه فارغاً للاحتفاظ بالرقم الحالي">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-sticky-note" style="margin-left: 8px; color: #4ecdc4;"></i>
                    ملاحظات التجديد
                </label>
                <textarea name="renewal_notes" rows="3"
                          style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; resize: vertical;"
                          placeholder="أي ملاحظات حول عملية التجديد..."></textarea>
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: center;">
                <button type="submit" 
                        style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-sync-alt" style="margin-left: 8px;"></i>
                    تجديد الشهادة
                </button>
                <button type="button" onclick="closeRenewalModal()" 
                        style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-times" style="margin-left: 8px;"></i>
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentCertificateId = null;

function renewCertificate(certificateId) {
    currentCertificateId = certificateId;
    
    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    const nextYear = new Date();
    nextYear.setFullYear(nextYear.getFullYear() + 3);
    const expiryDate = nextYear.toISOString().split('T')[0];
    
    document.querySelector('input[name="new_issue_date"]').value = today;
    document.querySelector('input[name="new_expiry_date"]').value = expiryDate;
    
    document.getElementById('renewalModal').style.display = 'block';
}

function renewAllExpired() {
    if (confirm('هل أنت متأكد من تجديد جميع الشهادات المنتهية الصلاحية؟')) {
        alert('سيتم تجديد جميع الشهادات المنتهية الصلاحية...\nهذه الميزة قيد التطوير');
    }
}

function viewCertificate(certificateId) {
    window.location.href = '{{ route("tenant.inventory.regulatory.certificates.index") }}';
}

function closeRenewalModal() {
    document.getElementById('renewalModal').style.display = 'none';
    currentCertificateId = null;
    document.getElementById('renewalForm').reset();
}

function submitRenewal(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    // Validate dates
    if (new Date(data.new_expiry_date) <= new Date(data.new_issue_date)) {
        alert('تاريخ الانتهاء يجب أن يكون بعد تاريخ الإصدار');
        return;
    }
    
    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التجديد...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        alert('تم تجديد الشهادة بنجاح!\nسيتم إعادة توجيهك إلى قائمة الشهادات');
        window.location.reload();
    }, 2000);
}

// Close modal when clicking outside
document.getElementById('renewalModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRenewalModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRenewalModal();
    }
});
</script>

@endsection
