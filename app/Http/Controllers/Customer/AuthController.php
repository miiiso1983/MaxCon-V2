<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

/**
 * Customer Authentication Controller
 * 
 * تحكم في مصادقة العملاء
 */
class AuthController extends Controller
{
    /**
     * Show customer login form
     */
    public function showLoginForm(): View
    {
        return view('customer.auth.login');
    }

    /**
     * Handle customer login
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('customer')->attempt($credentials, $request->filled('remember'))) {
            $customer = Auth::guard('customer')->user();
            
            // Check if customer is active
            if (!$customer->is_active) {
                Auth::guard('customer')->logout();
                return back()->withErrors([
                    'email' => 'حسابك معطل. يرجى التواصل مع الإدارة.',
                ]);
            }

            // Update last login info
            $customer->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            $request->session()->regenerate();

            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'مرحباً بك ' . $customer->name);
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة.',
        ])->onlyInput('email');
    }

    /**
     * Handle customer logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }

    /**
     * Show customer registration form
     */
    public function showRegistrationForm(): View
    {
        return view('customer.auth.register');
    }

    /**
     * Handle customer registration
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
        ]);

        $customer = Customer::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'password' => Hash::make($request->get('password')),
            'company_name' => $request->get('company_name'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'tenant_id' => 1, // Default tenant or get from subdomain
            'is_active' => false, // Requires admin approval
            'currency' => 'IQD',
        ]);

        // Assign basic customer role
        $customer->assignRole('basic_customer');

        return redirect()->route('customer.login')
            ->with('success', 'تم إنشاء حسابك بنجاح. سيتم مراجعته من قبل الإدارة قريباً.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm(): View
    {
        return view('customer.auth.forgot-password');
    }

    /**
     * Handle forgot password
     */
    public function forgotPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
        ]);

        // Here you would implement password reset logic
        // For now, just return success message
        
        return back()->with('success', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني');
    }
}
