<?php

use App\Http\Controllers\UploadController;
use App\Models\FileHandling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/api/files/top', function (Request $request) {
    $files = FileHandling::whereNull('password')
        ->where(function ($query) {
            $query->whereNull('expiration')
                ->orWhere('expiration', '>', now());
        })
        ->orderBy('downloads', 'desc')
        ->latest()
        ->take(8)
        ->get();

    return response()->json($files);
});

Route::get('/api/files/public', function (Request $request) {
    $files = FileHandling::whereNull('password')
        ->where(function ($query) {
            $query->whereNull('expiration')
                ->orWhere('expiration', '>', now());
        })
        ->orderBy('downloads', 'desc')
        ->latest()
        ->take(20)
        ->get();

    return response()->json($files);
});

Route::get('/api/files/search/{query}', function (Request $request, $query) {
    $files = FileHandling::where('file', 'LIKE', '%'.$query.'%')
        ->whereNull('password')
        ->where(function ($query) {
            $query->whereNull('expiration')
                ->orWhere('expiration', '>', now());
        })
        ->orderBy('downloads', 'desc')
        ->latest()
        ->take(20)
        ->get();

    return response()->json($files);
});

Route::post('/api/file/{id}', function (Request $request, $id) {
    $file = FileHandling::with(['user:id,name'])->where('public_url', $id)->first();
    $body = $request->all();

    if (! $file) {
        return response()->json([
            'error' => 'File not found',
        ], 404);
    }

    if (! $file->expiration || ! $file->expiration->isFuture()) {
        return response()->json([
            'error' => 'File might be expired',
        ], 403);
    }

    // Validate password
    $providedPassword = $body['password'] ?? '';
    $actualPassword = $file->password ?? '';

    if ($actualPassword !== $providedPassword) {
        return response()->json([
            'error' => 'Invalid password',
        ], 401);
    }

    // Return file info (password removed for security)
    $file->password = null;

    return response()->json($file);
});

Route::get('/api/file/{id}', function (Request $request, $id) {
    $file = FileHandling::with(['user:id,name'])->where('public_url', $id)->first();

    if (! $file) {
        return response()->json([
            'error' => 'File not found',
        ], 404);
    }

    if (! $file->expiration || ! $file->expiration->isFuture()) {
        return response()->json([
            'error' => 'File might be expired',
        ], 403);
    }

    // Return only basic info and secured status - not the actual file data
    // The actual file data should only be accessible via POST with password
    return response()->json([
        'id' => $file->id,
        'file' => $file->file,
        'secured' => ! empty($file->password),
        'expiration' => $file->expiration,
        'user' => $file->user,
        // Don't return actual file data like description, downloads, etc.
        // These should only be accessible after password validation via POST
    ]);
});

Route::post('/api/file/{id}/download', function (Request $request, $id) {
    $file = FileHandling::where('public_url', $id)->first();

    if (! $file) {
        return response()->json(['error' => 'File not found'], 404);
    }

    // Verify password if set
    /* if ($file->password && $request->password !== $file->password) { */
    /*     return response()->json(['error' => 'Invalid password'], 403); */
    /* } */

    if (! $file->expiration || $file->expiration->isFuture()) {
        // Increment downloads
        $file->increment('downloads');

        // Get file path from Telegram
        $token = env('TELEGRAM_BOT_TOKEN');

        try {
            $response = Http::get("https://api.telegram.org/bot{$token}/getFile", [
                'file_id' => $file->private_url,
            ]);

            if ($response->successful()) {
                $filePath = $response->json()['result']['file_path'];
                $downloadUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";

                return response()->json([
                    'success' => true,
                    'download_url' => $downloadUrl,
                    'file_name' => $file->file,
                ]);
            }

            return response()->json(['error' => 'Failed to retrieve file from Telegram storage'], 502);
        } catch (Exception $e) {
            return response()->json(['error' => 'Storage communication error'], 500);
        }
    } else {
        return response()->json([
            'error' => 'The file is already expired',
        ], 403);
    }
});

Route::post('/api/upload', [UploadController::class, 'store']);
