@extends('layouts.modern')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم الرئيسية')
@section('page-description', 'نظرة عامة على أداء النظام والإحصائيات')

@section('content')
<!-- Welcome Card -->
<div class="header-card">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold mb-2">
                مرحباً، {{ auth()->user()->name }}! 👋
            </h2>
            <p class="text-blue-100 text-lg mb-4">
                @if(is_tenant_context())
                    مؤسسة: <strong>{{ tenant()->name }}</strong>
                @elseif(auth()->user()->tenant_id && auth()->user()->tenant)
                    مؤسسة: <strong>{{ auth()->user()->tenant->name }}</strong>
                @else
                    مرحباً بك في نظام إدارة الأعمال المتكامل
                @endif
            </p>
            <div class="flex items-center space-x-4 space-x-reverse">
                <div class="bg-white bg-opacity-20 rounded-lg px-3 py-1">
                    <span class="text-sm">آخر تسجيل دخول: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'لم يسجل من قبل' }}</span>
                </div>
            </div>
        </div>
        <div class="hidden lg:block">
            <div class="bg-white bg-opacity-20 rounded-2xl p-6">
                <i class="fas fa-chart-line text-6xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    @can('users.view')
    <div class="stat-card">
        <div class="icon-wrapper bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <i class="fas fa-users"></i>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">إجمالي المستخدمين</h3>
        <p class="text-3xl font-bold text-gray-900 mb-2">
            @if(is_tenant_context())
                {{ \App\Models\User::where('tenant_id', tenant_id())->count() }}
            @elseif(auth()->user()->tenant_id)
                {{ \App\Models\User::where('tenant_id', auth()->user()->tenant_id)->count() }}
            @else
                {{ \App\Models\User::count() }}
            @endif
        </p>
        <div class="flex items-center">
            <span class="text-green-500 text-sm font-medium">+12%</span>
            <span class="text-gray-500 text-sm mr-2">من الشهر الماضي</span>
        </div>
    </div>
    @endcan

    <!-- Active Sessions -->
    <div class="stat-card">
        <div class="icon-wrapper bg-gradient-to-br from-green-500 to-green-600 text-white">
            <i class="fas fa-circle"></i>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">الجلسات النشطة</h3>
        <p class="text-3xl font-bold text-gray-900 mb-2">
            {{ \App\Models\User::where('last_login_at', '>=', now()->subMinutes(30))->count() }}
        </p>
        <div class="flex items-center">
            <span class="text-green-500 text-sm font-medium">متصل الآن</span>
        </div>
    </div>

    <!-- Storage -->
    <div class="stat-card">
        <div class="icon-wrapper bg-gradient-to-br from-yellow-500 to-orange-500 text-white">
            <i class="fas fa-hdd"></i>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">استخدام التخزين</h3>
        <p class="text-3xl font-bold text-gray-900 mb-2">
            @if(is_tenant_context())
                {{ number_format(tenant()->storage_limit / 1024 / 1024, 0) }}
            @elseif(auth()->user()->tenant_id && auth()->user()->tenant)
                {{ number_format(auth()->user()->tenant->storage_limit / 1024 / 1024, 0) }}
            @else
                ∞
            @endif
            <span class="text-lg text-gray-500">ميجا</span>
        </p>
        <div class="flex items-center">
            <div class="bg-gray-200 rounded-full h-2 flex-1 ml-2">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 h-2 rounded-full" style="width: 65%"></div>
            </div>
            <span class="text-gray-500 text-sm">65%</span>
        </div>
    </div>

    <!-- System Status -->
    <div class="stat-card">
        <div class="icon-wrapper bg-gradient-to-br from-purple-500 to-purple-600 text-white">
            <i class="fas fa-heartbeat"></i>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">حالة النظام</h3>
        <p class="text-2xl font-bold text-gray-900 mb-2">ممتاز</p>
        <div class="flex items-center">
            <div class="w-2 h-2 bg-green-500 rounded-full ml-2"></div>
            <span class="text-green-500 text-sm font-medium">جميع الأنظمة تعمل</span>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Activity -->
    @can('users.view')
    <div class="lg:col-span-2">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">النشاط الأخير</h3>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">عرض الكل</button>
            </div>
            
            <div class="space-y-4">
                @php
                    if (is_tenant_context()) {
                        $recentUsers = \App\Models\User::where('tenant_id', tenant_id())->latest()->limit(5)->get();
                    } elseif (auth()->user()->tenant_id) {
                        $recentUsers = \App\Models\User::where('tenant_id', auth()->user()->tenant_id)->latest()->limit(5)->get();
                    } else {
                        $recentUsers = \App\Models\User::latest()->limit(5)->get();
                    }
                @endphp
                
                @forelse($recentUsers as $user)
                    <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                        <img class="h-12 w-12 rounded-full ml-4" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="text-left">
                            <p class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            @foreach($user->roles as $role)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    @switch($role->name)
                                        @case('super-admin') سوبر أدمن @break
                                        @case('tenant-admin') أدمن المؤسسة @break
                                        @case('manager') مدير @break
                                        @case('employee') موظف @break
                                        @case('customer') عميل @break
                                        @default {{ $role->name }}
                                    @endswitch
                                </span>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">لا توجد أنشطة حديثة</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @endcan

    <!-- Quick Actions -->
    <div class="space-y-6">
        <!-- Quick Actions Card -->
        <div class="card p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">إجراءات سريعة</h3>
            
            <div class="space-y-3">
                @can('users.create')
                    <button class="w-full btn-primary text-right">
                        <i class="fas fa-user-plus ml-2"></i>
                        إضافة مستخدم جديد
                    </button>
                @endcan
                
                @if(auth()->user()->isSuperAdmin())
                    <button class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl p-3 font-medium hover:shadow-lg transition-all">
                        <i class="fas fa-building ml-2"></i>
                        إضافة مؤسسة جديدة
                    </button>
                @endif
                
                <button class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl p-3 font-medium hover:shadow-lg transition-all">
                    <i class="fas fa-chart-bar ml-2"></i>
                    عرض التقارير
                </button>
                
                <button class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-xl p-3 font-medium hover:shadow-lg transition-all">
                    <i class="fas fa-cog ml-2"></i>
                    إعدادات النظام
                </button>
            </div>
        </div>

        <!-- System Info -->
        <div class="card p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">معلومات النظام</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">إصدار النظام</span>
                    <span class="font-medium">v2.1.0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">آخر تحديث</span>
                    <span class="font-medium">{{ now()->format('Y/m/d') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">وقت التشغيل</span>
                    <span class="font-medium text-green-600">99.9%</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
