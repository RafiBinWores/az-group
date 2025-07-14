<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CuttingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmbroideryPrintController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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
        Route::delete('/roles/{role}', 'destroy')->name('roles.destroy');
    });

    // User routes
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::get('/users/create', 'create')->name('users.create');
        Route::post('/users', 'store')->name('users.store');
        Route::get('/users/{user}/edit', 'edit')->name('users.edit');
        Route::put('/users/{user}', 'update')->name('users.update');
        Route::delete('/users/{user}', 'destroy')->name('users.destroy');
    });

    // Order routes
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('orders.index');
        Route::get('/orders/create', 'create')->name('orders.create');
        Route::post('/orders', 'store')->name('orders.store');
        Route::get('/orders/{order}/edit', 'edit')->name('orders.edit');
        Route::put('/orders/{order}', 'update')->name('orders.update');
        Route::delete('/orders/{order}', 'destroy')->name('orders.destroy');
    });

    // Cutting routes
    Route::controller(CuttingController::class)->group(function () {
        Route::get('/cutting-report', 'index')->name('cutting.index');
        Route::get('/cutting-report/create', 'create')->name('cutting.create');
        Route::post('/cutting-report', 'store')->name('cutting.store');
        Route::get('/cutting-report/{cutting}/show', 'show')->name('cutting.show');
        Route::get('/cutting-report/{cutting}/edit', 'edit')->name('cutting.edit');
        Route::put('/cutting-report/{cutting}', 'update')->name('cutting.update');
        Route::delete('/cutting-report/{cutting}', 'destroy')->name('cutting.destroy');
        Route::get('/cutting-report/{cutting}/export', 'export')->name('cutting.export');
    });

    // Embroidery Or Print routes
    Route::controller(EmbroideryPrintController::class)->group(function () {
        Route::get('/embroidery-prints', 'index')->name('embroidery_prints.index');
        Route::get('/embroidery-prints/create', 'create')->name('embroidery_prints.create');
        Route::post('/embroidery-prints', 'store')->name('embroidery_prints.store');
        Route::get('/embroidery-prints/{embroidery_print}/show', 'show')->name('embroidery_prints.show');
        Route::get('/embroidery-prints/{embroidery_print}/edit', 'edit')->name('embroidery_prints.edit');
        Route::put('/embroidery-prints/{embroidery_print}', 'update')->name('embroidery_prints.update');
        Route::delete('/embroidery-prints/{embroidery_print}', 'destroy')->name('embroidery_prints.destroy');
        Route::get('/embroidery-prints/{embroidery_print}/export', 'export')->name('embroidery_prints.export');
    });

});
