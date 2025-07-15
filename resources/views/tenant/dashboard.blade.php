@extends('layouts.modern')

@section('page-title', 'لوحة تحكم المؤسسة')
@section('page-description', 'إدارة شاملة لمؤسستك ومستخدميها')

@section('content')
<!-- Welcome Header -->
<div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-building" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            مرحباً بك في لوحة تحكم المؤسسة 🏢
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة شاملة لمؤسستك ومستخدميها
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-user" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ auth()->user()->name }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-crown" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">مدير المؤسسة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">{{ now()->format('Y/m/d') }}</span>
                    </div>
                </div>
            </div>

            <div>
                <a href="{{ route('tenant.roles.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-user-shield"></i>
                    إدارة الأدوار والصلاحيات
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-bolt" style="color: #fbbf24; margin-left: 10px;"></i>
        الإجراءات السريعة
    </h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <!-- إدارة الأدوار والصلاحيات -->
        <a href="{{ route('tenant.roles.index') }}" style="text-decoration: none; color: inherit;">
            <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.borderColor='#667eea'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                        <i class="fas fa-user-shield" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #2d3748; margin: 0;">إدارة الأدوار والصلاحيات</h4>
                        <p style="font-size: 12px; color: #718096; margin: 2px 0 0 0;">تحكم في صلاحيات المستخدمين</p>
                    </div>
                </div>
                <p style="color: #4a5568; font-size: 14px; margin: 0;">إدارة شاملة لأدوار وصلاحيات جميع المستخدمين في المؤسسة</p>
            </div>
        </a>

        <!-- إدارة طلبات المبيعات -->
        <a href="{{ route('tenant.sales.orders.index') }}" style="text-decoration: none; color: inherit;">
            <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.borderColor='#4299e1'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                        <i class="fas fa-shopping-cart" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #2d3748; margin: 0;">إدارة طلبات المبيعات</h4>
                        <p style="font-size: 12px; color: #718096; margin: 2px 0 0 0;">إدارة ومتابعة الطلبات</p>
                    </div>
                </div>
                <p style="color: #4a5568; font-size: 14px; margin: 0;">إدارة شاملة لطلبات المبيعات ومتابعة حالتها من الإنشاء حتى التسليم</p>
            </div>
        </a>

        <!-- إدارة العملاء -->
        <a href="{{ route('tenant.sales.customers.index') }}" style="text-decoration: none; color: inherit;">
            <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.borderColor='#48bb78'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                        <i class="fas fa-users" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #2d3748; margin: 0;">إدارة العملاء</h4>
                        <p style="font-size: 12px; color: #718096; margin: 2px 0 0 0;">قاعدة بيانات العملاء</p>
                    </div>
                </div>
                <p style="color: #4a5568; font-size: 14px; margin: 0;">إدارة معلومات العملاء وتتبع المعاملات والأرصدة</p>
            </div>
        </a>

        <!-- إدارة المنتجات -->
        <a href="{{ route('tenant.sales.products.index') }}" style="text-decoration: none; color: inherit;">
            <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.borderColor='#9f7aea'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                        <i class="fas fa-pills" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #2d3748; margin: 0;">إدارة المنتجات</h4>
                        <p style="font-size: 12px; color: #718096; margin: 2px 0 0 0;">كتالوج المنتجات</p>
                    </div>
                </div>
                <p style="color: #4a5568; font-size: 14px; margin: 0;">إدارة كتالوج المنتجات والأسعار والمخزون</p>
            </div>
        </a>

        <!-- إدارة الفواتير -->
        <a href="{{ route('tenant.sales.invoices.index') }}" style="text-decoration: none; color: inherit;">
            <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.borderColor='#ed8936'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                        <i class="fas fa-file-invoice" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #2d3748; margin: 0;">إدارة الفواتير</h4>
                        <p style="font-size: 12px; color: #718096; margin: 2px 0 0 0;">فواتير متقدمة مع QR</p>
                    </div>
                </div>
                <p style="color: #4a5568; font-size: 14px; margin: 0;">إنشاء فواتير احترافية مع QR Code ودعم العملات المتعددة</p>
            </div>
        </a>
    </div>
</div>

<!-- Welcome Message -->
<div class="content-card">
    <div style="text-align: center; padding: 40px; color: #718096;">
        <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 40px;">
            <i class="fas fa-building"></i>
        </div>
        <h2 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0 0 10px 0;">مرحباً بك في نظام إدارة المؤسسة</h2>
        <p style="font-size: 16px; color: #4a5568; margin: 0 0 20px 0; max-width: 600px; margin-left: auto; margin-right: auto;">
            يمكنك الآن إدارة أدوار وصلاحيات المستخدمين في مؤسستك بسهولة وفعالية. ابدأ بالنقر على "إدارة الأدوار والصلاحيات" أعلاه.
        </p>

        <div style="display: flex; justify-content: center; gap: 15px; margin-top: 30px;">
            <a href="{{ route('tenant.roles.index') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.4)';"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.3)';">
                <i class="fas fa-user-shield"></i>
                إدارة الأدوار والصلاحيات
            </a>
        </div>
    </div>
</div>
@endsection