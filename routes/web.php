<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\LoginsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\GoogleSheetController;
use App\Http\Controllers\CallReportController;


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleDashboards\JuniorDashboardController;
use App\Http\Controllers\RoleDashboards\SeniorDashboardController;
use App\Http\Controllers\RoleDashboards\CustomerDashboardController;
use App\Http\Controllers\RoleDashboards\AccountantDashboardController;
use App\Http\Controllers\RoleDashboards\TrainerDashboardController;
use App\Http\Controllers\RoleDashboards\AdminDashboardController;
use App\Http\Controllers\TimerController;

Route::middleware(['auth'])->group(function () {
    // Default dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Separate dashboards per role
    Route::get('/dashboard/junior', [JuniorDashboardController::class, 'index'])->name('dashboard.junior');
    Route::get('/dashboard/senior', [SeniorDashboardController::class, 'index'])->name('dashboard.senior');
    Route::get('/dashboard/customer', [CustomerDashboardController::class, 'index'])->name('dashboard.customer');
    Route::get('/dashboard/accountant', [AccountantDashboardController::class, 'index'])->name('dashboard.accountant');
    Route::get('/dashboard/trainer', [TrainerDashboardController::class, 'index'])->name('dashboard.trainer');
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');

    Route::get('/dashboard/admin/calendar/{month?}/{year?}', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/dashboard/senior/calendar/{month?}/{year?}', [CalendarController::class, 'seniorUser'])->name('calendar.seniorUser');
    Route::get('/dashboard/junior/calendar/', [CalendarController::class, 'juniorUser'])->name('calendar.juniorUser');
    Route::get('/dashboard/junior/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.juniorEvents');
    Route::post('/dashboard/calendar/update-status', [CalendarController::class, 'updateStatus'])->name('calendar.updateStatus');

    Route::get('/dashboard/admin/google-sheet', [GoogleSheetController::class, 'index'])->name('google.sheet.index');
    Route::post('/dashboard/admin/google-sheet/fetch', [GoogleSheetController::class, 'adminfetch'])->name('google.sheet.adminfetch');
    Route::patch('/dashboard/admin/google-sheet/update/{id}', [GoogleSheetController::class, 'adminupdate'])->name('google.sheet.adminupdate');
    Route::post('/dashboard/admin/google-sheet/store', [GoogleSheetController::class, 'adminstore'])->name('google.sheet.adminstore');

    Route::get('/dashboard/senior/google-sheet', [GoogleSheetController::class, 'senior'])->name('google.sheet.senior');
    Route::post('/dashboard/senior/google-sheet/fetch', [GoogleSheetController::class, 'seniorfetch'])->name('google.sheet.seniorfetch');
    // Route::patch('/dashboard/senior/google-sheet/update/{id}', [GoogleSheetController::class, 'seniorupdate'])->name('google.sheet.seniorupdate');
    // Route::post('/dashboard/senior/google-sheet/store', [GoogleSheetController::class, 'seniorstore'])->name('google.sheet.seniorstore');
    Route::patch('/dashboard/senior/google-sheet/pdfupdate/{id}', [GoogleSheetController::class, 'seniorpdfupdate'])->name('google.sheet.seniorpdfupdate');
    Route::post('/dashboard/senior/google-sheet/pdfstore', [GoogleSheetController::class, 'seniorpdfstore'])->name('google.sheet.seniorpdfstore');

    Route::get('/dashboard/junior/google-sheet', [GoogleSheetController::class, 'junior'])->name('google.sheet.junior');
    Route::post('/dashboard/junior/google-sheet/fetch', [GoogleSheetController::class, 'juniorfetch'])->name('google.sheet.juniorfetch');
    // Route::patch('/dashboard/junior/google-sheet/update/{id}', [GoogleSheetController::class, 'juniorupdate'])->name('google.sheet.juniorupdate');
    // Route::post('/dashboard/junior/google-sheet/store', [GoogleSheetController::class, 'juniorstore'])->name('google.sheet.juniorstore');
    Route::patch('/dashboard/junior/google-sheet/pdfupdate/{id}', [GoogleSheetController::class, 'juniorpdfupdate'])->name('google.sheet.juniorpdfupdate');
    Route::post('/dashboard/junior/google-sheet/pdfstore', [GoogleSheetController::class, 'juniorpdfstore'])->name('google.sheet.juniorpdfstore');

    Route::get('/dashboard/admin/call-reports', [CallReportController::class, 'index'])->name('call.reports.index');
    Route::get('/dashboard/junior/call-reports', [CallReportController::class, 'junior'])->name('call.reports.junior');
    Route::get('/dashboard/senior/call-reports', [CallReportController::class, 'senior'])->name('call.reports.senior');

    Route::match(['get','post'], '/timer/update', [DashboardController::class, 'updateTimer'])->name('timer.update');
    Route::get('/dashboard/senior/seniortimer', [TimerController::class, 'seniorTimers'])->name('timer.senior');
    Route::match(['get','post'], '/timer/updatejunior', [TimerController::class, 'updateTimer'])->name('timer.updatejunior');
    Route::get('/timer/all-juniors', [TimerController::class, 'allJuniorTimers'])->name('timer.alljuniors');
    Route::get('/dashboard/admin/admintimer', [TimerController::class, 'adminTimers'])->name('timer.admin');
    Route::get('/dashboard/junior/juniortimer', [TimerController::class, 'juniorTimers'])->name('timer.junior');
    Route::post('/timer/toggle-button-status', [TimerController::class, 'toggleButtonStatus'])->name('timer.toggleButtonStatus');
    Route::post('/timer/toggle-all-status', [TimerController::class, 'toggleAllStatus'])->name('timer.toggleAllStatus');
    Route::post('/dashboard/junior/google-sheet/juniorstore', [GoogleSheetController::class, 'juniorstore'])->name('juniorstore');
    Route::post('/dashboard/junior/google-sheet/juniorupdate', [GoogleSheetController::class, 'juniorupdate'])->name('juniorupdate');
    Route::post('/dashboard/senior/google-sheet/seniorstore', [GoogleSheetController::class, 'seniorstore'])->name('seniorstore');
    Route::post('/dashboard/senior/google-sheet/seniorupdate', [GoogleSheetController::class, 'seniorupdate'])->name('seniorupdate');

    
});

Route::get('/admin/logins', [LoginsController::class, 'index'])->name('logins');
Route::get('/', [Controller::class, 'index'])->name('home');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/registersubmit', [RegisterController::class, 'register'])->name('register.submit');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/loginsubmit', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/resumes/upload/{id}', [ResumeController::class, 'upload'])->name('resumes.upload')->middleware('auth');
Route::patch('/resumes/{id}/status', [ResumeController::class, 'updateStatus'])->name('resumes.updateStatus');
Route::patch('/payment/{id}/status', [PaymentController::class, 'updateStatus'])->name('payment.updateStatus');
Route::patch('/training/{id}/trastatus', [PaymentController::class, 'traupdateStatus'])->name('training.updateStatus');
Route::get('/login-history', [LoginController::class, 'loginHistory'])->name('login.history');





