<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
   * Store a newly created file share.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'file' => 'required|file|max:102400', // max 100MB
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'password' => 'nullable|string|min:4',
      'expiration' => 'required|string|in:1h,24h,7d,30d,custom',
    ]);

    if ($validator->fails()) {
      if ($request->expectsJson()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }
      return back()->withErrors($validator)->withInput();
    }

    // In a real application, the uploaded file would be processed here,
    // and stored in the database.
    
    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'File uploaded successfully!',
        'redirect' => '/' // or wherever
      ]);
    }

    return redirect('/')->with('success', 'File registered and uploaded successfully.');
  }
}
