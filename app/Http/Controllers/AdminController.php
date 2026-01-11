<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Member;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Get credentials from .env
        $username = env('ADMIN_USERNAME', 'fitcamp_admin'); // fallback username
        $hashedPassword = env('ADMIN_PASSWORD', password_hash('password123', PASSWORD_BCRYPT));

        // Rate-limiting to prevent brute force
        $ip = $request->ip();
        if (RateLimiter::tooManyAttempts($ip, 5)) {
            return back()->withErrors(['Too many login attempts. Please try again in a few minutes.']);
        }

        // Check credentials
        if ($request->username === $username && password_verify($request->password, $hashedPassword)) {
            Session::put('is_admin', true);
            RateLimiter::clear($ip);
            return redirect()->route('admin.dashboard');
        }

        RateLimiter::hit($ip, 60); // lockout for 60 seconds
        return back()->withErrors(['Invalid username or password']);
    }
public function dashboard(Request $request)
    {
        // Protect dashboard
        if (!Session::get('is_admin')) {
            return redirect()->route('admin.login');
        }

        // Auto-expire members
        Member::where('end_date', '<', Carbon::today())
            ->where('status', 'Active')
            ->update(['status' => 'Expired']);

        // Start query
        $members = Member::query();

        // 🔍 SEARCH (Full Name / Facebook Name / Member ID)
        if ($request->filled('search')) {
            $members->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('facebook_name', 'like', '%' . $request->search . '%')
                  ->orWhere('member_id', 'like', '%' . $request->search . '%');
            });
        }

        // 🟢 FILTER (Active / Expired)
        if ($request->filled('filter') && $request->filter !== 'all') {
            $members->where('status', ucfirst($request->filter));
        }

        // Final fetch
        $members = $members->orderBy('created_at', 'desc')->get();

        $membershipLabels = $this->membershipLabels();

        return view('admin.dashboard', compact('members', 'membershipLabels'));
    }

    public function logout()
    {
        Session::forget('is_admin');
        return redirect()->route('admin.login');
    }

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

}
