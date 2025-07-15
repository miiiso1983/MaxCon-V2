@extends('layouts.modern')

@section('page-title', 'عرض الفئة: ' . $category->name)
@section('page-description', 'تفاصيل فئة المنتجات')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                             style="width: 80px; height: 80px; border-radius: 15px; object-fit: cover; margin-left: 20px; border: 3px solid rgba(255,255,255,0.3);">
                    @else
                        <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 20px; margin-left: 20px; border: 3px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-tags" style="font-size: 40px;"></i>
                        </div>
                    @endif
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $category->name }} 🏷️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $category->description ?? 'لا يوجد وصف' }}
                        </p>
                        @if($category->parent)
                            <div style="margin-top: 10px;">
                                <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 15px; font-size: 14px;">
                                    <i class="fas fa-level-up-alt" style="margin-left: 5px;"></i>
                                    {{ $category->full_path }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-code" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $category->code }}</span>
                    </div>
                    <div style="background: rgba(16, 185, 129, 0.2); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-cube" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $category->products->count() }} منتج</span>
                    </div>
                    @if($category->children->count() > 0)
                        <div style="background: rgba(59, 130, 246, 0.2); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-sitemap" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px;">{{ $category->children->count() }} فئة فرعية</span>
                        </div>
                    @endif
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-{{ $category->status === 'active' ? 'check-circle' : 'times-circle' }}" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $category->status === 'active' ? 'نشطة' : 'غير نشطة' }}</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.categories.edit', $category) }}" style="background: rgba(245, 158, 11, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
                <a href="{{ route('tenant.inventory.categories.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <!-- Subcategories -->
    @if($category->children->count() > 0)
        <div class="content-card">
            <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-sitemap" style="color: #8b5cf6;"></i>
                الفئات الفرعية ({{ $category->children->count() }})
            </h3>
            
            <div style="display: grid; gap: 15px;">
                @foreach($category->children as $child)
                    <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; transition: all 0.3s ease;" 
                         onmouseover="this.style.borderColor='#8b5cf6'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(139, 92, 246, 0.15)';"
                         onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($child->image)
                                    <img src="{{ asset('storage/' . $child->image) }}" alt="{{ $child->name }}" 
                                         style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                @else
                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                        {{ substr($child->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 600; color: #2d3748; margin-bottom: 2px;">{{ $child->name }}</div>
                                    <div style="font-size: 12px; color: #6b7280;">{{ $child->code }}</div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                    {{ $child->products->count() }} منتج
                                </span>
                                <a href="{{ route('tenant.inventory.categories.show', $child) }}" 
                                   style="background: #8b5cf6; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    <!-- Products -->
    <div class="content-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <h3 style="color: #2d3748; margin: 0; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-cube" style="color: #3b82f6;"></i>
                المنتجات ({{ $category->products->count() }})
            </h3>
            <a href="{{ route('tenant.inventory.products.create') }}?category_id={{ $category->id }}" 
               style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600;">
                <i class="fas fa-plus" style="margin-left: 5px;"></i>
                إضافة منتج
            </a>
        </div>
        
        @if($category->products->count() > 0)
            <div style="display: grid; gap: 15px; max-height: 500px; overflow-y: auto;">
                @foreach($category->products as $product)
                    <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; transition: all 0.3s ease;" 
                         onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.15)';"
                         onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($product->primary_image_url)
                                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" 
                                         style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                @else
                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                                        {{ substr($product->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 600; color: #2d3748; margin-bottom: 2px;">{{ $product->name }}</div>
                                    <div style="font-size: 12px; color: #6b7280;">{{ $product->code ?? $product->product_code }}</div>
                                    @if($product->brand)
                                        <div style="font-size: 11px; color: #9ca3af;">{{ $product->brand }}</div>
                                    @endif
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="text-align: center;">
                                    <div style="font-weight: 600; color: #059669; font-size: 14px;">
                                        {{ number_format($product->selling_price, 2) }} {{ $product->currency ?? 'IQD' }}
                                    </div>
                                    <div style="font-size: 11px; color: #6b7280;">
                                        مخزون: {{ number_format($product->current_stock ?? $product->stock_quantity ?? 0, 2) }}
                                    </div>
                                </div>
                                <a href="{{ route('tenant.inventory.products.show', $product) }}" 
                                   style="background: #3b82f6; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px; color: #6b7280;">
                <div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                    <i class="fas fa-cube"></i>
                </div>
                <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 16px; font-weight: 600;">لا توجد منتجات</h4>
                <p style="margin: 0 0 15px 0; font-size: 14px;">لم يتم إضافة أي منتجات لهذه الفئة بعد</p>
                <a href="{{ route('tenant.inventory.products.create') }}?category_id={{ $category->id }}" 
                   style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-plus"></i>
                    إضافة منتج جديد
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
