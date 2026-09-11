<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ParentsBookingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\TrainingEmailTestController;
use App\Http\Controllers\Admin\UserGroupController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () { return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('admin.login'); })->name('home');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.submit');

    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::middleware('admin.permission:admin_privilages')->group(function () {
            Route::get('/parents-booking', [ParentsBookingController::class, 'index'])->name('parents.booking.index');
            Route::post('/parents-booking/upload', [ParentsBookingController::class, 'upload'])->name('parents.booking.upload');
            Route::post('/parents-booking/import', [ParentsBookingController::class, 'import'])->name('parents.booking.import');
            Route::get('/parents-booking/parents/check-email', [ParentsBookingController::class, 'checkParentEmail'])->name('parents.booking.parents.check-email');
            Route::post('/parents-booking/children', [ParentsBookingController::class, 'storeChild'])->name('parents.booking.children.store');
            Route::put('/parents-booking/children/{child}', [ParentsBookingController::class, 'updateChild'])->name('parents.booking.children.update');
            Route::delete('/parents-booking/children/{child}', [ParentsBookingController::class, 'deleteChild'])->name('parents.booking.children.delete');
            Route::get('/bookings/session-wise', [BookingController::class, 'index'])->name('bookings.session-wise');
            Route::post('/bookings/session-wise/manual/validate', [BookingController::class, 'validateManual'])->name('bookings.session-wise.manual.validate');
            Route::post('/bookings/session-wise/manual/store', [BookingController::class, 'store'])->name('bookings.session-wise.manual.store');
            Route::put('/bookings/session-wise/{booking}/session', [BookingController::class, 'updateSession'])->name('bookings.session-wise.update-session');
            Route::delete('/bookings/session-wise/{booking}', [BookingController::class, 'destroy'])->name('bookings.session-wise.destroy');
            Route::get('/schedules', fn() => redirect()->route('admin.em.packages.index'))->name('schedules.index');
            Route::get('/schedules/packages', [PackageController::class, 'index'])->name('em.packages.index');
            Route::get('/schedules/packages/create', [PackageController::class, 'create'])->name('em.packages.create');
            Route::post('/schedules/packages', [PackageController::class, 'store'])->name('em.packages.store');
            Route::get('/schedules/packages/{package}/edit', [PackageController::class, 'edit'])->name('em.packages.edit');
            Route::put('/schedules/packages/{package}', [PackageController::class, 'update'])->name('em.packages.update');
            Route::delete('/schedules/packages/{package}', [PackageController::class, 'destroy'])->name('em.packages.destroy');
            Route::get('/schedules/sessions', [SessionController::class, 'index'])->name('em.sessions.index');
            Route::get('/schedules/sessions/create', [SessionController::class, 'create'])->name('em.sessions.create');
            Route::post('/schedules/sessions', [SessionController::class, 'store'])->name('em.sessions.store');
            Route::get('/schedules/sessions/{session}/edit', [SessionController::class, 'edit'])->name('em.sessions.edit');
            Route::put('/schedules/sessions/{session}', [SessionController::class, 'update'])->name('em.sessions.update');
            Route::delete('/schedules/sessions/{session}', [SessionController::class, 'destroy'])->name('em.sessions.destroy');
            Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

            Route::get('/email-testing', [TrainingEmailTestController::class, 'index'])->name('email-tests.index');
            Route::post('/email-testing/{type}/send', [TrainingEmailTestController::class, 'send'])->name('email-tests.send');
        });

        Route::middleware('admin.permission:manage_permissions')->group(function () {
            Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
            Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
            Route::patch('/permissions/{permission}/toggle', [PermissionController::class, 'toggle'])->name('permissions.toggle');
            Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
        });

        Route::middleware('admin.permission:manage_user_groups')->group(function () {
            Route::get('/user-groups', [UserGroupController::class, 'index'])->name('user-groups.index');
            Route::post('/user-groups', [UserGroupController::class, 'store'])->name('user-groups.store');
            Route::get('/user-groups/{userGroup}/edit', [UserGroupController::class, 'edit'])->name('user-groups.edit');
            Route::put('/user-groups/{userGroup}', [UserGroupController::class, 'update'])->name('user-groups.update');
            Route::delete('/user-groups/{userGroup}', [UserGroupController::class, 'destroy'])->name('user-groups.destroy');
        });

        Route::middleware('admin.permission:manage_users')->group(function () {
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        });
    });
});