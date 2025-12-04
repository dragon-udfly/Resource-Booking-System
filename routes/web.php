<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\HallBookingController;

Route::middleware(['db-access'])->group(function () {
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
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/officers', [UserController::class, 'index'])->name('officers.index');
        Route::get('/seeofficers', [UserController::class, 'seeOfficers'])->name('seeofficers');

        Route::get('/addhall', [HallController::class, 'create'])->name('halls.create');
        Route::post('/addhall', [HallController::class, 'store'])->name('halls.store');
        Route::get('/halls/{hall}/edit', [HallController::class, 'edit'])->name('halls.edit');
        Route::patch('/halls/{hall}', [HallController::class, 'update'])->name('halls.update');
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
        Route::get('/seehalls', [HallController::class, 'seeHalls'])->name('seehalls');

        Route::get('/dashboard', [UserController::class, 'showDashboard'])->name('dashboard');
        Route::get('/seeauditlog', [UserController::class, 'seeAuditLog'])->name('seeauditlog');
    });

    Route::get('/halldashboard', function(){
        return view('halldashboard');
    });

    Route::get('/quarterdashboard', function(){
        return view('quarterdashboard');
    });

    Route::get('/bookhall', [HallBookingController::class, 'create'])->name('halls.book');
    Route::post('/bookhall', [HallBookingController::class, 'store'])->name('hall_bookings.store');

    Route::get('/hallschedule', [HallBookingController::class, 'showSchedule'])->name('halls.schedule');
});
