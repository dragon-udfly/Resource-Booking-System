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

Route::get('/officers', function(){
    return view('officers');
});

Route::get('/preference', function(){
    return view('preference');
});

Route::get('/quarters', function(){
    return view('quarters');
});

Route::get('/addhall', function(){
    return view('addhall');
});

Route::get('/addquarter', function(){
    return view('addquarter');
});