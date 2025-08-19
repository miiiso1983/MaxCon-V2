@extends('layouts.modern')

@section('title', 'تقارير الساعات الإضافية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; font-size: 28px;">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 28px; font-weight: 800;">تقارير الساعات الإضافية</h1>
                    <p style="color: #718096; margin: 6px 0 0 0; font-size: 14px;">تحليلات وإحصائيات الساعات الإضافية</p>
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <form method="GET" action="{{ route('tenant.hr.overtime.export') }}">
                    <input type="hidden" name="export_type" value="current_month">
                    <input type="hidden" name="format" value="excel">
                    <button type="submit" style="background: #48bb78; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
                        <i class="fas fa-file-excel"></i> تصدير Excel
                    </button>
                </form>
                <form method="GET" action="{{ route('tenant.hr.overtime.export') }}">
                    <input type="hidden" name="export_type" value="current_month">
                    <input type="hidden" name="format" value="csv">
                    <button type="submit" style="background: #4299e1; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
                        <i class="fas fa-file-csv"></i> تصدير CSV
                    </button>
                </form>
                <form method="GET" action="{{ route('tenant.hr.overtime.export') }}">
                    <input type="hidden" name="export_type" value="current_month">
                    <input type="hidden" name="format" value="pdf">
                    <button type="submit" style="background: #f56565; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
                        <i class="fas fa-file-pdf"></i> تصدير PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-clock"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 26px; font-weight: 800;">{{ number_format($monthlyStats->sum('total_hours'), 1) }}</h4>
            <p style="color: #718096; margin: 0; font-size: 13px; font-weight: 600;">إجمالي ساعات هذا العام</p>
        </div>
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 26px; font-weight: 800;">{{ number_format($monthlyStats->sum('total_amount')) }}</h4>
            <p style="color: #718096; margin: 0; font-size: 13px; font-weight: 600;">إجمالي المبالغ هذا العام</p>
        </div>
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-user-check"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 26px; font-weight: 800;">{{ $employeeStats->count() }}</h4>
            <p style="color: #718096; margin: 0; font-size: 13px; font-weight: 600;">أفضل الموظفين (Top)</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px;">
            <h3 style="margin: 0 0 15px 0; color: #2d3748; font-weight: 800; font-size: 18px;">إحصائيات شهرية</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f7fafc;">
                            <th style="padding: 10px; text-align: right;">الشهر</th>
                            <th style="padding: 10px; text-align: center;">الساعات</th>
                            <th style="padding: 10px; text-align: center;">المبالغ</th>
                            <th style="padding: 10px; text-align: center;">الطلبات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyStats as $row)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 10px;">{{ $row->year }}-{{ str_pad($row->month, 2, '0', STR_PAD_LEFT) }}</td>
                            <td style="padding: 10px; text-align: center;">{{ number_format($row->total_hours, 1) }}</td>
                            <td style="padding: 10px; text-align: center;">{{ number_format($row->total_amount) }}</td>
                            <td style="padding: 10px; text-align: center;">{{ $row->total_requests }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px;">
            <h3 style="margin: 0 0 15px 0; color: #2d3748; font-weight: 800; font-size: 18px;">أفضل 10 موظفين</h3>
            <div style="display: grid; gap: 10px;">
                @foreach($employeeStats as $emp)
                <div style="display: flex; justify-content: space-between; padding: 10px; background: #fff; border-radius: 8px; border: 1px solid #f1f5f9;">
                    <span style="color: #2d3748; font-weight: 600;">{{ $emp->employee->full_name ?? '-' }}</span>
                    <span style="color: #48bb78; font-weight: 700;">{{ number_format($emp->total_hours, 1) }} ساعة</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

