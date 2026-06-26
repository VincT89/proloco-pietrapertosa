<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/', function () {
    return redirect('/it');
});

Route::middleware(['setLocale'])->group(function () {
    Route::prefix('it')->group(function () {
        Route::get('/', [PublicController::class, 'home'])->name('home.it');
        Route::get('/pro-loco', [PublicController::class, 'proLoco'])->name('proLoco.it');
        Route::get('/comunita', [PublicController::class, 'community'])->name('community.it');
        Route::get('/eccellenze', [PublicController::class, 'excellences'])->name('excellences.it');
        Route::get('/sapori', [PublicController::class, 'tastes'])->name('tastes.it');
        Route::get('/scopri', [PublicController::class, 'discover'])->name('discover.it');

        Route::get('/notizie', [PublicController::class, 'news'])->name('news.it');
        Route::get('/eventi', [PublicController::class, 'events'])->name('events.it');
        Route::get('/galleria', [PublicController::class, 'gallery'])->name('gallery.it');
        Route::get('/ringraziamenti-fotografici', [PublicController::class, 'photoThanks'])->name('photo-thanks.it');
        Route::post('/pro-loco/contact', [ContactController::class, 'send'])
            ->name('contact.send.it')
            ->middleware('throttle:5,1');
        Route::get('/privacy', [PublicController::class, 'privacy'])->name('privacy.it');
        Route::get('/cookie', [PublicController::class, 'cookie'])->name('cookie.it');

        // Chatbot routes
        Route::post('/chatbot/message', [ChatbotController::class, 'message'])
            ->name('chatbot.message.it')
            ->defaults('locale', 'it')
            ->middleware('throttle:20,1');
        Route::get('/chatbot/suggestions', [ChatbotController::class, 'suggestions'])
            ->name('chatbot.suggestions.it')
            ->defaults('locale', 'it')
            ->middleware('throttle:20,1');
    });

    Route::prefix('en')->group(function () {
        Route::get('/', [PublicController::class, 'home'])->name('home.en');
        Route::get('/pro-loco', [PublicController::class, 'proLoco'])->name('proLoco.en');
        Route::get('/community', [PublicController::class, 'community'])->name('community.en');
        Route::get('/excellences', [PublicController::class, 'excellences'])->name('excellences.en');
        Route::get('/tastes', [PublicController::class, 'tastes'])->name('tastes.en');
        Route::get('/discover', [PublicController::class, 'discover'])->name('discover.en');

        Route::get('/news', [PublicController::class, 'news'])->name('news.en');
        Route::get('/events', [PublicController::class, 'events'])->name('events.en');
        Route::get('/gallery', [PublicController::class, 'gallery'])->name('gallery.en');
        Route::get('/photo-thanks', [PublicController::class, 'photoThanks'])->name('photo-thanks.en');
        Route::post('/pro-loco/contact', [ContactController::class, 'send'])
            ->name('contact.send.en')
            ->middleware('throttle:5,1');
        Route::get('/privacy', [PublicController::class, 'privacy'])->name('privacy.en');
        Route::get('/cookies', [PublicController::class, 'cookie'])->name('cookie.en');

        // Chatbot routes
        Route::post('/chatbot/message', [ChatbotController::class, 'message'])
            ->name('chatbot.message.en')
            ->defaults('locale', 'en')
            ->middleware('throttle:20,1');
        Route::get('/chatbot/suggestions', [ChatbotController::class, 'suggestions'])
            ->name('chatbot.suggestions.en')
            ->defaults('locale', 'en')
            ->middleware('throttle:20,1');
    });
});
