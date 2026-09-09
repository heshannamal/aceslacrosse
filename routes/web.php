<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramFeedController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/pages/mission', function () {
    return view('mission'); // mission.blade.php
})->name('mission');

Route::get('/pages/academy', function () {
    return view('academy');
})->name('academy');

Route::get('/pages/travel-teams', function () {
    return view('travel');
})->name('travel');

// Route::get('/pages/hotels', function () {
//     return view('hotels');
// })->name('hotels');

Route::get('/pages/tryouts', function () {
    return view('tryouts');
})->name('tryouts');

// Route::get('/pages/team-store', function () {
//     return view('shop');
// })->name('shop');

Route::get('/pages/contact-us', function () {
    return view('contact');
})->name('contact');

Route::get('/pages/testimonials', function () {
    return view('testimonials');
})->name('testimonials');

Route::get('/pages/sacramento-fall-league', function () {
    return view('sacramento');
})->name('sacramento');

Route::get('/pages/grow-your-game-el-dorado-hills', function () {
    return view('grow your game el dorado hills');
})->name('grow your game el dorado hills');

Route::get('/pages/aces-grow-your-game-davis', function () {
    return view('aces grow your game davis');
})->name('aces grow your game davis');

Route::get('/pages/coaching-staff', function () {
    return view('coaching-staff');
})->name('coaching-staff');
Route::get('/pages/aces-in-college', function () {
    return view('aces-playing');
})->name('aces-playing');
Route::get('/pages/championships', function () {
    return view('championship');
})->name('championship');

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return 'Cache cleared successfully';
});

Route::get(
    '/api/instagram-feed',
    [InstagramFeedController::class, 'index']
)->name('api.instagram-feed');

Route::post('/contact/submit', [ContactController::class, 'submit'])
    ->name('contact.submit');

require __DIR__.'/training.php';
require __DIR__.'/admin.php';
