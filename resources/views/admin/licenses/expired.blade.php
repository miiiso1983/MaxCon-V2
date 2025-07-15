@extends('layouts.modern')

@section('page-title', 'التراخيص المنتهية')
@section('page-description', 'إدارة المؤسسات ذات التراخيص المنتهية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            التراخيص المنتهية ⚠️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            المؤسسات التي انتهت تراخيصها أو فترة التجربة
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">تراخيص منتهية</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-building" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $stats['total_expired'] ?? 0 }} مؤسسة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">{{ $stats['expiring_soon'] ?? 0 }} ستنتهي قريباً</span>
                    </div>
                </div>
            </div>
            
            <div>
                <a href="{{ route('admin.licenses.expiring-soon') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-clock"></i>
                    التراخيص المنتهية قريباً
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <!-- إجمالي المنتهية -->
    <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(245, 101, 101, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $stats['total_expired'] ?? 0 }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">منتهية</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">إجمالي المنتهية</div>
            <div style="font-size: 14px; opacity: 0.9;">تحتاج تجديد فوري</div>
        </div>
    </div>

    <!-- فترة التجربة المنتهية -->
    <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(237, 137, 54, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-clock" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $stats['trial_expired'] ?? 0 }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">تجربة</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">فترة التجربة المنتهية</div>
            <div style="font-size: 14px; opacity: 0.9;">تحتاج ترقية للخطة المدفوعة</div>
        </div>
    </div>

    <!-- التراخيص المنتهية -->
    <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(159, 122, 234, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-certificate" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $stats['license_expired'] ?? 0 }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">ترخيص</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">التراخيص المنتهية</div>
            <div style="font-size: 14px; opacity: 0.9;">تحتاج تجديد الترخيص</div>
        </div>
    </div>

    <!-- ستنتهي قريباً -->
    <div style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(251, 191, 36, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-hourglass-half" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $stats['expiring_soon'] ?? 0 }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">قريباً</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">ستنتهي قريباً</div>
            <div style="font-size: 14px; opacity: 0.9;">خلال 7 أيام القادمة</div>
        </div>
    </div>
</div>

<!-- Expired Tenants Table -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-table" style="color: #f56565; margin-left: 10px;"></i>
        المؤسسات ذات التراخيص المنتهية
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc;">
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المؤسسة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">نوع الانتهاء</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">تاريخ الانتهاء</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المستخدمون النشطون</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expiredTenants as $tenant)
                <tr style="transition: all 0.3s ease;" onmouseover="this.style.background='#f7fafc';" onmouseout="this.style.background='white';">
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px; font-weight: 700; font-size: 18px;">
                                {{ substr($tenant->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #2d3748; margin-bottom: 2px;">{{ $tenant->name }}</div>
                                <div style="font-size: 14px; color: #718096; margin-bottom: 2px;">{{ $tenant->slug }}</div>
                                @if($tenant->subdomain)
                                    <div style="font-size: 12px; color: #667eea; font-family: monospace;">{{ $tenant->subdomain }}.{{ config('app.central_domain') }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        @if($tenant->trial_ends_at && $tenant->trial_ends_at < now())
                            <span style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">فترة التجربة</span>
                        @elseif($tenant->license_expires_at && $tenant->license_expires_at < now())
                            <span style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">ترخيص مدفوع</span>
                        @else
                            <span style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">غير محدد</span>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #f56565; font-weight: 600;">
                        @if($tenant->trial_ends_at && $tenant->trial_ends_at < now())
                            {{ $tenant->trial_ends_at->format('Y/m/d') }}
                            <div style="font-size: 12px; color: #718096;">منذ {{ $tenant->trial_ends_at->diffForHumans() }}</div>
                        @elseif($tenant->license_expires_at && $tenant->license_expires_at < now())
                            {{ $tenant->license_expires_at->format('Y/m/d') }}
                            <div style="font-size: 12px; color: #718096;">منذ {{ $tenant->license_expires_at->diffForHumans() }}</div>
                        @else
                            غير محدد
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #4a5568;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-users" style="color: #667eea; font-size: 14px;"></i>
                            <span style="font-weight: 600;">{{ $tenant->users->count() }}</span>
                            <span style="color: #718096;">مستخدم نشط</span>
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a href="{{ route('admin.tenants.show', $tenant) }}" 
                               style="color: #4299e1; text-decoration: none; padding: 4px;" 
                               title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="showExtendModal({{ $tenant->id }}, '{{ $tenant->name }}')" 
                                    style="background: none; border: none; color: #48bb78; cursor: pointer; padding: 4px;" 
                                    title="تمديد الترخيص">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                            <a href="{{ route('admin.tenants.edit', $tenant) }}" 
                               style="color: #667eea; text-decoration: none; padding: 4px;" 
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 40px; text-align: center; color: #718096;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <i class="fas fa-check-circle" style="font-size: 48px; color: #48bb78; margin-bottom: 15px;"></i>
                            <p style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">لا توجد تراخيص منتهية</p>
                            <p style="font-size: 14px; margin: 0;">جميع المؤسسات لديها تراخيص سارية</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($expiredTenants->hasPages())
    <div style="margin-top: 20px;">
        {{ $expiredTenants->links() }}
    </div>
    @endif
</div>

<!-- Extend License Modal -->
<div id="extendModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0 0 20px 0; display: flex; align-items: center;">
            <i class="fas fa-plus-circle" style="color: #48bb78; margin-left: 10px;"></i>
            تمديد الترخيص
        </h3>
        
        <form id="extendForm" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المؤسسة</label>
                <div id="tenantName" style="padding: 12px; background: #f7fafc; border-radius: 8px; color: #2d3748; font-weight: 600;"></div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع الترخيص</label>
                <select name="license_type" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                    <option value="trial">فترة تجربة</option>
                    <option value="license">ترخيص مدفوع</option>
                </select>
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">عدد الأيام</label>
                <input type="number" name="extension_days" min="1" max="365" value="30" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <button type="button" onclick="closeExtendModal()" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; cursor: pointer;">
                    إلغاء
                </button>
                <button type="submit" class="btn-green" style="padding: 12px 24px;">
                    <i class="fas fa-plus-circle"></i>
                    تمديد الترخيص
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showExtendModal(tenantId, tenantName) {
    document.getElementById('tenantName').textContent = tenantName;
    document.getElementById('extendForm').action = `/admin/licenses/${tenantId}/extend`;
    document.getElementById('extendModal').style.display = 'flex';
}

function closeExtendModal() {
    document.getElementById('extendModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('extendModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExtendModal();
    }
});
</script>
@endpush
@endsection
