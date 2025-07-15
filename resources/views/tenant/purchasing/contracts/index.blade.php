@extends('layouts.modern')

@section('page-title', 'العقود والاتفاقيات')
@section('page-description', 'إدارة عقود الموردين والاتفاقيات التجارية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-contract" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            العقود والاتفاقيات 📄
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة عقود الموردين والاتفاقيات التجارية
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar-alt" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">تتبع المواعيد</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-bell" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تنبيهات التجديد</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-bar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">متابعة الأداء</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.contracts.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    عقد جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-list" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي العقود</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['total']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عقد</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">نشط</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['active']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عقد نشط</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-times-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">منتهي الصلاحية</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['expired']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عقد منتهي</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">ينتهي قريباً</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['expiring_soon']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عقد</div>
        </div>
    </div>
</div>

<!-- Coming Soon Message -->
<div class="content-card">
    <div style="text-align: center; padding: 60px 40px; color: #6b7280;">
        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
            <i class="fas fa-file-contract"></i>
        </div>
        <h2 style="margin: 0 0 15px 0; color: #2d3748; font-size: 28px; font-weight: 700;">العقود والاتفاقيات</h2>
        <p style="margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
            نحن نعمل على تطوير نظام متطور لإدارة العقود والاتفاقيات يتضمن:
        </p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0; max-width: 800px; margin-left: auto; margin-right: auto;">
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border-right: 4px solid #8b5cf6;">
                <i class="fas fa-file-signature" style="color: #8b5cf6; font-size: 24px; margin-bottom: 10px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #2d3748;">إنشاء العقود</h4>
                <p style="margin: 0; font-size: 14px; color: #6b7280;">قوالب عقود احترافية مع جميع البنود</p>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border-right: 4px solid #10b981;">
                <i class="fas fa-calendar-check" style="color: #10b981; font-size: 24px; margin-bottom: 10px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #2d3748;">تتبع المواعيد</h4>
                <p style="margin: 0; font-size: 14px; color: #6b7280;">متابعة تواريخ البداية والانتهاء</p>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border-right: 4px solid #f59e0b;">
                <i class="fas fa-bell" style="color: #f59e0b; font-size: 24px; margin-bottom: 10px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #2d3748;">تنبيهات التجديد</h4>
                <p style="margin: 0; font-size: 14px; color: #6b7280;">تنبيهات تلقائية قبل انتهاء العقود</p>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border-right: 4px solid #ef4444;">
                <i class="fas fa-chart-bar" style="color: #ef4444; font-size: 24px; margin-bottom: 10px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #2d3748;">متابعة الأداء</h4>
                <p style="margin: 0; font-size: 14px; color: #6b7280;">تقييم أداء الموردين مقابل العقود</p>
            </div>
        </div>
        
        <div style="background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); padding: 20px; border-radius: 12px; margin: 30px 0;">
            <h3 style="margin: 0 0 10px 0; color: #6b46c1; font-size: 20px;">🚀 قريباً جداً!</h3>
            <p style="margin: 0; color: #6b46c1; font-size: 16px;">
                سيتم إطلاق هذه الوحدة في التحديث القادم مع أدوات إدارة قانونية متقدمة
            </p>
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('tenant.purchasing.suppliers.index') }}" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-truck"></i>
                إدارة الموردين
            </a>
            <a href="{{ route('tenant.purchasing.purchase-requests.index') }}" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-alt"></i>
                طلبات الشراء
            </a>
            <a href="{{ route('tenant.dashboard') }}" style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-home"></i>
                العودة للرئيسية
            </a>
        </div>
    </div>
</div>
@endsection
