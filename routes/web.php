<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [UserController::class, 'login'])->name('login.submit');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/admin', function() {
    return view('adminpanel');
})->name('admin');

Route::get('/createaccount', function() {
    return view('createaccount');
})->name('createacount');

Route::get('/officers', [UserController::class, 'index'])->name('officers');

Route::get('/preference', function(){
    return view('preference');
});

Route::get('/quarters', function(){
    return view('quarters');
});

Route::get('/halls', function(){
    return view('halls');
});

Route::get('/addhall', function(){
    return view('addhall');
});

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

Route::get('/auditlog', function(){
    return view('auditlog');
});

// for common user dashboard
// for demonstration only
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