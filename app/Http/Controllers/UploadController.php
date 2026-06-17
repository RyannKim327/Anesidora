<?php

namespace App\Http\Controllers;

use App\Models\FileHandling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Show the upload page.
     */
    public function index()
    {
        return view('upload');
    }

    /**
     * Store a newly created file share using Telegram API.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:102400', // max 100MB
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'password' => 'nullable|string|min:4',
            'expiration' => 'required|string|in:1h,24h,7d,30d,custom,0,lifetime',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('file');
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (! $token || ! $chatId) {
            return response()->json([
                'success' => false,
                'message' => 'Telegram API credentials not configured.',
            ], 500);
        }

        try {
            // Send file to Telegram
            $response = Http::attach(
                'document',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post("https://api.telegram.org/bot{$token}/sendDocument", [
                'chat_id' => $chatId,
                'caption' => "File: {$request->name}\nDescription: {$request->description}",
            ]);

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload to Telegram: '.$response->body(),
                ], 502);
            }

            $telegramData = $response->json();
            $fileId = $telegramData['result']['document']['file_id'];

            // Map expiration string to actual datetime
            $expirationDate = match ($request->expiration) {
                '1h' => now()->addHour(),
                '24h' => now()->addDay(),
                '7d' => now()->addDays(7),
                '30d' => now()->addDays(30),
                'lifetime', '0' => null,
                default => null
            };

            // Create record in database
            $fileRecord = FileHandling::create([
                'file' => $request->name,
                'user_id' => Auth::id(),
                'private_url' => $fileId, // Use Telegram file_id as internal reference
                'public_url' => Str::random(12), // Unique hash for our app's public link
                'description' => $request->description ?? 'No description Provided',
                'password' => $request->password, // Note: Should probably hash this in production
                'expiration' => $expirationDate,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded and registered successfully!',
                'redirect' => '/file/'.$fileRecord->public_url,
                'file_id' => $fileRecord->public_url,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An internal error occurred: '.$e->getMessage(),
            ], 500);
        }
    }
}
