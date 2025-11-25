<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;

//Register
Route::get('/register', [MemberController::class, 'create'])->name('member.create');
Route::post('/register', [MemberController::class, 'store'])->name('member.store');

//Register Success
Route::get('register-success', function (){
    return view('register-success');
})->name('member.success');

//Admin
Route::get('/admin', [MemberController::class, 'index'])->name('admin.dashboard');

//Ngrok
Route::get('/', function () {
    return redirect('/register');
});

Route::get('/members/{id}', [MemberController::class, 'show'])->name('members.show');
Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');
Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('members.edit');
Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');



// /register(GET)- shows the registration form
// /register(POST)- saves the data into the database
// name()- gives your route a nickname so you can use it in blade like
// route(member.store)
