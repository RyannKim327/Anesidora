<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

foreach (glob(__DIR__.'/functions/*.php') as $file) {
    require $file;
}

foreach (glob(__DIR__.'/api/*.php') as $file) {
    require $file;
}

// Keeping the upload index route to show the page
Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
