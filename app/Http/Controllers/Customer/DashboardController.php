<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * Customer Dashboard Controller
 * 
 * تحكم في لوحة تحكم العميل
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Display customer dashboard
     */
    public function index(): View
    {
        $customer = Auth::guard('customer')->user();
        
        $dashboardData = $customer->getDashboardData();
        
        // Add permissions info
        $permissions = [
            'can_place_orders' => $customer->canPlaceOrders(),
            'can_view_financial_info' => $customer->canViewFinancialInfo(),
            'can_view_payment_history' => $customer->hasPermissionTo('view_payment_history'),
            'can_view_debt_details' => $customer->hasPermissionTo('view_debt_details'),
            'can_view_credit_limit' => $customer->hasPermissionTo('view_credit_limit'),
            'can_download_invoices' => $customer->hasPermissionTo('download_invoices'),
        ];

        return view('customer.dashboard', compact('dashboardData', 'permissions'));
    }

    /**
     * Display customer profile
     */
    public function profile(): View
    {
        $customer = Auth::guard('customer')->user();
        
        return view('customer.profile', compact('customer'));
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $customer->update($request->only([
            'name', 'email', 'phone', 'mobile', 
            'address', 'city', 'postal_code'
        ]));

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
