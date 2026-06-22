<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/it');
});

Route::middleware(['setLocale'])->group(function () {
    Route::prefix('it')->group(function () {
        Route::get('/', [PublicController::class, 'home'])->name('home.it');
        Route::get('/pro-loco', [PublicController::class, 'proLoco'])->name('proLoco.it');
        Route::get('/comunita', [PublicController::class, 'community'])->name('community.it');
        Route::get('/territorio', [PublicController::class, 'territory'])->name('territory.it');
        Route::get('/sapori', [PublicController::class, 'tastes'])->name('tastes.it');
        Route::get('/scopri', [PublicController::class, 'discover'])->name('discover.it');
        Route::get('/storie', [PublicController::class, 'stories'])->name('stories.it');
        Route::get('/il-borgo', [PublicController::class, 'borgo'])->name('borgo.it');
        Route::get('/contatti', [PublicController::class, 'contacts'])->name('contacts.it');
        Route::get('/notizie', [PublicController::class, 'news'])->name('news.it');
        Route::get('/eventi', [PublicController::class, 'events'])->name('events.it');
        Route::get('/galleria', [PublicController::class, 'gallery'])->name('gallery.it');
        Route::post('/pro-loco/contact', [ContactController::class, 'send'])
            ->name('contact.send.it')
            ->middleware('throttle:5,1');
        Route::get('/privacy', [PublicController::class, 'privacy'])->name('privacy.it');
        Route::get('/cookie', [PublicController::class, 'cookie'])->name('cookie.it');
    });

    Route::prefix('en')->group(function () {
        Route::get('/', [PublicController::class, 'home'])->name('home.en');
        Route::get('/pro-loco', [PublicController::class, 'proLoco'])->name('proLoco.en');
        Route::get('/community', [PublicController::class, 'community'])->name('community.en');
        Route::get('/territory', [PublicController::class, 'territory'])->name('territory.en');
        Route::get('/tastes', [PublicController::class, 'tastes'])->name('tastes.en');
        Route::get('/discover', [PublicController::class, 'discover'])->name('discover.en');
        Route::get('/stories', [PublicController::class, 'stories'])->name('stories.en');
        Route::get('/the-village', [PublicController::class, 'borgo'])->name('borgo.en');
        Route::get('/contacts', [PublicController::class, 'contacts'])->name('contacts.en');
        Route::get('/news', [PublicController::class, 'news'])->name('news.en');
        Route::get('/events', [PublicController::class, 'events'])->name('events.en');
        Route::get('/gallery', [PublicController::class, 'gallery'])->name('gallery.en');
        Route::post('/pro-loco/contact', [ContactController::class, 'send'])
            ->name('contact.send.en')
            ->middleware('throttle:5,1');
        Route::get('/privacy', [PublicController::class, 'privacy'])->name('privacy.en');
        Route::get('/cookies', [PublicController::class, 'cookie'])->name('cookie.en');
    });
});
