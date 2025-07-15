@extends('layouts.modern')

@section('page-title', 'عروض الأسعار')
@section('page-description', 'مقارنة عروض الأسعار واختيار الأفضل')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-quote-right" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            عروض الأسعار 💰
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            مقارنة عروض الأسعار واختيار الأفضل
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-balance-scale" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">مقارنة العروض</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-star" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تقييم العروض</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تحليل الأسعار</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.quotations.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    طلب عرض سعر
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-list" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي العروض</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['total']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عرض سعر</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-clock" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">في الانتظار</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['pending']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عرض معلق</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-inbox" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">مستلم</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['received']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عرض مستلم</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">مقبول</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['accepted']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عرض مقبول</div>
        </div>
    </div>
</div>

<!-- Coming Soon Message -->
<div class="content-card">
    <div style="text-align: center; padding: 60px 40px; color: #6b7280;">
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
            <i class="fas fa-quote-right"></i>
        </div>
        <h2 style="margin: 0 0 15px 0; color: #2d3748; font-size: 28px; font-weight: 700;">عروض الأسعار</h2>
        <p style="margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
            نحن نعمل على تطوير نظام متقدم لإدارة عروض الأسعار يتضمن:
        </p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0; max-width: 800px; margin-left: auto; margin-right: auto;">
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border-right: 4px solid #f59e0b;">
                <i class="fas fa-paper-plane" style="color: #f59e0b; font-size: 24px; margin-bottom: 10px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #2d3748;">طلب عروض الأسعار</h4>
                <p style="margin: 0; font-size: 14px; color: #6b7280;">إرسال طلبات لموردين متعددين</p>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border-right: 4px solid #3b82f6;">
                <i class="fas fa-balance-scale" style="color: #3b82f6; font-size: 24px; margin-bottom: 10px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #2d3748;">مقارنة العروض</h4>
                <p style="margin: 0; font-size: 14px; color: #6b7280;">مقارنة تفصيلية للأسعار والشروط</p>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border-right: 4px solid #10b981;">
                <i class="fas fa-star" style="color: #10b981; font-size: 24px; margin-bottom: 10px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #2d3748;">تقييم العروض</h4>
                <p style="margin: 0; font-size: 14px; color: #6b7280;">تقييم تقني وتجاري شامل</p>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border-right: 4px solid #8b5cf6;">
                <i class="fas fa-chart-line" style="color: #8b5cf6; font-size: 24px; margin-bottom: 10px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #2d3748;">تحليل الأسعار</h4>
                <p style="margin: 0; font-size: 14px; color: #6b7280;">تحليل اتجاهات الأسعار والتوفير</p>
            </div>
        </div>
        
        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 12px; margin: 30px 0;">
            <h3 style="margin: 0 0 10px 0; color: #92400e; font-size: 20px;">🚀 قريباً جداً!</h3>
            <p style="margin: 0; color: #92400e; font-size: 16px;">
                سيتم إطلاق هذه الوحدة في التحديث القادم مع أدوات مقارنة متقدمة
            </p>
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('tenant.purchasing.suppliers.index') }}" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
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
