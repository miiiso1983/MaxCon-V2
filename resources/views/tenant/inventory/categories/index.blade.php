@extends('layouts.modern')

@section('page-title', 'فئات المنتجات')
@section('page-description', 'إدارة فئات المنتجات الهرمية')

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
                        <i class="fas fa-tags" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            فئات المنتجات 🏷️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة وتنظيم فئات المنتجات الهرمية
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-layer-group" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $stats['total'] }} فئة</span>
                    </div>
                    <div style="background: rgba(16, 185, 129, 0.2); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $stats['active'] }} نشطة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-sitemap" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $stats['root'] }} فئة رئيسية</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.categories.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    فئة جديدة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="content-card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('tenant.inventory.categories.index') }}">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                       placeholder="البحث في الاسم، الرمز، أو الوصف...">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
                <select name="status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشطة</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشطة</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الفئة الأب</label>
                <select name="parent_id" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                    <option value="">جميع الفئات</option>
                    <option value="root" {{ request('parent_id') === 'root' ? 'selected' : '' }}>الفئات الرئيسية</option>
                    @foreach($rootCategories as $category)
                        <option value="{{ $category->id }}" {{ request('parent_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('tenant.inventory.categories.index') }}" style="background: #6b7280; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center;">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Categories List -->
<div class="content-card">
    @if($categories->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">الفئة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الرمز</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الفئة الأب</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">المنتجات</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                                             style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                    @else
                                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                            {{ substr($category->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: #2d3748; margin-bottom: 2px;">{{ $category->name }}</div>
                                        @if($category->description)
                                            <div style="font-size: 12px; color: #6b7280;">{{ Str::limit($category->description, 50) }}</div>
                                        @endif
                                        @if($category->level > 0)
                                            <div style="font-size: 11px; color: #8b5cf6; margin-top: 2px;">
                                                <i class="fas fa-level-up-alt" style="margin-left: 4px;"></i>
                                                مستوى {{ $category->level }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="background: #f3f4f6; color: #374151; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                    {{ $category->code }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @if($category->parent)
                                    <span style="color: #6b7280; font-size: 14px;">{{ $category->parent->name }}</span>
                                @else
                                    <span style="color: #8b5cf6; font-weight: 600; font-size: 14px;">فئة رئيسية</span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                    {{ $category->products->count() }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @if($category->status === 'active')
                                    <span style="background: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-check-circle" style="margin-left: 4px;"></i>
                                        نشطة
                                    </span>
                                @else
                                    <span style="background: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-times-circle" style="margin-left: 4px;"></i>
                                        غير نشطة
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('tenant.inventory.categories.show', $category) }}" 
                                       style="background: #3b82f6; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;"
                                       title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tenant.inventory.categories.edit', $category) }}" 
                                       style="background: #f59e0b; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;"
                                       title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('tenant.inventory.categories.destroy', $category) }}" style="display: inline;" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="background: #ef4444; color: white; padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;"
                                                title="حذف">
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
        @if($categories->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $categories->links() }}
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 60px 40px; color: #6b7280;">
            <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-tags"></i>
            </div>
            <h3 style="margin: 0 0 10px 0; color: #2d3748; font-size: 20px; font-weight: 700;">لا توجد فئات</h3>
            <p style="margin: 0 0 20px 0; font-size: 16px;">ابدأ بإنشاء فئة جديدة لتنظيم منتجاتك</p>
            <a href="{{ route('tenant.inventory.categories.create') }}" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i>
                إنشاء فئة جديدة
            </a>
        </div>
    @endif
</div>
@endsection
