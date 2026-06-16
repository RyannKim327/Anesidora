<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return view('index');
});

Route::get('/about', function (Request $request) {
    return view('about');
});

Route::get('/files', function (Request $request) {
    return view('public-files');
});

Route::get('/file/{id}', function (Request $request, $id) {
    return view('file-info', ['id' => $id]);
});
