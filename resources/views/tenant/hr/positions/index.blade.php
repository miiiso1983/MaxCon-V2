@extends('layouts.modern')

@section('title', 'إدارة المناصب')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إدارة المناصب</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">المناصب والوظائف في الشركة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.positions.create') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-plus"></i>
                    إضافة منصب جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-briefcase"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">{{ $positions->count() }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجمالي المناصب</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-crown"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">{{ $positions->where('level', 'executive')->count() }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">مناصب تنفيذية</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-user-tie"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">{{ $positions->where('level', 'manager')->count() }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">مناصب إدارية</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-users"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">{{ $positions->whereIn('level', ['mid', 'senior'])->count() }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">مناصب فنية</p>
        </div>
    </div>

    <!-- Positions List -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 25px;">
            <h3 style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;">
                <i class="fas fa-list" style="margin-left: 10px; color: #4299e1;"></i>
                قائمة المناصب
            </h3>
        </div>

        <!-- Positions Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
            @foreach($positions as $position)
        @php($deptName = is_object($position->department ?? null) ? ($position->department->name ?? '') : ($position->department ?? ''))
                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 15px; padding: 25px; transition: transform 0.3s, box-shadow 0.3s;" 
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'" 
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    
                    <!-- Position Header -->
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <div style="
                            background: 
                                @if($position->level === 'executive') linear-gradient(135deg, #9f7aea 0%, #805ad5 100%)
                                @elseif($position->level === 'manager') linear-gradient(135deg, #48bb78 0%, #38a169 100%)
                                @elseif($position->level === 'senior') linear-gradient(135deg, #4299e1 0%, #3182ce 100%)
                                @else linear-gradient(135deg, #ed8936 0%, #dd6b20 100%)
                                @endif;
                            color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700;">
                            @if($position->level === 'executive')
                                <i class="fas fa-crown"></i>
                            @elseif($position->level === 'manager')
                                <i class="fas fa-user-tie"></i>
                            @elseif($position->level === 'senior')
                                <i class="fas fa-star"></i>
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 18px; font-weight: 700;">{{ $position->title }}</h4>
                            <p style="color: #718096; margin: 0; font-size: 14px;">{{ $deptName }}</p>
                        </div>
                        <div style="text-align: center;">
                            <span style="
                                background: 
                                    @if($position->level === 'executive') #9f7aea
                                    @elseif($position->level === 'manager') #48bb78
                                    @elseif($position->level === 'senior') #4299e1
                                    @else #ed8936
                                    @endif;
                                color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                @if($position->level === 'executive') تنفيذي
                                @elseif($position->level === 'manager') إداري
                                @elseif($position->level === 'senior') أول
                                @else متوسط
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Position Details -->
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <i class="fas fa-building" style="color: #4299e1; width: 16px;"></i>
                            <span style="color: #4a5568; font-size: 14px; font-weight: 600;">القسم:</span>
                            <span style="color: #2d3748; font-size: 14px;">{{ $deptName }}</span>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <i class="fas fa-layer-group" style="color: #4299e1; width: 16px;"></i>
                            <span style="color: #4a5568; font-size: 14px; font-weight: 600;">المستوى:</span>
                            <span style="color: #2d3748; font-size: 14px;">
                                @if($position->level === 'executive') تنفيذي
                                @elseif($position->level === 'manager') إداري
                                @elseif($position->level === 'senior') أول
                                @else متوسط
                                @endif
                            </span>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-briefcase" style="color: #4299e1; width: 16px;"></i>
                            <span style="color: #4a5568; font-size: 14px; font-weight: 600;">المنصب:</span>
                            <span style="color: #2d3748; font-size: 14px;">{{ $position->title }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 10px; justify-content: center; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                        <button onclick="alert('ميزة العرض قيد التطوير')" style="background: #4299e1; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                            <i class="fas fa-eye"></i> عرض
                        </button>
                        <a href="{{ route('tenant.hr.positions.edit', $position->id) }}" style="background: #ed8936; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; text-decoration:none;">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <button onclick="alert('ميزة الحذف قيد التطوير')" style="background: #f56565; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Actions -->
        <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #e2e8f0;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                
                <a href="{{ route('tenant.hr.positions.create') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-plus"></i>
                    إضافة منصب جديد
                </a>

                <a href="{{ route('tenant.hr.departments.index') }}" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-building"></i>
                    إدارة الأقسام
                </a>

                <a href="{{ route('tenant.hr.positions.reports') }}" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-chart-bar"></i>
                    تقارير المناصب
                </a>

                <a href="{{ route('tenant.hr.employees.index') }}" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-users"></i>
                    إدارة الموظفين
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
