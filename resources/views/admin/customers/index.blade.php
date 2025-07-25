@extends('layouts.modern')

@section('page-title', "إدارة عملاء {$tenant->name}")
@section('page-description', 'عرض وإدارة عملاء المستأجر مع الحدود المسموحة')

@push('styles')
<style>
    .progress-bar-container {
        width: 100%;
        background: #e2e8f0;
        border-radius: 10px;
        height: 12px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .status-toggle-btn {
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s ease;
    }

    .btn-deactivate {
        background-color: #e53e3e;
    }

    .btn-deactivate:hover {
        background-color: #c53030;
        transform: translateY(-1px);
    }

    .btn-activate {
        background-color: #38a169;
    }

    .btn-activate:hover {
        background-color: #2f855a;
        transform: translateY(-1px);
    }

    /* Responsive improvements for mobile */
    @media (max-width: 767px) {
        .table-responsive {
            font-size: 14px;
            margin: 0 8px;
        }

        .table th,
        .table td {
            padding: 8px 4px;
            font-size: 12px;
        }

        .status-toggle-btn {
            padding: 8px 12px;
            font-size: 14px;
            min-height: 44px;
            min-width: 44px;
        }

        .btn {
            min-height: 44px;
            padding: 12px 16px;
            margin-bottom: 8px;
        }

        .card {
            margin: 8px;
            border-radius: 12px;
        }

        .card-header,
        .card-body {
            padding: 16px;
        }

        /* Hide some columns on mobile */
        .hidden-mobile {
            display: none !important;
        }

        /* Stack action buttons vertically on mobile */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .action-buttons form {
            width: 100%;
        }

        .action-buttons button {
            width: 100%;
            justify-content: center;
        }
    }

    /* Tablet improvements */
    @media (min-width: 768px) and (max-width: 1023px) {
        .table th,
        .table td {
            padding: 10px 8px;
            font-size: 14px;
        }

        .btn {
            padding: 10px 14px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply progress bar styles
    const progressBars = document.querySelectorAll('.progress-bar-fill');
    progressBars.forEach(bar => {
        const width = bar.getAttribute('data-width');
        const color = bar.getAttribute('data-color');
        bar.style.width = width + '%';
        bar.style.backgroundColor = color;
    });
});
</script>
@endpush

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 25px;">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: 800; backdrop-filter: blur(10px);">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div>
                    <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                        عملاء {{ $tenant->name }} 👥
                    </h1>
                    <p style="font-size: 18px; margin: 5px 0 15px 0; opacity: 0.9;">
                        إدارة العملاء والحدود المسموحة
                    </p>
                    
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-users" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px; font-weight: 600;">{{ $statistics['total_customers'] }} عميل</span>
                        </div>
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px;">{{ number_format($statistics['usage_percentage'], 1) }}% مستخدم</span>
                        </div>
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-clock" style="margin-left: 8px; color: #4ade80;"></i>
                            <span style="font-size: 14px;">{{ $statistics['remaining_slots'] }} متبقي</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('admin.tenants.show', $tenant) }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 25px; text-decoration: none; font-weight: 600; backdrop-filter: blur(10px); transition: all 0.3s ease;">
                    <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                    العودة للمستأجر
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Customer Limits Management -->
<div class="content-card" style="margin-bottom: 30px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-cog" style="color: #667eea;"></i>
            إدارة حدود العملاء
        </h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <!-- Current Usage -->
        <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 15px;">
            <div style="font-size: 24px; font-weight: 700; color: #2d3748; margin-bottom: 5px;">
                {{ $statistics['total_customers'] }}
            </div>
            <div style="color: #718096; font-size: 14px;">العملاء الحاليين</div>
        </div>
        
        <!-- Max Allowed -->
        <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 15px;">
            <div style="font-size: 24px; font-weight: 700; color: #2d3748; margin-bottom: 5px;">
                {{ $statistics['max_customers'] }}
            </div>
            <div style="color: #718096; font-size: 14px;">الحد الأقصى</div>
        </div>
        
        <!-- Remaining -->
        <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 15px;">
            <div style="font-size: 24px; font-weight: 700; color: #38a169; margin-bottom: 5px;">
                {{ $statistics['remaining_slots'] }}
            </div>
            <div style="color: #718096; font-size: 14px;">المتبقي</div>
        </div>
        
        <!-- Usage Percentage -->
        <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 15px;">
            <div style="font-size: 24px; font-weight: 700; color: #ed8936; margin-bottom: 5px;">
                {{ number_format($statistics['usage_percentage'], 1) }}%
            </div>
            <div style="color: #718096; font-size: 14px;">نسبة الاستخدام</div>
        </div>
    </div>
    
    <!-- Progress Bar -->
    <div style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <span style="font-weight: 600; color: #4a5568;">استخدام العملاء</span>
            <span style="font-size: 14px; color: #718096;">{{ $statistics['total_customers'] }} / {{ $statistics['max_customers'] }}</span>
        </div>
        @php
            $progressColor = $statistics['usage_percentage'] >= 80 ? '#e53e3e' : ($statistics['usage_percentage'] >= 60 ? '#dd6b20' : '#38a169');
        @endphp
        <div class="progress-bar-container">
            <div class="progress-bar-fill"
                 data-width="{{ min(100, $statistics['usage_percentage']) }}"
                 data-color="{{ $progressColor }}"></div>
        </div>
    </div>
    
    <!-- Update Limits Form -->
    <form method="POST" action="{{ route('admin.customers.update-limits', $tenant) }}" style="display: flex; gap: 15px; align-items: end;">
        @csrf
        @method('PATCH')
        
        <div style="flex: 1;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">تحديث الحد الأقصى للعملاء</label>
            <input type="number" name="max_customers" value="{{ $tenant->max_customers }}" 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                   min="{{ $statistics['total_customers'] }}" required>
            @error('max_customers')
                <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" 
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
            <i class="fas fa-save" style="margin-left: 8px;"></i>
            تحديث
        </button>
    </form>
</div>

<!-- Search and Filters -->
<div class="content-card" style="margin-bottom: 20px;">
    <form method="GET" style="display: grid; grid-template-columns: 1fr 200px 150px; gap: 15px; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">البحث</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="البحث بالاسم، الإيميل، الكود، أو الهاتف..."
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الحالة</label>
            <select name="status" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                <option value="">جميع الحالات</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
            </select>
        </div>
        
        <button type="submit" 
                style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
            <i class="fas fa-search" style="margin-left: 8px;"></i>
            بحث
        </button>
    </form>
</div>

<!-- Customers List -->
<div class="content-card">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-list" style="color: #667eea;"></i>
            قائمة العملاء ({{ $customers->total() }})
        </h3>
    </div>
    
    @if($customers->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr style="background: #f7fafc;">
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">العميل</th>
                        <th class="hidden-mobile" style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">كود العميل</th>
                        <th class="hidden-mobile" style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">الهاتف</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">الحالة</th>
                        <th class="hidden-mobile" style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">تاريخ التسجيل</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px; color: #2d3748;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $customer->name }}</div>
                                    <div style="color: #718096; font-size: 12px;">{{ $customer->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="hidden-mobile" style="padding: 12px; text-align: center; color: #4a5568; font-family: monospace;">
                            {{ $customer->customer_code ?? 'غير محدد' }}
                        </td>
                        <td class="hidden-mobile" style="padding: 12px; text-align: center; color: #4a5568;">
                            {{ $customer->phone ?? 'غير محدد' }}
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            @if($customer->is_active)
                                <span style="background: #c6f6d5; color: #22543d; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-check-circle" style="margin-left: 4px;"></i>
                                    نشط
                                </span>
                            @else
                                <span style="background: #fed7d7; color: #742a2a; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-times-circle" style="margin-left: 4px;"></i>
                                    غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="hidden-mobile" style="padding: 12px; text-align: center; color: #4a5568; font-size: 14px;">
                            {{ $customer->created_at->format('Y-m-d') }}
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <div class="action-buttons" style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.customers.show', [$tenant, $customer]) }}" 
                                   style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <form method="POST" action="{{ route('admin.customers.toggle-status', [$tenant, $customer]) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="status-toggle-btn {{ $customer->is_active ? 'btn-deactivate' : 'btn-activate' }}">
                                        <i class="fas fa-{{ $customer->is_active ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('admin.customers.destroy', [$tenant, $customer]) }}" 
                                      style="display: inline;" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="background: #e53e3e; color: white; padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                        <i class="fas fa-trash"></i>
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
        <div style="margin-top: 20px;">
            {{ $customers->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #718096;">
            <i class="fas fa-user-friends" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
            <h4 style="margin: 0 0 8px 0; color: #4a5568;">لا توجد عملاء</h4>
            <p style="margin: 0;">لم يتم العثور على عملاء لهذا المستأجر</p>
        </div>
    @endif
</div>
@endsection
