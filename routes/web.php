<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LoginsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\GoogleSheetController;
use App\Http\Controllers\CallReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimerController;
use App\Http\Controllers\UserController;


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard/admin/index', [UserController::class, 'index'])->name('user.admin');
    Route::get('/dashboard/admin/create', [UserController::class, 'admincreate'])->name('users.admin.create');
    Route::post('/dashboard/admin/store', [UserController::class, 'adminstore'])->name('users.admin.store');
    Route::get('/dashboard/admin/{id}/edit', [UserController::class, 'adminedit'])->name('users.admin.edit');
    Route::put('/dashboard/admin/update/{id}', [UserController::class, 'adminupdate'])->name('users.admin.update');
    Route::delete('/dashboard/admin/destroy/{id}', [UserController::class, 'admindestroy'])->name('users.admin.destroy');

    Route::get('/dashboard/admin/junior', [UserController::class, 'junior'])->name('user.junior');
    Route::get('/dashboard/admin/junior/create', [UserController::class, 'juniorcreate'])->name('users.junior.create');
    Route::post('/dashboard/admin/junior/store', [UserController::class, 'juniorstore'])->name('users.junior.store');
    Route::get('/dashboard/admin/junior/{id}/edit', [UserController::class, 'junioredit'])->name('users.junior.edit');
    Route::put('/dashboard/admin/junior/update/{id}', [UserController::class, 'juniorupdate'])->name('users.junior.update');
    Route::delete('/dashboard/admin/junior/destroy/{id}', [UserController::class, 'juniordestroy'])->name('users.junior.destroy');

    Route::get('/dashboard/admin/senior', [UserController::class, 'senior'])->name('user.senior');
    Route::get('/dashboard/admin/senior/create', [UserController::class, 'seniorcreate'])->name('users.senior.create');
    Route::post('/dashboard/admin/senior/store', [UserController::class, 'seniorstore'])->name('users.senior.store');
    Route::get('/dashboard/admin/senior/{id}/edit', [UserController::class, 'senioredit'])->name('users.senior.edit');
    Route::put('/dashboard/admin/senior/update/{id}', [UserController::class, 'seniorupdate'])->name('users.senior.update');
    Route::delete('/dashboard/admin/senior/destroy/{id}', [UserController::class, 'seniordestroy'])->name('users.senior.destroy');

    Route::get('/dashboard/admin/trainer', [UserController::class, 'trainer'])->name('user.trainer');
    Route::get('/dashboard/admin/trainer/create', [UserController::class, 'trainercreate'])->name('users.trainer.create');
    Route::post('/dashboard/admin/trainer/store', [UserController::class, 'trainerstore'])->name('users.trainer.store');
    Route::get('/dashboard/admin/trainer/{id}/edit', [UserController::class, 'traineredit'])->name('users.trainer.edit');
    Route::put('/dashboard/admin/trainer/update/{id}', [UserController::class, 'trainerupdate'])->name('users.trainer.update');
    Route::delete('/dashboard/admin/trainer/update/{id}', [UserController::class, 'trainerdestroy'])->name('users.trainer.destroy');

    Route::get('/dashboard/admin/accountant', [UserController::class, 'accountant'])->name('user.accountant');
    Route::get('/dashboard/admin/accountant/create', [UserController::class, 'accountantcreate'])->name('users.accountant.create');
    Route::post('/dashboard/admin/accountant/store', [UserController::class, 'accountantstore'])->name('users.accountant.store');
    Route::get('/dashboard/admin/accountant/{id}/edit', [UserController::class, 'accountantedit'])->name('users.accountant.edit');
    Route::put('/dashboard/admin/accountant/update/{id}', [UserController::class, 'accountantupdate'])->name('users.accountant.update');
    Route::delete('/dashboard/admin/accountant/update/{id}', [UserController::class, 'accountantdestroy'])->name('users.accountant.destroy');

    Route::get('/dashboard/admin/customer', [UserController::class, 'customer'])->name('user.customer');
    Route::get('/dashboard/admin/customer/create', [UserController::class, 'customercreate'])->name('users.customer.create');
    Route::post('/dashboard/admin/customer/store', [UserController::class, 'customerstore'])->name('users.customer.store');
    Route::get('/dashboard/admin/customer/{id}/edit', [UserController::class, 'customeredit'])->name('users.customer.edit');
    Route::put('/dashboard/admin/customer/update/{id}', [UserController::class, 'customerupdate'])->name('users.customer.update');
    Route::delete('/dashboard/admin/customer/destroy/{id}', [UserController::class, 'customerdestroy'])->name('users.customer.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.admin');
    Route::get('/dashboard/junior', [DashboardController::class, 'junior'])->name('dashboard.junior');
    Route::get('/dashboard/senior', [DashboardController::class, 'senior'])->name('dashboard.senior');
    Route::get('/dashboard/customer', [DashboardController::class, 'customer'])->name('dashboard.customer');
    Route::get('/dashboard/accountant', [DashboardController::class, 'accountant'])->name('dashboard.accountant');
    Route::get('/dashboard/trainer', [DashboardController::class, 'trainer'])->name('dashboard.trainer');
    Route::get('/button/status', [DashboardController::class, 'getButtonStatus'])->name('button.status');
    Route::post('/dashboard/start-timer', [DashboardController::class, 'startTimer'])->name('timer.start');
    Route::post('/dashboard/start-timer-hide', [DashboardController::class, 'startTimerHide'])->name('timer.starthide');

    Route::get('/dashboard/admin/calendar/{month?}/{year?}', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/dashboard/accountant/calendar/{month?}/{year?}', [CalendarController::class, 'accountantUser'])->name('calendar.accountantUser');
    Route::get('/dashboard/trainer/calendar/{month?}/{year?}', [CalendarController::class, 'trainerUser'])->name('calendar.trainerUser');
    Route::get('/dashboard/junior/calendar/', [CalendarController::class, 'juniorUser'])->name('calendar.junior');
    Route::get('/dashboard/junior/calendar/events', [CalendarController::class, 'juniorEvents'])->name('calendar.juniorEvents');
    Route::get('/dashboard/senior/calendar/', [CalendarController::class, 'seniorUser'])->name('calendar.seniorUser');
    Route::get('/dashboard/senior/calendar/alljuniorlist', [CalendarController::class, 'allJuniorlist'])->name('calendar.allJuniorlist');
    Route::get('/dashboard/senior/calendar/alljunior/{userId}', [CalendarController::class, 'alljuniorUser'])->name('calendar.alljuniorUser');
    Route::get('/dashboard/senior/calendar/alljuniorevents/{userId}', [CalendarController::class, 'getallJuniorEvents'])->name('calendar.allJuniorEvents');
    Route::get('/dashboard/senior/calendar/events', [CalendarController::class, 'getSeniorEvents'])->name('calendar.seniorEvents');
    Route::post('/dashboard/calendar/update-status', [CalendarController::class, 'updateStatus'])->name('calendar.updateStatus');

    Route::get('/dashboard/admin/google-sheet', [GoogleSheetController::class, 'index'])->name('google.sheet.index');
    Route::post('/dashboard/admin/google-sheet/fetch', [GoogleSheetController::class, 'adminfetch'])->name('google.sheet.adminfetch');
    Route::patch('/dashboard/admin/google-sheet/update/{id}', [GoogleSheetController::class, 'adminupdate'])->name('google.sheet.adminupdate');
    Route::post('/dashboard/admin/google-sheet/store', [GoogleSheetController::class, 'adminstore'])->name('google.sheet.adminstore');
    Route::post('/dashboard/admin/google-sheet/adminupdate', [GoogleSheetController::class, 'adminupdate'])->name('adminupdate');
    Route::post('/dashboard/admin/google-sheet/adminstore', [GoogleSheetController::class, 'adminstore'])->name('adminstore');
    Route::get('/dashboard/admin/google-sheet/view-resume/{id}', [GoogleSheetController::class, 'viewadminResume'])->name('view.admin.resume');
    Route::get('/dashboard/admin/google-sheet/download-resume/{id}', [GoogleSheetController::class, 'downloadadminResume'])->name('download.admin.resume');

    Route::get('/dashboard/senior/google-sheet', [GoogleSheetController::class, 'senior'])->name('google.sheet.senior');
    Route::post('/dashboard/senior/google-sheet/fetch', [GoogleSheetController::class, 'seniorfetch'])->name('google.sheet.seniorfetch');
    Route::patch('/dashboard/senior/google-sheet/pdfupdate/{id}', [GoogleSheetController::class, 'seniorpdfupdate'])->name('google.sheet.seniorpdfupdate');
    Route::post('/dashboard/senior/google-sheet/pdfstore', [GoogleSheetController::class, 'seniorpdfstore'])->name('google.sheet.seniorpdfstore');
    Route::post('/dashboard/senior/google-sheet/seniorupdate', [GoogleSheetController::class, 'seniorupdate'])->name('seniorupdate');
    Route::post('/dashboard/senior/google-sheet/seniorstore', [GoogleSheetController::class, 'seniorstore'])->name('seniorstore');
    Route::get('/dashboard/senior/google-sheet/view-resume/{id}', [GoogleSheetController::class, 'viewseniorResume'])->name('view.resume');
    Route::get('/dashboard/senior/google-sheet/download-resume/{id}', [GoogleSheetController::class, 'downloadseniorResume'])->name('download.resume');
    Route::get('/dashboard/senior/google-sheet/search', [GoogleSheetController::class, 'seniorSuggestions'])->name('senior.suggestions');

    Route::get('/dashboard/junior/google-sheet', [GoogleSheetController::class, 'junior'])->name('google.sheet.junior');
    Route::post('/dashboard/junior/google-sheet/fetch', [GoogleSheetController::class, 'juniorfetch'])->name('google.sheet.juniorfetch');
    Route::patch('/dashboard/junior/google-sheet/pdfupdate/{id}', [GoogleSheetController::class, 'juniorpdfupdate'])->name('google.sheet.juniorpdfupdate');
    Route::post('/dashboard/junior/google-sheet/pdfstore', [GoogleSheetController::class, 'juniorpdfstore'])->name('google.sheet.juniorpdfstore');
    Route::post('/dashboard/junior/google-sheet/juniorstore', [GoogleSheetController::class, 'juniorstore'])->name('juniorstore');
    Route::post('/dashboard/junior/google-sheet/juniorupdate', [GoogleSheetController::class, 'juniorupdate'])->name('juniorupdate');
    Route::get('/dashboard/junior/google-sheet/view-resume/{id}', [GoogleSheetController::class, 'viewjuniorResume'])->name('view.resume');
    Route::get('/dashboard/junior/google-sheet/download-resume/{id}', [GoogleSheetController::class, 'downloadjuniorResume'])->name('download.resume');

    Route::get('/dashboard/trainer/google-sheet', [GoogleSheetController::class, 'trainer'])->name('google.sheet.trainer');
    Route::post('/dashboard/trainer/google-sheet/fetch', [GoogleSheetController::class, 'trainerfetch'])->name('google.sheet.trainerfetch');
    Route::patch('/dashboard/trainer/google-sheet/pdfupdate/{id}', [GoogleSheetController::class, 'trainerpdfupdate'])->name('google.sheet.trainerpdfupdate');
    Route::post('/dashboard/trainer/google-sheet/pdfstore', [GoogleSheetController::class, 'trainerpdfstore'])->name('google.sheet.trainerpdfstore');
    Route::post('/dashboard/trainer/google-sheet/trainerstore', [GoogleSheetController::class, 'trainertore'])->name('trainerstore');
    Route::post('/dashboard/trainer/google-sheet/trainerupdate', [GoogleSheetController::class, 'trainerupdate'])->name('trainerupdate');
    Route::get('/dashboard/trainer/google-sheet/view-resume/{id}', [GoogleSheetController::class, 'viewtrainerResume'])->name('view.resume');
    Route::get('/dashboard/trainer/google-sheet/download-resume/{id}', [GoogleSheetController::class, 'downloadtrainerResume'])->name('download.resume');

    Route::get('/dashboard/accountant/google-sheet', [GoogleSheetController::class, 'accountant'])->name('google.sheet.accountant');
    Route::post('/dashboard/accountant/google-sheet/fetch', [GoogleSheetController::class, 'accountantfetch'])->name('google.sheet.accountantfetch');
    Route::patch('/dashboard/accountant/google-sheet/pdfupdate/{id}', [GoogleSheetController::class, 'accountantpdfupdate'])->name('google.sheet.accountantpdfupdate');
    Route::post('/dashboard/accountant/google-sheet/pdfstore', [GoogleSheetController::class, 'accountantpdfstore'])->name('google.sheet.accountantpdfstore');
    Route::post('/dashboard/accountant/google-sheet/accountantstore', [GoogleSheetController::class, 'accountantstore'])->name('accountantstore');
    Route::post('/dashboard/accountant/google-sheet/accountantupdate', [GoogleSheetController::class, 'accountantupdate'])->name('accountantupdate');
    Route::get('/dashboard/accountant/google-sheet/view-resume/{id}', [GoogleSheetController::class, 'viewaccountantResume'])->name('view.resume');
    Route::get('/dashboard/accountant/google-sheet/download-resume/{id}', [GoogleSheetController::class, 'downloadaccountantResume'])->name('download.resume');

    Route::get('/dashboard/admin/call-reports', [CallReportController::class, 'index'])->name('call.reports.index');
    Route::get('/dashboard/junior/call-reports', [CallReportController::class, 'junior'])->name('call.reports.junior');
    Route::get('/dashboard/juniormonthly/call-reports', [CallReportController::class, 'juniormonthly'])->name('call.reports.juniormonthly');
    Route::get('/dashboard/senior/call-reports', [CallReportController::class, 'senior'])->name('call.reports.senior');
    Route::get('/dashboard/seniormonthly/call-reports', [CallReportController::class, 'seniormonthly'])->name('call.reports.seniormonthly');
    Route::get('/dashboard/alljuniorlist/call-reports', [CallReportController::class, 'alljuniorlist'])->name('call.reports.alljuniorlist');
    Route::get('/dashboard/alljuniormonthly/call-reports/{userId}', [CallReportController::class, 'alljuniormonthly'])->name('call.reports.alljuniormonthly');
    Route::get('/dashboard/alljuniordaily/call-reports/{userId}', [CallReportController::class, 'alljuniordaily'])->name('call.reports.alljuniordaily');

    Route::match(['get', 'post'], '/timer/update', [DashboardController::class, 'updateTimer'])->name('timer.update');
    Route::get('/dashboard/senior/seniortimer', [TimerController::class, 'seniorTimers'])->name('timer.senior');
    Route::get('/timer/all-juniors', [TimerController::class, 'allJuniorTimers'])->name('timer.alljuniors');
    Route::get('/dashboard/junior/juniortimer', [TimerController::class, 'juniorTimers'])->name('timer.junior');
    Route::post('/timer/toggle-button-status', [TimerController::class, 'toggleButtonStatus'])->name('timer.toggleButtonStatus');
    Route::post('/timer/toggle-all-status', [TimerController::class, 'toggleAllStatus'])->name('timer.toggleAllStatus');
    Route::get('/dashboard/admin/timer-settings', [TimerController::class, 'index'])->name('timer.admin');
    Route::post('/timers/work-day', [TimerController::class, 'updateWorkDay'])->name('timer.updateWorkDay');
    Route::post('/timers/base-time', [TimerController::class, 'updateBaseTime'])->name('timer.updateBaseTime');
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
