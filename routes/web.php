<?php

use Illuminate\Support\Facades\Route;

Route::middleware('custom.throttle')->group(function () {
    foreach (glob(__DIR__.'/functions/*.php') as $file) {
        require $file;
    }

    foreach (glob(__DIR__.'/api/*.php') as $file) {
        require $file;
    }
});

// Keeping the upload index route to show the page
// Route::get('/upload', [UploadController::class, 'index'])->name('upload.index')->middleware('custom.throttle');

