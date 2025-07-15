@extends('layouts.app')

@section('title', 'لوحة التحكم الرئيسية')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-xl shadow-lg text-white">
        <div class="px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img class="h-16 w-16 rounded-full border-4 border-white border-opacity-20"
                         src="{{ auth()->user()->avatar_url }}"
                         alt="{{ auth()->user()->name }}">
                    <div class="mr-4">
                        <h1 class="text-3xl font-bold mb-2">
                            أهلاً وسهلاً، {{ auth()->user()->name }}!
                        </h1>
                        <p class="text-indigo-100 text-lg">
                            @if(is_tenant_context())
                                مؤسسة: <strong>{{ tenant()->name }}</strong>
                            @else
                                لوحة تحكم مدير النظام
                            @endif
                        </p>
                        <p class="text-indigo-200 text-sm mt-1">
                            آخر تسجيل دخول: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'لم يسجل من قبل' }}
                        </p>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="bg-white bg-opacity-20 rounded-full p-6">
                        <i class="fas fa-crown text-5xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Users Count -->
        @can('users.view')
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-users text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Models\User::count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">View all users</a>
                </div>
            </div>
        </div>
        @endcan

        <!-- Active Sessions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-circle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Sessions</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Models\User::where('last_login_at', '>=', now()->subMinutes(30))->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="text-gray-500">Last 30 minutes</span>
                </div>
            </div>
        </div>

        <!-- Storage Usage -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-hdd text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Storage Used</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                @if(is_tenant_context())
                                    {{ number_format(tenant()->storage_limit / 1024 / 1024, 0) }} MB
                                @else
                                    N/A
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    @if(is_tenant_context())
                        <span class="text-gray-500">of {{ number_format(tenant()->storage_limit / 1024 / 1024, 0) }} MB limit</span>
                    @else
                        <span class="text-gray-500">System storage</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-check-circle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">System Status</dt>
                            <dd class="text-lg font-medium text-gray-900">Healthy</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="text-green-600">All systems operational</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        @can('users.view')
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fas fa-user-plus mr-2"></i>
                    Recent Users
                </h3>
                <div class="space-y-3">
                    @forelse(\App\Models\User::latest()->limit(5)->get() as $user)
                        <div class="flex items-center space-x-3">
                            <img class="h-8 w-8 rounded-full" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No users found.</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endcan

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fas fa-bolt mr-2"></i>
                    Quick Actions
                </h3>
                <div class="space-y-3">
                    @can('users.create')
                        <a href="#" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-plus text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-900">Add New User</p>
                                <p class="text-sm text-blue-700">Create a new user account</p>
                            </div>
                        </a>
                    @endcan

                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.tenants.create') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="flex-shrink-0">
                                <i class="fas fa-building text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-900">Add New Tenant</p>
                                <p class="text-sm text-green-700">Create a new tenant organization</p>
                            </div>
                        </a>
                    @endif

                    <a href="#" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="flex-shrink-0">
                            <i class="fas fa-cog text-purple-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-purple-900">Settings</p>
                            <p class="text-sm text-purple-700">Configure your preferences</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
