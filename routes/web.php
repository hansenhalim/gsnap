<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PhotoPaperController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/orders');

Route::middleware('auth')->group(function () {
    Route::resource('/orders', OrderController::class);
    Route::resource('/photo-papers', PhotoPaperController::class);
    Route::resource('/photos', PhotoController::class)->only(['create', 'edit', 'update', 'destroy']);
    Route::get('/thank-you', fn () => Inertia::render('ThankYou', ['timerSeconds' => (int) nova_get_setting('redirect_timer', 10)]))->name('thank-you');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
