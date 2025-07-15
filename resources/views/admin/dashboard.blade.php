@extends('layouts.app')

@section('title', 'لوحة تحكم السوبر أدمن')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-xl shadow-lg text-white">
        <div class="px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-crown ml-3"></i>
                        لوحة تحكم السوبر أدمن
                    </h1>
                    <p class="text-red-100 text-lg">
                        إدارة شاملة للنظام والمؤسسات
                    </p>
                    <p class="text-red-200 text-sm mt-2">
                        مرحباً {{ auth()->user()->name }} - لديك صلاحيات كاملة على النظام
                    </p>
                </div>
                <div class="hidden lg:block">
                    <div class="bg-white bg-opacity-20 rounded-full p-6">
                        <i class="fas fa-shield-alt text-6xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Tenants -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-r-4 border-blue-500 transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي المؤسسات</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $tenantStats['total'] ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-green-600 text-sm font-medium">{{ $tenantStats['active'] ?? 0 }} نشط</span>
                <span class="text-gray-500 text-sm mr-2">من أصل {{ $tenantStats['total'] ?? 0 }}</span>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-r-4 border-green-500 transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي المستخدمين</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $userStats['total'] ?? 0 }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-green-600 text-sm font-medium">{{ $userStats['active'] ?? 0 }} نشط</span>
                <span class="text-gray-500 text-sm mr-2">في جميع المؤسسات</span>
            </div>
        </div>

        <!-- System Health -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-r-4 border-purple-500 transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">صحة النظام</p>
                    <p class="text-3xl font-bold text-gray-900">ممتاز</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-heartbeat text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-purple-600 text-sm font-medium">جميع الأنظمة تعمل بكفاءة</span>
            </div>
        </div>

        <!-- Trials Expiring -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-r-4 border-yellow-500 transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">التجارب المنتهية</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $trialsExpiringSoon->count() ?? 0 }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-yellow-600 text-sm font-medium">خلال 7 أيام القادمة</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Tenants -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fas fa-building mr-2"></i>
                    Recent Tenants
                </h3>
                <div class="space-y-3">
                    @forelse($recentTenants as $tenant)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $tenant->name }}</p>
                                <p class="text-sm text-gray-500">{{ $tenant->plan }} plan</p>
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $tenant->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No tenants found.</p>
                    @endforelse
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.tenants.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        View all tenants →
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fas fa-bolt mr-2"></i>
                    Quick Actions
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.tenants.create') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="flex-shrink-0">
                            <i class="fas fa-plus text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-900">Create New Tenant</p>
                            <p class="text-sm text-blue-700">Add a new organization</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.tenants.index') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="flex-shrink-0">
                            <i class="fas fa-building text-green-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-900">Manage Tenants</p>
                            <p class="text-sm text-green-700">View and edit tenants</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-purple-900">Manage Users</p>
                            <p class="text-sm text-purple-700">View and edit users</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
