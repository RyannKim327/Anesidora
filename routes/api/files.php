<?php

use App\Http\Controllers\UploadController;
use App\Models\FileHandling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/api/files/top', function (Request $request) {
    // Get latest 8 files as "top" for now, or could order by downloads if that column existed
    $files = FileHandling::latest()->take(8)->get();
    return response()->json($files);
});

Route::get('/api/file/{id}', function (Request $request, $id) {
    // Search by public_url (the hash we generated)
    $file = FileHandling::where('public_url', $id)->first();
    
    if (!$file) {
        return response()->json([
            'error' => 'File not found'
        ], 404);
    }
    
    return response()->json($file);
});

Route::post('/api/file/upload', [UploadController::class, 'store']);
