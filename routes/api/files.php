<?php

use App\Http\Controllers\UploadController;
use App\Models\FileHandling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/api/files/top', function (Request $request) {
    // Get latest 8 public files ordered by downloads
    $files = FileHandling::whereNull('password')
        ->orderBy('downloads', 'desc')
        ->latest()
        ->take(8)
        ->get();

    return response()->json($files);
});

Route::get('/api/files/public', function (Request $request) {
    // Get latest 20 public files
    $files = FileHandling::whereNull('password')
        ->orderBy('downloads', 'desc')
        ->latest()
        ->take(20)
        ->get();

    return response()->json($files);
});

Route::get('/api/file/{id}', function (Request $request, $id) {
    // Search by public_url (the hash we generated)
    $file = FileHandling::where('public_url', $id)->first();

    if (!$file) {
        return response()->json([
            'error' => 'File not found',
        ], 404);
    }

    return response()->json($file);
});

Route::post('/api/file/{id}/download', function (Request $request, $id) {
    $file = FileHandling::where('public_url', $id)->first();
    
    if (!$file) {
        return response()->json(['error' => 'File not found'], 404);
    }

    // Verify password if set
    if ($file->password && $request->password !== $file->password) {
        return response()->json(['error' => 'Invalid password'], 403);
    }

    // Increment downloads
    $file->increment('downloads');

    // Get file path from Telegram
    $token = env('TELEGRAM_BOT_TOKEN');
    
    try {
        $response = Http::get("https://api.telegram.org/bot{$token}/getFile", [
            'file_id' => $file->private_url
        ]);

        if ($response->successful()) {
            $filePath = $response->json()['result']['file_path'];
            $downloadUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";
            
            return response()->json([
                'success' => true,
                'download_url' => $downloadUrl,
                'file_name' => $file->file
            ]);
        }
        
        return response()->json(['error' => 'Failed to retrieve file from Telegram storage'], 502);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Storage communication error'], 500);
    }
});

Route::post('/api/file/upload', [UploadController::class, 'store']);
