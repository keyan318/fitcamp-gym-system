<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ScanQrController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\AdminAuth;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Default redirect
Route::get('/', function () {
    return redirect('/register');
});

// Member Registration
Route::get('/register', [MemberController::class, 'create'])->name('member.create');
Route::post('/register', [MemberController::class, 'store'])->name('member.store');

// Registration success
Route::get('/register-success', function () {
    return view('register-success');
})->name('member.success');

// Member profile
Route::get('/members/{id}', [MemberController::class, 'show'])->name('members.show');
Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('members.edit');
Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');
Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');


/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');


/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(AdminAuth::class)->group(function () {
    //Main Dashboard
      Route::get('/admin/mainDashboard', [DashboardController::class, 'index'])
     ->name('admin.mainDashboard');
    // Profile
    Route::get('/admin/profile', [AdminController::class, 'dashboard'])
        ->name('admin.profile');

    // Attendance list
    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    // Attendance calendar per member
    Route::get('/attendance/{member}/calendar', [AttendanceController::class, 'calendar'])
        ->name('attendance.calendar');

    // QR Scan page
    Route::get('/qr', function () {
        return view('scan.qr');
    })->name('scan.qr');

    // QR Scan → mark attendance
    Route::get('/scan/present/{memberId}', [AttendanceController::class, 'mark'])
        ->name('scan.present');

    // Membership expired
    Route::get('/scan/expired', [ScanQrController::class, 'expired'])
        ->name('scan.expired');
    
    
});
