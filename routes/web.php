<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminAuth;

//Register
Route::get('/register', [MemberController::class, 'create'])->name('member.create');
Route::post('/register', [MemberController::class, 'store'])->name('member.store');

//Register Success
Route::get('register-success', function (){
    return view('register-success');
})->name('member.success');



//Ngrok
Route::get('/', function () {
    return redirect('/register');
});

Route::get('/members/{id}', [MemberController::class, 'show'])->name('members.show');
Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');
Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('members.edit');
Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');


// Admin login routes
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

// Dashboard route protected with middleware
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware(AdminAuth::class) // <-- use the middleware class directly
    ->name('admin.dashboard');

// Logout route
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// /register(GET)- shows the registration form
// /register(POST)- saves the data into the database
// name()- gives your route a nickname so you can use it in blade like
// route(member.store)
