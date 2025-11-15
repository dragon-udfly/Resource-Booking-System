<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/admin', function() {
    return view('adminpanel');
})->name('admin');

Route::get('/createaccount', function() {
    return view('createaccount');
})->name('createacount');