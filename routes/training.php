<?php

use App\Http\Controllers\EMCustomerController;
use App\Http\Controllers\TrainingAuthController;
use App\Http\Controllers\TrainingDashboardController;
use App\Http\Controllers\TrainingPortalController;
use App\Http\Controllers\TrainingProfileController;
use App\Http\Controllers\TrainingRegistrationController;
use App\Http\Middleware\EnsureTrainingCustomerPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('training')->name('em.customer.')->group(function () {
    Route::get('/login', [TrainingAuthController::class, 'login'])->name('login');
    Route::post('/login', [TrainingAuthController::class, 'loginSubmit'])->name('login.submit');
    Route::get('/create-password', [TrainingAuthController::class, 'createPassword'])->name('password.create');
    Route::post('/create-password', [TrainingAuthController::class, 'storePassword'])->middleware('throttle:10,1')->name('password.store');

    Route::get('/register', function (Request $request) {
        if (session()->has('em_customer_id')) {
            return redirect()->route('em.customer.dashboard');
        }

        if ($request->filled('redirect')) {
            session()->put('em_url_intended', $request->string('redirect')->toString());
        }

        return view('pages.customer_sessions.register');
    })->name('register');
    Route::post('/register', [TrainingRegistrationController::class, 'store'])->name('register.submit');

    Route::get('/forgot-password', [EMCustomerController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [EMCustomerController::class, 'forgotPasswordSubmit'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [EMCustomerController::class, 'resetPassword'])->middleware('throttle:20,1')->name('password.reset');
    Route::post('/reset-password', [EMCustomerController::class, 'resetPasswordSubmit'])->middleware('throttle:5,1')->name('password.update');

    Route::get('/', [TrainingPortalController::class, 'index'])->name('index');
    Route::get('/calendar-events', [TrainingPortalController::class, 'calendarEvents'])->name('calendar.events');
    Route::get('/packages', [TrainingPortalController::class, 'packages'])->name('packages');
    Route::get('/packages/{id}', [TrainingPortalController::class, 'packageDetails'])->name('package.details');
    Route::post('/cart/add/{id}', [TrainingPortalController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [TrainingPortalController::class, 'cart'])->name('cart');
    Route::post('/cart/{id}', [TrainingPortalController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{id}', [TrainingPortalController::class, 'removeCart'])->name('cart.remove');
    Route::get('/checkout', [TrainingPortalController::class, 'checkout'])->name('checkout');

    Route::middleware(['em.customer', EnsureTrainingCustomerPricing::class])->group(function () {
        Route::get('/dashboard', [TrainingDashboardController::class, 'index'])->name('dashboard');
        Route::get('/my-bookings', [TrainingDashboardController::class, 'bookings'])->name('bookings');
        Route::post('/logout', [TrainingPortalController::class, 'logout'])->name('logout');

        Route::post('/session/{id}/book', [TrainingPortalController::class, 'bookSession'])->name('session.book');
        Route::post('/booking/{id}/cancel', [EMCustomerController::class, 'cancelBooking'])->name('booking.cancel');

        Route::post('/checkout/pay', [EMCustomerController::class, 'pay'])->name('pay');
        Route::get('/payment-success/{orderId}', [EMCustomerController::class, 'paymentSuccess'])->name('payment.success');

        Route::get('/profile', [TrainingProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [TrainingProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/players', [TrainingProfileController::class, 'storeChild'])->name('profile.children.store');
        Route::put('/profile/players/{child}', [TrainingProfileController::class, 'updateChild'])->name('profile.children.update');
        Route::delete('/profile/players/{child}', [TrainingProfileController::class, 'deleteChild'])->name('profile.children.delete');
    });
});

Route::prefix('book_my_sessions')->group(function () {
    Route::get('/', fn () => redirect()->route('em.customer.index'));
    Route::get('/login', fn () => redirect()->route('em.customer.login'));
    Route::get('/register', fn () => redirect()->route('em.customer.register'));
});
