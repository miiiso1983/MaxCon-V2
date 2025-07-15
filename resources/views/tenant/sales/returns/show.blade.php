@extends('layouts.modern')

@section('page-title', 'طلب إرجاع رقم ' . $return->return_number)
@section('page-description', 'تفاصيل طلب الإرجاع')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-undo-alt" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            طلب إرجاع {{ $return->return_number }} 🔄
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $return->type === 'return' ? 'طلب إرجاع' : 'طلب استبدال' }} - {{ $return->customer->name }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $return->return_date->format('Y/m/d') }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        @switch($return->status)
                            @case('pending')
                                <i class="fas fa-clock" style="margin-left: 8px; color: #fbbf24;"></i>
                                <span style="font-size: 14px;">معلق</span>
                                @break
                            @case('approved')
                                <i class="fas fa-check" style="margin-left: 8px; color: #4ade80;"></i>
                                <span style="font-size: 14px;">موافق عليه</span>
                                @break
                            @case('completed')
                                <i class="fas fa-check-double" style="margin-left: 8px; color: #34d399;"></i>
                                <span style="font-size: 14px;">مكتمل</span>
                                @break
                            @case('rejected')
                                <i class="fas fa-times" style="margin-left: 8px; color: #f87171;"></i>
                                <span style="font-size: 14px;">مرفوض</span>
                                @break
                        @endswitch
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-money-bill-wave" style="margin-left: 8px; color: #34d399;"></i>
                        <span style="font-size: 14px;">{{ number_format($return->refund_amount, 0) }} د.ع</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.returns.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Return Details -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Main Details -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #f093fb; margin-left: 10px;"></i>
            تفاصيل الطلب
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">رقم الطلب</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px; font-weight: 600;">{{ $return->return_number }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">الفاتورة الأصلية</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">
                    <a href="{{ route('tenant.sales.invoices.show', $return->invoice) }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                        {{ $return->invoice->invoice_number }}
                    </a>
                </div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">العميل</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px; font-weight: 600;">{{ $return->customer->name }}</div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">نوع العملية</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">
                    @if($return->type === 'return')
                        <span style="color: #1e40af; font-weight: 600;">
                            <i class="fas fa-undo"></i> إرجاع (استرداد نقدي)
                        </span>
                    @else
                        <span style="color: #92400e; font-weight: 600;">
                            <i class="fas fa-exchange-alt"></i> استبدال (تبديل منتج)
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">تاريخ الإرجاع</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">{{ $return->return_date->format('Y/m/d') }}</div>
            </div>
            @if($return->refund_method)
            <div>
                <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">طريقة الاسترداد</label>
                <div style="padding: 10px; background: #f8fafc; border-radius: 6px;">
                    @switch($return->refund_method)
                        @case('cash')
                            <i class="fas fa-money-bill-wave"></i> نقداً
                            @break
                        @case('credit')
                            <i class="fas fa-credit-card"></i> رصيد للعميل
                            @break
                        @case('bank_transfer')
                            <i class="fas fa-university"></i> تحويل بنكي
                            @break
                    @endswitch
                </div>
            </div>
            @endif
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">سبب الإرجاع</label>
            <div style="padding: 15px; background: #f8fafc; border-radius: 6px; line-height: 1.6;">{{ $return->reason }}</div>
        </div>
        
        @if($return->notes)
        <div>
            <label style="font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">ملاحظات إضافية</label>
            <div style="padding: 15px; background: #f8fafc; border-radius: 6px; line-height: 1.6;">{{ $return->notes }}</div>
        </div>
        @endif
    </div>
    
    <!-- Status & Actions -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cogs" style="color: #f093fb; margin-left: 10px;"></i>
            الحالة والإجراءات
        </h3>
        
        <!-- Status -->
        <div style="margin-bottom: 20px;">
            <label style="font-weight: 600; color: #4a5568; margin-bottom: 10px; display: block;">الحالة الحالية</label>
            @switch($return->status)
                @case('pending')
                    <div style="background: #fef3c7; color: #92400e; padding: 15px; border-radius: 8px; text-align: center; font-weight: 600;">
                        <i class="fas fa-clock" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                        معلق - في انتظار المراجعة
                    </div>
                    @break
                @case('approved')
                    <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; text-align: center; font-weight: 600;">
                        <i class="fas fa-check" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                        موافق عليه - تم تحديث المخزون
                    </div>
                    @break
                @case('completed')
                    <div style="background: #dbeafe; color: #1e40af; padding: 15px; border-radius: 8px; text-align: center; font-weight: 600;">
                        <i class="fas fa-check-double" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                        مكتمل - تم الانتهاء من العملية
                    </div>
                    @break
                @case('rejected')
                    <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; text-align: center; font-weight: 600;">
                        <i class="fas fa-times" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                        مرفوض
                    </div>
                    @break
            @endswitch
        </div>
        
        <!-- Processing Info -->
        @if($return->processed_by)
        <div style="margin-bottom: 20px; padding: 15px; background: #f0f9ff; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #1e40af; font-size: 14px; font-weight: 600;">معلومات المعالجة</h4>
            <div style="font-size: 13px; color: #374151;">
                <div><strong>تمت المعالجة بواسطة:</strong> {{ $return->processedBy->name }}</div>
                <div><strong>تاريخ المعالجة:</strong> {{ $return->processed_at->format('Y/m/d H:i') }}</div>
            </div>
        </div>
        @endif
        
        <!-- Actions -->
        @if($return->status === 'pending')
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <form method="POST" action="{{ route('tenant.sales.returns.approve', $return) }}" style="margin: 0;">
                @csrf
                <button type="submit" onclick="return confirm('هل أنت متأكد من الموافقة على طلب الإرجاع؟ سيتم تحديث المخزون تلقائياً.')" style="width: 100%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-check"></i> الموافقة على الطلب
                </button>
            </form>
            
            <form method="POST" action="{{ route('tenant.sales.returns.reject', $return) }}" style="margin: 0;">
                @csrf
                <button type="submit" onclick="return confirm('هل أنت متأكد من رفض طلب الإرجاع؟')" style="width: 100%; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-times"></i> رفض الطلب
                </button>
            </form>
            
            <a href="{{ route('tenant.sales.returns.edit', $return) }}" style="width: 100%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px; border-radius: 8px; font-weight: 600; text-decoration: none; text-align: center; display: block;">
                <i class="fas fa-edit"></i> تعديل الطلب
            </a>
        </div>
        @elseif($return->status === 'approved')
        <div>
            <form method="POST" action="{{ route('tenant.sales.returns.complete', $return) }}" style="margin: 0;">
                @csrf
                <button type="submit" onclick="return confirm('هل أنت متأكد من إكمال طلب الإرجاع؟')" style="width: 100%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-check-double"></i> إكمال الطلب
                </button>
            </form>
        </div>
        @endif
        
        <!-- Financial Summary -->
        <div style="margin-top: 20px; padding: 15px; background: #f0fff4; border: 1px solid #c6f6d5; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #065f46; font-size: 14px; font-weight: 600;">الملخص المالي</h4>
            <div style="font-size: 13px; color: #374151;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>إجمالي قيمة المرتجع:</span>
                    <span style="font-weight: 600;">{{ number_format($return->total_amount, 0) }} د.ع</span>
                </div>
                @if($return->type === 'exchange')
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>قيمة الاستبدال:</span>
                    <span style="font-weight: 600;">{{ number_format($return->total_amount - $return->refund_amount, 0) }} د.ع</span>
                </div>
                @endif
                <div style="display: flex; justify-content: space-between; border-top: 1px solid #d1fae5; padding-top: 5px;">
                    <span style="font-weight: 600;">المبلغ المسترد:</span>
                    <span style="font-weight: 600; color: #059669;">{{ number_format($return->refund_amount, 0) }} د.ع</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Return Items -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-list" style="color: #f093fb; margin-left: 10px;"></i>
        الأصناف المرتجعة ({{ $return->items->count() }})
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">المنتج</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">الكمية الأصلية</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">الكمية المرتجعة</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">سعر الوحدة</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">الإجمالي</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">الحالة</th>
                    @if($return->type === 'exchange')
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">منتج الاستبدال</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($return->items as $item)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            <div style="font-weight: 600; color: #2d3748;">{{ $item->product_name }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $item->product_code }}</div>
                            @if($item->batch_number)
                                <div style="font-size: 12px; color: #6b7280;">رقم الدفعة: {{ $item->batch_number }}</div>
                            @endif
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            <span style="font-weight: 600; color: #4a5568;">{{ $item->quantity_original }}</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            <span style="font-weight: 600; color: #059669;">{{ $item->quantity_returned }}</span>
                            <div style="font-size: 12px; color: #6b7280;">{{ $item->return_percentage }}% من الكمية الأصلية</div>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            <span style="font-weight: 600;">{{ number_format($item->unit_price, 0) }} د.ع</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            <span style="font-weight: 600; color: #059669;">{{ number_format($item->total_amount, 0) }} د.ع</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            @switch($item->condition)
                                @case('good')
                                    <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-check"></i> جيد
                                    </span>
                                    @break
                                @case('damaged')
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-exclamation-triangle"></i> تالف
                                    </span>
                                    @break
                                @case('expired')
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-clock"></i> منتهي الصلاحية
                                    </span>
                                    @break
                            @endswitch
                            @if($item->reason)
                                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">{{ $item->reason }}</div>
                            @endif
                        </td>
                        @if($return->type === 'exchange')
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            @if($item->exchangeProduct)
                                <div style="font-weight: 600; color: #2d3748;">{{ $item->exchangeProduct->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">الكمية: {{ $item->exchange_quantity }}</div>
                                <div style="font-size: 12px; color: #6b7280;">القيمة: {{ number_format($item->exchange_total_amount, 0) }} د.ع</div>
                            @else
                                <span style="color: #6b7280; font-style: italic;">لا يوجد استبدال</span>
                            @endif
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
