<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\HallBookingController;
use App\Http\Controllers\QuarterController;
use App\Http\Controllers\QuarterAllocationController;

Route::get('/', function () {
    return view('home');
})->name('home');

use App\Http\Controllers\FileController;

Route::get('/homepage', function () {
    return view('homepage');
})->name('homepage');

Route::get('/help', [FileController::class, 'showHelp'])->name('help');

Route::get('/about', [FileController::class, 'showAbout'])->name('about');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [UserController::class, 'login'])->name('login.submit');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return view('adminpanel');
    })->name('admin');

    Route::get('/adminpreference', function () {
        return view('adminpreference');
    })->name('adminpreference');

    Route::post('/adminpreference/update', [UserController::class, 'updateAdminProfile'])->name('adminpreference.update');

    Route::get('/createaccount', [UserController::class, 'create'])->name('createaccount');
    Route::post('/createaccount', [UserController::class, 'store'])->name('createaccount.store');
    Route::delete('/users/clear', [UserController::class, 'clearUsers'])->name('users.clear');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/officers', [UserController::class, 'index'])->name('officers.index');
    Route::get('/gradesalary', [UserController::class, 'showGradeSalary'])->name('gradesalary.index');
    Route::match(['put', 'patch'], '/gradesalary', [UserController::class, 'updateGradeSalary'])->name('gradesalary.update');

    Route::get('/addhall', [HallController::class, 'create'])->name('halls.create');
    Route::post('/addhall', [HallController::class, 'store'])->name('halls.store');
    Route::get('/halls/{hall}/edit', [HallController::class, 'edit'])->name('halls.edit');
    Route::patch('/halls/{hall}', [HallController::class, 'update'])->name('halls.update');
    Route::delete('/halls/{hall}', [HallController::class, 'destroy'])->name('halls.destroy');

    Route::get('/modifyaccount', function () {
        return view('modifyaccount');
    });

    Route::get('/modifyhall', function () {
        return view('modifyhall');
    });

    // System Settings
    Route::get('/systemsetting', [App\Http\Controllers\SettingsController::class, 'index'])->name('systemsetting');
    Route::get('/system-status', [App\Http\Controllers\SettingsController::class, 'systemStatus'])->name('system.status');
    Route::post('/settings/email/test', [App\Http\Controllers\SettingsController::class, 'testEmail'])->name('settings.email.test');
    Route::post('/settings/backup/db', [App\Http\Controllers\SettingsController::class, 'backupDatabase'])->name('settings.backup.db');
    Route::post('/settings/restore/db', [App\Http\Controllers\SettingsController::class, 'restoreDatabase'])->name('settings.restore.db');
    Route::post('/settings/restore/halls', [App\Http\Controllers\SettingsController::class, 'restoreHalls'])->name('settings.restore.halls');
    Route::post('/settings/restore/quarters', [App\Http\Controllers\SettingsController::class, 'restoreQuarters'])->name('settings.restore.quarters');
    Route::post('/settings/restore/officers', [App\Http\Controllers\SettingsController::class, 'restoreOfficers'])->name('settings.restore.officers');
    Route::post('/settings/restore/grade-salary', [App\Http\Controllers\SettingsController::class, 'restoreGradeSalary'])->name('settings.restore.gradesalary');
    Route::post('/settings/restore/marking-scheme', [App\Http\Controllers\SettingsController::class, 'restoreMarkingScheme'])->name('settings.restore.markingscheme');
    Route::post('/settings/restore/memos', [App\Http\Controllers\SettingsController::class, 'restoreMemos'])->name('settings.restore.memos');
    Route::post('/settings/backup/halls', [App\Http\Controllers\SettingsController::class, 'backupHalls'])->name('settings.backup.halls');
    Route::post('/settings/backup/quarters', [App\Http\Controllers\SettingsController::class, 'backupQuarters'])->name('settings.backup.quarters');
    Route::post('/settings/backup/officers', [App\Http\Controllers\SettingsController::class, 'backupOfficers'])->name('settings.backup.officers');
    Route::post('/settings/backup/hall-bookings', [App\Http\Controllers\SettingsController::class, 'backupHallBookings'])->name('settings.backup.hallbookings');
    Route::post('/settings/backup/scheduled-applications', [App\Http\Controllers\SettingsController::class, 'backupScheduledApplications'])->name('settings.backup.scheduled');
    Route::post('/settings/backup/family-applications', [App\Http\Controllers\SettingsController::class, 'backupFamilyApplications'])->name('settings.backup.family');
    Route::post('/settings/backup/grade-salary', [App\Http\Controllers\SettingsController::class, 'backupGradeSalary'])->name('settings.backup.gradesalary');
    Route::post('/settings/backup/marking-scheme', [App\Http\Controllers\SettingsController::class, 'backupMarkingScheme'])->name('settings.backup.markingscheme');
    Route::post('/settings/backup/memos', [App\Http\Controllers\SettingsController::class, 'backupMemos'])->name('settings.backup.memos');

    Route::get('/modifyquarter', function () {
        return view('modifyquarter');
    });

    Route::get('/auditlog', [UserController::class, 'showAuditLog'])->name('auditlog');
    Route::delete('/auditlog/clear', [UserController::class, 'clearAuditLog'])->name('auditlog.clear');


    Route::delete('/bookings/clear', [HallBookingController::class, 'clearBookings'])->name('bookings.clear');
    Route::delete('/bookings/clear-rejected', [HallBookingController::class, 'clearRejectedBookings'])->name('bookings.clearRejected');
    Route::delete('/quarters/scheduled/clear-rejected', [QuarterAllocationController::class, 'clearRejectedScheduledApplications'])->name('quarters.scheduled.clearRejected');
    Route::delete('/quarters/family/clear-rejected', [QuarterAllocationController::class, 'clearRejectedFamilyApplications'])->name('quarters.family.clearRejected');
    Route::delete('/memos/clear-history', [App\Http\Controllers\MemoController::class, 'clearRespondedMemos'])->name('memos.clearResponded');

    Route::get('/quarters', [QuarterController::class, 'index'])->name('quarters.index');
    Route::get('/quarters/{quarter}/edit', [QuarterController::class, 'edit'])->name('quarters.edit');
    Route::delete('/quarters/{quarter}', [QuarterController::class, 'destroy'])->name('quarters.destroy');
    Route::patch('/quarters/{quarter}', [QuarterController::class, 'update'])->name('quarters.update');

    Route::get('/marking-scheme', [QuarterAllocationController::class, 'markingScheme'])->name('marking-scheme.edit');
    Route::put('/marking-scheme', [QuarterAllocationController::class, 'updateMarkingScheme'])->name('marking-scheme.update');

    Route::get('/addquarter', function () {
        return view('addquarter');
    })->name('addquarter');

    Route::post('/quarters', [QuarterController::class, 'store'])->name('quarters.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/preference', function () {
        return view('preference');
    })->name('preference');
    Route::post('/preference/profile/update', [UserController::class, 'updateUserProfile'])->name('preference.profile.update');
    Route::post('/preference/change-password', [UserController::class, 'changePassword'])->name('preference.changepassword');

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
    Route::get('/hall-bookings/{hallBooking}/review', [HallBookingController::class, 'review'])->name('hall_bookings.review');
    Route::post('/hall-bookings/{hallBooking}/cancel-approved', [HallBookingController::class, 'cancelApproved'])->name('hall_bookings.cancelApproved');
    Route::post('/hall-bookings/{hallBooking}/re-approve', [HallBookingController::class, 'reApprove'])->name('hall_bookings.reApprove');
    Route::get('/hall-bookings/{hallBooking}/processed', [HallBookingController::class, 'showProcessed'])->name('hall_bookings.processed');
    Route::get('/seequarters', [QuarterController::class, 'seeQuarters'])->name('seequarters');
    Route::get('/occupantdetails', [QuarterController::class, 'showOccupantDetails'])->name('occupantdetails');
    Route::get('/family-quarter-application/{id}/review', [QuarterAllocationController::class, 'showFamilyQuarterReview'])->name('family-quarter.review');
    Route::post('/family-quarter-application/{id}/allocate', [QuarterAllocationController::class, 'allocateFamilyQuarter'])->name('family-quarter.allocate');
    Route::post('/family-quarter-application/{id}/reject', [QuarterAllocationController::class, 'rejectFamilyQuarter'])->name('family-quarter.reject');
    Route::patch('/family-quarter-application/{id}/review', [QuarterAllocationController::class, 'updateFamilyQuarterReview'])->name('family-quarter.review.update');
    Route::get('/scheduled-quarter-application/{id}/review', [QuarterAllocationController::class, 'showScheduledQuarterReview'])->name('scheduled-quarter.review');
    Route::post('/scheduled-quarter-application/{id}/allocate', [QuarterAllocationController::class, 'allocateScheduledQuarter'])->name('scheduled-quarter.allocate');
    Route::post('/scheduled-quarter-application/{id}/restore', [QuarterAllocationController::class, 'restoreQuarterApplication'])->name('scheduled-quarter.restore');
    Route::post('/family-quarter-application/{id}/restore', [QuarterAllocationController::class, 'restoreQuarterApplication'])->name('family-quarter.restore');
    Route::post('/scheduled-quarter-application/{id}/cancel-allocation', [QuarterAllocationController::class, 'cancelScheduledQuarter'])->name('scheduled-quarter.cancel');
    Route::post('/family-quarter-application/{id}/cancel-allocation', [QuarterAllocationController::class, 'cancelFamilyQuarter'])->name('family-quarter.cancel');
    Route::delete('/scheduled-quarter-application/{id}/delete', [QuarterAllocationController::class, 'deleteScheduledQuarterApplication'])->name('scheduled-quarter.delete');
    Route::delete('/family-quarter-application/{id}/delete', [QuarterAllocationController::class, 'deleteFamilyQuarterApplication'])->name('family-quarter.delete');
    Route::get('/quarter-application/{id}/download-pdf', [QuarterAllocationController::class, 'downloadPdf'])->name('quarter.download-pdf');
    Route::get('/processed-application/scheduled/{id}', [QuarterAllocationController::class, 'showProcessedScheduled'])->name('history.view_scheduled');
    Route::get('/processed-application/family/{id}', [QuarterAllocationController::class, 'showProcessedFamily'])->name('history.view_family');

    Route::get('/history', [HallBookingController::class, 'showHistory'])->name('history');
    Route::get('/history/quarters', [QuarterAllocationController::class, 'showQuarterHistory'])->name('history.quarters');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Internal Memo Routes
    Route::get('/internal-memo', [App\Http\Controllers\MemoController::class, 'index'])->name('memo.index');
    Route::get('/internal-memo/fetch-inbox', [App\Http\Controllers\MemoController::class, 'fetchInbox'])->name('memo.fetch_inbox');
    Route::get('/internal-memo/fetch-outbox', [App\Http\Controllers\MemoController::class, 'fetchOutbox'])->name('memo.fetch_outbox');
    Route::post('/internal-memo/send', [App\Http\Controllers\MemoController::class, 'store'])->name('memo.send');
    Route::post('/internal-memo/{id}/respond', [App\Http\Controllers\MemoController::class, 'updateStatus'])->name('memo.respond');
    Route::post('/internal-memo/clear-read', [App\Http\Controllers\MemoController::class, 'clearRead'])->name('memo.clear_read');
    Route::post('/internal-memo/clear-sent', [App\Http\Controllers\MemoController::class, 'clearSent'])->name('memo.clear_sent');
    Route::get('/internal-memo/{id}', [App\Http\Controllers\MemoController::class, 'show'])->name('memo.show');
});

// Quarter
Route::post('/verify-quarter-requester', [QuarterAllocationController::class, 'verifyRequester'])->name('quarters.requester.verify');
Route::get('/bookquarter', [QuarterController::class, 'create'])->name('bookquarter');
Route::get('/familyquarter', [QuarterAllocationController::class, 'bookFamilyQuarters'])->name('familyquarter');
Route::post('/familyquarter', [QuarterAllocationController::class, 'storeFamilyQuarters'])->name('familyquarter.store');
Route::get('/scheduledquarter', [QuarterAllocationController::class, 'bookScheduledQuarters'])->name('scheduledquarter');
Route::post('/scheduledquarter', [QuarterAllocationController::class, 'storeScheduledQuarters'])->name('scheduledquarter.store');

// Hall
Route::post('/verify-requester', [HallBookingController::class, 'verifyRequester'])->name('requester.verify');
Route::get('/bookhall', [HallBookingController::class, 'create'])->name('halls.book');
Route::post('/bookhall', [HallBookingController::class, 'store'])->name('hall_bookings.store');
Route::get('/api/halls/available', [HallController::class, 'getAvailableHalls'])->name('halls.available');
Route::get('/hall-overview', [HallController::class, 'showOverview'])->name('halls.overview');
Route::get('/hallschedule', [HallBookingController::class, 'showSchedule'])->name('halls.schedule');

// temporary hidden
Route::get('/developers', function () {
    return view('developers');
});
