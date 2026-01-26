<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\HallBookingController;
use App\Http\Controllers\QuarterController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/homepage', function () {
    return view('homepage');
})->name('homepage');

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

    Route::get('/addhall', [HallController::class, 'create'])->name('halls.create');
    Route::post('/addhall', [HallController::class, 'store'])->name('halls.store');
    Route::get('/halls/{hall}/edit', [HallController::class, 'edit'])->name('halls.edit');
    Route::patch('/halls/{hall}', [HallController::class, 'update'])->name('halls.update');
    Route::delete('/halls/{hall}', [HallController::class, 'destroy'])->name('halls.destroy');

    Route::get('/modifyaccount', function(){
        return view('modifyaccount');
    });

        Route::get('/modifyhall', function(){
            return view('modifyhall');
        });
    
        Route::get('/systemsetting', function(){
            return view('systemsetting');
        })->name('systemsetting');
    Route::get('/auditlog', [UserController::class, 'showAuditLog'])->name('auditlog');
    Route::delete('/auditlog/clear', [UserController::class, 'clearAuditLog'])->name('auditlog.clear');
    Route::delete('/halls/clear', [HallController::class, 'clearHalls'])->name('halls.clear');
    Route::delete('/bookings/clear', [HallBookingController::class, 'clearBookings'])->name('bookings.clear');
    Route::delete('/bookings/clear-rejected', [HallBookingController::class, 'clearRejectedBookings'])->name('bookings.clearRejected');
    Route::delete('/users/clear', [UserController::class, 'clearUsers'])->name('users.clear');

    Route::get('/quarters', [QuarterController::class, 'index'])->name('quarters.index');
    Route::get('/quarters/{quarter}/edit', [QuarterController::class, 'edit'])->name('quarters.edit');
    Route::delete('/quarters/{quarter}', [QuarterController::class, 'destroy'])->name('quarters.destroy');
    Route::patch('/quarters/{quarter}', [QuarterController::class, 'update'])->name('quarters.update');

    Route::get('/addquarter', function(){
        return view('addquarter');
    })->name('addquarter');

    Route::post('/quarters', [QuarterController::class, 'store'])->name('quarters.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/preference', function(){
        return view('preference');
    })->name('preference');
    Route::post('/password/change', [UserController::class, 'changePassword'])->name('password.change');

    // Hall routes
    Route::get('/halls', [HallController::class, 'index'])->name('halls.index');
    Route::get('/seehalls', [HallController::class, 'seeHalls'])->name('seehalls');
    Route::get('/seeofficers', [UserController::class, 'seeOfficers'])->name('seeofficers');

    Route::get('/dashboard', [UserController::class, 'showDashboard'])->name('dashboard');
    Route::get('/seeauditlog', [UserController::class, 'seeAuditLog'])->name('seeauditlog');

    // Requester Booking Management Routes
    Route::patch('/hall-bookings/{hallBooking}', [HallBookingController::class, 'updateBooking'])->name('hall_bookings.update_by_requester');
    Route::delete('/hall-bookings/{hallBooking}', [HallBookingController::class, 'destroyBooking'])->name('hall_bookings.destroy_by_requester');
    Route::get('/hall-bookings/{hallBooking}/download', [HallBookingController::class, 'downloadPDF'])->name('hall_bookings.download');

    // Approval Routes
    Route::post('/hall-bookings/{hallBooking}/approve', [HallBookingController::class, 'approve'])->name('hall_bookings.approve');
    Route::post('/hall-bookings/{hallBooking}/reject', [HallBookingController::class, 'reject'])->name('hall_bookings.reject');
    Route::post('/hall-bookings/{hallBooking}/cancel-approved', [HallBookingController::class, 'cancelApproved'])->name('hall_bookings.cancelApproved');

    Route::get('/history', [HallBookingController::class, 'showHistory'])->name('history');

    Route::get('/seequarters', [QuarterController::class, 'seeQuarters'])->name('seequarters');
    Route::get('/occupantdetails', [QuarterController::class, 'showOccupantDetails'])->name('occupantdetails');
});

Route::post('/verify-requester', [HallBookingController::class, 'verifyRequester'])->name('requester.verify');

Route::get('/quarterapplication', function(){
    return view('quarterapplication');
})->name('quarterapplication');

Route::post('/quarterapplication', [QuarterApplicationController::class, 'store'])->name('quarterapplication.store');


Route::get('/bookhall', [HallBookingController::class, 'create'])->name('halls.book');
Route::post('/bookhall', [HallBookingController::class, 'store'])->name('hall_bookings.store');

Route::get('/api/halls/available', [HallController::class, 'getAvailableHalls'])->name('halls.available');

Route::get('/hall-overview', [HallController::class, 'showOverview'])->name('halls.overview');

Route::get('/hallschedule', [HallBookingController::class, 'showSchedule'])->name('halls.schedule');





Route::get('/modifyquarter', function(){
    return view('modifyquarter');
});

Route::get('/developers', function () {
    return view('developers');
});
