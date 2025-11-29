<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HallController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [UserController::class, 'login'])->name('login.submit');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function() {
        return view('adminpanel');
    })->name('admin');

    Route::get('/createaccount', [UserController::class, 'create'])->name('createaccount');
    Route::post('/createaccount', [UserController::class, 'store'])->name('createaccount.store');

    Route::get('/officers', [UserController::class, 'index'])->name('officers');

    Route::get('/addhall', [HallController::class, 'create'])->name('halls.create');
    Route::post('/addhall', [HallController::class, 'store'])->name('halls.store');
    Route::delete('/halls/{hall}', [HallController::class, 'destroy'])->name('halls.destroy');

    Route::get('/addquarter', function(){
        return view('addquarter');
    });

    Route::get('/modifyaccount', function(){
        return view('modifyaccount');
    });

    Route::get('/modifyquarter', function(){
        return view('modifyquarter');
    });

    Route::get('/modifyhall', function(){
        return view('modifyhall');
    });

    Route::get('/auditlog', [UserController::class, 'showAuditLog'])->name('auditlog');
    Route::delete('/auditlog/clear', [UserController::class, 'clearAuditLog'])->name('auditlog.clear');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/preference', function(){
        return view('preference');
    })->name('preference');
    Route::post('/password/change', [UserController::class, 'changePassword'])->name('password.change');

    // Hall routes
    Route::get('/halls', [HallController::class, 'index'])->name('halls.index');
});

Route::get('/dashboard', function(){
    return view('dashboard');
});

Route::get('/halldashboard', function(){
    return view('halldashboard');
});

Route::get('/quarterdashboard', function(){
    return view('quarterdashboard');
});

// public content 
Route::get('/privacy_notice', function(){
    return view('privacy_notice');
});

Route::get('/user_agreement', function(){
    return view('user_agreement');
});