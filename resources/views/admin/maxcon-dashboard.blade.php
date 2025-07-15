@extends('layouts.modern')

@section('page-title', 'لوحة التحكم الرئيسية')
@section('page-description', 'نظرة شاملة على أداء النظام والإحصائيات')

@section('content')
<!-- Welcome Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-crown" style="font-size: 32px; color: #ffd700;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            مرحباً {{ auth()->user()->name }} 👋
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة النظام الرئيسية - MaxCon Master
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-shield-alt" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">مدير عام</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">آخر دخول: {{ now()->format('Y-m-d H:i') }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">النظام يعمل بشكل طبيعي</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <!-- إجمالي المستأجرين -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-building" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ \App\Models\Tenant::count() }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">+{{ \App\Models\Tenant::whereDate('created_at', today())->count() }} اليوم</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">إجمالي المستأجرين</div>
            <div style="font-size: 14px; opacity: 0.9;">جميع المؤسسات المسجلة</div>
        </div>
    </div>

    <!-- المستأجرين النشطين -->
    <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(72, 187, 120, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ \App\Models\Tenant::where('is_active', true)->count() }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">من الإجمالي</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">المستأجرين النشطين</div>
            <div style="font-size: 14px; opacity: 0.9;">يستخدمون النظام حالياً</div>
        </div>
    </div>

    <!-- إجمالي المستخدمين -->
    <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(66, 153, 225, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-users" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ \App\Models\User::count() }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">في جميع المؤسسات</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">إجمالي المستخدمين</div>
            <div style="font-size: 14px; opacity: 0.9;">المسجلين في النظام</div>
        </div>
    </div>

    <!-- صحة النظام -->
    <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(237, 137, 54, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-heartbeat" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">99.9%</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">وقت التشغيل</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">صحة النظام</div>
            <div style="font-size: 14px; opacity: 0.9;">جميع الخدمات تعمل</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <!-- إجراءات سريعة -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-bolt" style="color: #667eea; margin-left: 10px;"></i>
            إجراءات سريعة
        </h3>
        <div style="display: grid; gap: 15px;">
            <a href="{{ route('admin.tenants.create') }}" style="display: flex; align-items: center; padding: 15px; background: #f7fafc; border-radius: 12px; text-decoration: none; color: #2d3748; transition: all 0.3s ease;">
                <div style="background: #667eea; color: white; border-radius: 10px; padding: 10px; margin-left: 15px;">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <div style="font-weight: 600; margin-bottom: 2px;">إضافة مستأجر جديد</div>
                    <div style="font-size: 14px; color: #718096;">إنشاء مؤسسة جديدة</div>
                </div>
            </a>
            
            <a href="{{ route('admin.users.index') }}" style="display: flex; align-items: center; padding: 15px; background: #f7fafc; border-radius: 12px; text-decoration: none; color: #2d3748; transition: all 0.3s ease;">
                <div style="background: #4299e1; color: white; border-radius: 10px; padding: 10px; margin-left: 15px;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div style="font-weight: 600; margin-bottom: 2px;">إدارة المستخدمين</div>
                    <div style="font-size: 14px; color: #718096;">عرض جميع المستخدمين</div>
                </div>
            </a>
            
            <a href="{{ route('admin.tenants.maxcon') }}" style="display: flex; align-items: center; padding: 15px; background: #f7fafc; border-radius: 12px; text-decoration: none; color: #2d3748; transition: all 0.3s ease;">
                <div style="background: #4299e1; color: white; border-radius: 10px; padding: 10px; margin-left: 15px;">
                    <i class="fas fa-list"></i>
                </div>
                <div>
                    <div style="font-weight: 600; margin-bottom: 2px;">عرض جميع المستأجرين</div>
                    <div style="font-size: 14px; color: #718096;">إدارة المؤسسات</div>
                </div>
            </a>
        </div>
    </div>

    <!-- آخر النشاطات -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-clock" style="color: #ed8936; margin-left: 10px;"></i>
            آخر النشاطات
        </h3>
        <div style="display: grid; gap: 15px;">
            <div style="display: flex; align-items: center; padding: 15px; background: #f7fafc; border-radius: 12px;">
                <div style="background: #48bb78; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-user-plus" style="font-size: 14px;"></i>
                </div>
                <div>
                    <div style="font-weight: 600; margin-bottom: 2px;">مستخدم جديد انضم</div>
                    <div style="font-size: 14px; color: #718096;">منذ 5 دقائق</div>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; padding: 15px; background: #f7fafc; border-radius: 12px;">
                <div style="background: #667eea; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-building" style="font-size: 14px;"></i>
                </div>
                <div>
                    <div style="font-weight: 600; margin-bottom: 2px;">مؤسسة جديدة مسجلة</div>
                    <div style="font-size: 14px; color: #718096;">منذ ساعة</div>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; padding: 15px; background: #f7fafc; border-radius: 12px;">
                <div style="background: #4299e1; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-sync" style="font-size: 14px;"></i>
                </div>
                <div>
                    <div style="font-weight: 600; margin-bottom: 2px;">تحديث النظام</div>
                    <div style="font-size: 14px; color: #718096;">منذ 3 ساعات</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="content-card" style="margin-bottom: 30px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-server" style="color: #4299e1; margin-left: 10px;"></i>
        حالة النظام
    </h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <!-- قاعدة البيانات -->
        <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 12px;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                <i class="fas fa-database"></i>
            </div>
            <div style="font-weight: 600; margin-bottom: 5px;">قاعدة البيانات</div>
            <div style="color: #48bb78; font-size: 14px;">متصلة</div>
        </div>

        <!-- الخادم -->
        <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 12px;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                <i class="fas fa-server"></i>
            </div>
            <div style="font-weight: 600; margin-bottom: 5px;">الخادم</div>
            <div style="color: #48bb78; font-size: 14px;">يعمل بشكل طبيعي</div>
        </div>

        <!-- التخزين -->
        <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 12px;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                <i class="fas fa-hdd"></i>
            </div>
            <div style="font-weight: 600; margin-bottom: 5px;">التخزين</div>
            <div style="color: #ed8936; font-size: 14px;">75% مستخدم</div>
        </div>

        <!-- الذاكرة -->
        <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 12px;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                <i class="fas fa-memory"></i>
            </div>
            <div style="font-weight: 600; margin-bottom: 5px;">الذاكرة</div>
            <div style="color: #4299e1; font-size: 14px;">60% مستخدمة</div>
        </div>
    </div>
</div>

<!-- Recent Tenants -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center;">
            <i class="fas fa-building" style="color: #667eea; margin-left: 10px;"></i>
            آخر المستأجرين المسجلين
        </div>
        <a href="{{ route('admin.tenants.maxcon') }}" style="color: #667eea; text-decoration: none; font-size: 14px;">
            عرض الكل <i class="fas fa-arrow-left" style="margin-right: 5px;"></i>
        </a>
    </h3>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc;">
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المؤسسة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">النوع</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الحالة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">تاريخ التسجيل</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; margin-left: 10px; font-weight: 600;">
                                M
                            </div>
                            <div>
                                <div style="font-weight: 600;">صيدلية مصطفى</div>
                                <div style="font-size: 14px; color: #718096;">mustafa@pharmacy.com</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #2d3748;">صيدلية</td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span class="status-badge status-active">نشط</span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #718096;">{{ now()->subDays(2)->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #48bb78; color: white; display: flex; align-items: center; justify-content: center; margin-left: 10px; font-weight: 600;">
                                T
                            </div>
                            <div>
                                <div style="font-weight: 600;">صيدلية الاختبار</div>
                                <div style="font-size: 14px; color: #718096;">test@pharmacy.com</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #2d3748;">صيدلية</td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span class="status-badge status-inactive">معطل</span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #718096;">{{ now()->subDays(5)->format('Y-m-d') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
// Add hover effects to quick action cards
document.addEventListener('DOMContentLoaded', function() {
    const actionCards = document.querySelectorAll('a[style*="background: #f7fafc"]');

    actionCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.background = '#edf2f7';
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.background = '#f7fafc';
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Add pulse animation to status indicators
    const statusIndicators = document.querySelectorAll('.status-badge');
    statusIndicators.forEach(indicator => {
        if (indicator.classList.contains('status-active')) {
            indicator.style.animation = 'pulse 2s infinite';
        }
    });
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .content-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .content-card:nth-child(2) { animation-delay: 0.1s; }
    .content-card:nth-child(3) { animation-delay: 0.2s; }
    .content-card:nth-child(4) { animation-delay: 0.3s; }
`;
document.head.appendChild(style);
</script>
@endpush

@endsection
