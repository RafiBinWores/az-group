<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;


// Login route
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Show form to request reset link
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Send reset link
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Show reset form with token
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Handle new password submission
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


// Handle login POST request
Route::post('/login', [LoginController::class, 'login'])->name('auth.login.submit');

// Authentication routes
Route::middleware(['auth'])->group(function () {

    // Dashboard route
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard.index');
        Route::get('/logout', 'logout')->name('auth.logout');
    });

    // Role routes
    Route::controller(RoleController::class)->group(function () {
        Route::get('/roles', 'index')->name('roles.index');
        Route::get('/roles/create', 'create')->name('roles.create');
        Route::post('/roles', 'store')->name('roles.store');
        Route::get('/roles/{role}/edit', 'edit')->name('roles.edit');
        Route::put('/roles/{role}', 'update')->name('roles.update');
//         Route::put('/roles/{role}', function ($role) {
//     return response()->json(['role_param' => $role]);
// });
        Route::delete('/roles/{role}', 'destroy')->name('roles.destroy');
    });
});
