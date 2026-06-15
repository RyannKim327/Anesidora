<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

$topFiles = [
    [
      'id' => 1,
      'name' => "Sample File.png",
      'description' => "Hello World",
      'expiration' => 1780754837,
      'url' => "google",
      'protected' => false,
      'downloads' => 125
    ],
    [
      'id' => 2,
      'name' => "Project Proposal.pdf",
      'description' => "Project proposal",
      'expiration' => 1783346837,
      'url' => "drive-link-1",
      'protected' => true,
      'downloads' => 450
    ],
    [
      'id' => 3,
      'name' => "Marketing Banner.jpg",
      'description' => "Marketing assets",
      'expiration' => 1786025237,
      'url' => "cdn-link-2",
      'protected' => false,
      'downloads' => 89
    ],
    [
      'id' => 4,
      'name' => "Employee Handbook.docx",
      'description' => "Internal documentation",
      'expiration' => 1788703637,
      'url' => "storage-link-3",
      'protected' => true,
      'downloads' => 302
    ],
    [
      'id' => 5,
      'name' => "Financial Report.xlsx",
      'description' => "Q3 financial report",
      'expiration' => 1791295637,
      'url' => "storage-link-4",
      'protected' => false,
      'downloads' => 15
    ],
    [
      'id' => 6,
      'name' => "Presentation Slides.pptx",
      'description' => "Company presentation",
      'expiration' => 1793974037,
      'url' => "drive-link-5",
      'protected' => true,
      'downloads' => 67
    ],
    [
      'id' => 7,
      'name' => "System Architecture.png",
      'description' => "Backend architecture diagram",
      'expiration' => 1796652437,
      'url' => "storage-link-6",
      'protected' => false,
      'downloads' => 234
    ],
    [
      'id' => 8,
      'name' => "API Documentation.pdf",
      'description' => "Complete API reference guide",
      'expiration' => 1799330837,
      'url' => "storage-link-7",
      'protected' => true,
      'downloads' => 512
    ]
];

Route::get('/api/files/top', function (Request $request) use ($topFiles){
    return response()->json($topFiles);
});

Route::get('/api/file/{id}', function (Request $request, $id) use ($topFiles) {
    if(!isset($topFiles[$id - 1])){
        return response()->json([
            'error' => 'File not found'
        ]);
    }
    return response()->json($topFiles[$id - 1]);
});

Route::post('/api/file/upload', function (Request, $request){
    return response()->json([
        'message' => 'Uploaded'
    ]);
});
