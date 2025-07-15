@extends('layouts.modern')

@section('page-title', 'إدارة المؤسسات')
@section('page-description', 'إدارة جميع المؤسسات في النظام')

@section('content')
<!-- Page Header -->
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
                            إدارة المؤسسات 🏢
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة جميع المؤسسات في النظام
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-crown" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">مدير النظام</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-building" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $statistics['total'] ?? 0 }} مؤسسة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">{{ $statistics['active'] ?? 0 }} نشطة</span>
                    </div>
                </div>
            </div>

            <div>
                <a href="{{ route('admin.tenants.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-plus"></i>
                    إضافة مؤسسة جديدة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <!-- إجمالي المؤسسات -->
    <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(66, 153, 225, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-building" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $statistics['total'] ?? 0 }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">مؤسسة</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">إجمالي المؤسسات</div>
            <div style="font-size: 14px; opacity: 0.9;">في النظام</div>
        </div>
    </div>

    <!-- المؤسسات النشطة -->
    <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(72, 187, 120, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $statistics['active'] ?? 0 }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">نشطة</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">المؤسسات النشطة</div>
            <div style="font-size: 14px; opacity: 0.9;">تعمل بكامل طاقتها</div>
        </div>
    </div>

    <!-- المؤسسات المعلقة -->
    <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(245, 101, 101, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-pause-circle" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $statistics['suspended'] ?? 0 }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">معلقة</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">المؤسسات المعلقة</div>
            <div style="font-size: 14px; opacity: 0.9;">تحتاج مراجعة</div>
        </div>
    </div>

    <!-- في فترة التجربة -->
    <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(237, 137, 54, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-clock" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $statistics['on_trial'] ?? 0 }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">تجربة</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">في فترة التجربة</div>
            <div style="font-size: 14px; opacity: 0.9;">فترة تقييم</div>
        </div>
    </div>
</div>

<!-- Search Section -->
<div class="content-card" style="margin-bottom: 30px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-search" style="color: #667eea; margin-left: 10px;"></i>
        البحث والفلترة
    </h3>

    <form method="GET" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">البحث في المؤسسات</label>
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="ابحث بالاسم، الرمز، أو النطاق..."
                   style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; transition: all 0.3s ease;"
                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.15)';"
                   onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
        </div>

        <button type="submit" class="btn-blue" style="padding: 12px 20px;">
            <i class="fas fa-search"></i>
            بحث
        </button>

        @if($search)
            <a href="{{ route('admin.tenants.index') }}" style="background: #f7fafc; color: #4a5568; padding: 12px 20px; border: 2px solid #e2e8f0; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;"
               onmouseover="this.style.background='#edf2f7'; this.style.borderColor='#cbd5e0';"
               onmouseout="this.style.background='#f7fafc'; this.style.borderColor='#e2e8f0';">
                <i class="fas fa-times"></i>
                مسح
            </a>
        @endif
    </form>
</div>

<!-- Tenants Table -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-table" style="color: #48bb78; margin-left: 10px;"></i>
        قائمة المؤسسات
    </h3>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc;">
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المؤسسة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الخطة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الحالة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المستخدمون</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">تاريخ الإنشاء</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                <tr style="transition: all 0.3s ease;" onmouseover="this.style.background='#f7fafc';" onmouseout="this.style.background='white';">
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px; font-weight: 700; font-size: 18px;">
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
                        @if($tenant->plan === 'enterprise')
                            <span style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">مؤسسي</span>
                        @elseif($tenant->plan === 'premium')
                            <span style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">متقدم</span>
                        @else
                            <span style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">أساسي</span>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        @if($tenant->status === 'active')
                            <span class="status-badge status-active">نشط</span>
                        @elseif($tenant->status === 'suspended')
                            <span class="status-badge status-inactive">معلق</span>
                        @else
                            <span class="status-badge status-pending">غير نشط</span>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #4a5568;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-users" style="color: #667eea; font-size: 14px;"></i>
                            <span style="font-weight: 600;">{{ $tenant->users_count ?? 0 }}</span>
                            <span style="color: #718096;">/</span>
                            <span style="color: #718096;">{{ $tenant->max_users }}</span>
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #718096;">
                        {{ $tenant->created_at->format('Y/m/d') }}
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a href="{{ route('admin.tenants.show', $tenant) }}"
                               style="color: #4299e1; text-decoration: none; padding: 4px;"
                               title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.tenants.edit', $tenant) }}"
                               style="color: #667eea; text-decoration: none; padding: 4px;"
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($tenant->status === 'active')
                                <form method="POST" action="{{ route('admin.tenants.suspend', $tenant) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit"
                                            style="background: none; border: none; color: #ed8936; cursor: pointer; padding: 4px;"
                                            title="تعليق"
                                            onclick="return confirm('هل أنت متأكد من تعليق هذه المؤسسة؟')">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.tenants.activate', $tenant) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit"
                                            style="background: none; border: none; color: #48bb78; cursor: pointer; padding: 4px;"
                                            title="تفعيل"
                                            onclick="return confirm('هل أنت متأكد من تفعيل هذه المؤسسة؟')">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: #718096;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <i class="fas fa-building" style="font-size: 48px; color: #e2e8f0; margin-bottom: 15px;"></i>
                            <p style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">لا توجد مؤسسات</p>
                            <p style="font-size: 14px; margin: 0;">ابدأ بإضافة مؤسسة جديدة</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($tenants, 'links'))
    <div style="margin-top: 20px;">
        {{ $tenants->links() }}
    </div>
    @endif
</div>
@endsection
