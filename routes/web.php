<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

foreach (glob(__DIR__.'/functions/*.php') as $file) {
    require $file;
}

foreach (glob(__DIR__.'/api/*.php') as $file) {
    require $file;
}

// Keeping the upload store route if needed, but usually it would be an API route now
Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
