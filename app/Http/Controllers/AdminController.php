<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Member;
use Carbon\Carbon;

class AdminController extends Controller
{
    /* ==========================
       SHOW LOGIN FORM
    ========================== */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /* ==========================
       ADMIN LOGIN
    ========================== */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Admin credentials from .env
        $username = env('ADMIN_USERNAME', 'fitcamp_admin');
        $hashedPassword = env('ADMIN_PASSWORD', password_hash('password123', PASSWORD_BCRYPT));

        // Unique rate-limit key (per IP + username)
        $throttleKey = strtolower($request->username) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors([
                'Too many login attempts. Please try again in a few minutes.'
            ]);
        }

        if (
            $request->username === $username &&
            password_verify($request->password, $hashedPassword)
        ) {
            Session::put('is_admin', true);
            RateLimiter::clear($throttleKey);

            return redirect()->route('admin.mainDashboard');
        }

        RateLimiter::hit($throttleKey, 60); // lock for 60 seconds
        return back()->withErrors(['Invalid username or password']);
    }

    /* ==========================
       ADMIN DASHBOARD / PROFILE
    ========================== */
    public function dashboard(Request $request)
    {
        // Protect route
        if (!Session::get('is_admin')) {
            return redirect()->route('admin.login');
        }

        // Auto-expire members
        Member::where('end_date', '<', Carbon::today())
            ->where('status', 'Active')
            ->update(['status' => 'Expired']);

        $members = Member::query();

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $members->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('facebook_name', 'like', '%' . $request->search . '%')
                  ->orWhere('member_id', 'like', '%' . $request->search . '%');
            });
        }

        // 🟢 FILTER
        if ($request->filled('filter') && $request->filter !== 'all') {
            $members->where('status', ucfirst($request->filter));
        }

        $members = $members->orderBy('created_at', 'desc')->get();
        $membershipLabels = $this->membershipLabels();

        return view('admin.profile', compact('members', 'membershipLabels'));
    }

    /* ==========================
       ADMIN LOGOUT
    ========================== */
    public function logout(Request $request)
    {
        Session::forget('is_admin');
        Session::flush();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /* ==========================
       MEMBERSHIP LABELS
    ========================== */
    private function membershipLabels()
    {
        return [
            'unli_1_month' => '1 Month – ₱600',
            'unli_3_months' => '3 Months – ₱1,200',
            'unli_6_months' => '6 Months – ₱2,200',
            'pt_package_a' => 'PT Package A – 6 Sessions (₱1,200)',
            'pt_package_b' => 'PT Package B – 11 + 1 Free (₱2,200)',
            'pt_package_c' => 'PT Package C – 24 + 5 Free (₱4,800)',
            'boxing_package_a' => 'Boxing Package A – 6 Sessions (₱1,500)',
            'boxing_package_b' => 'Boxing Package B – 11 + 1 Free (₱2,700)',
            'boxing_package_c' => 'Boxing Package C – 24 + 5 Free (₱5,700)',
        ];
    }

    /* ==========================
       ATTENDANCE PAGE
    ========================== */
    public function attendance()
    {
        $members = Member::latest()->get();
        return view('attendance.index', compact('members'));
    }
}
