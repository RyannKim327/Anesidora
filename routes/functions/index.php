<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::get('/search/{query}', function (Request $request, $query) {
    return view('search', ['search' => $query]);
});

Route::get('/user/profile/{id?}', function (Request $request, $id = null) {

    if ($id) {
        $user = User::findOrFail($id);
    } else {
        if (! Auth::check()) {
            return redirect('/');
        }
        $user = Auth::user();
    }

    $isOwner = Auth::check() && Auth::id() === $user->id;

    $filesQuery = $user->files()->latest();

    if (! $isOwner) {
        $filesQuery->whereNull('password')->orWhere('password', '');
    }

    $files = $filesQuery->get();

    return view('profile', [
        'user' => $user,
        'files' => $files,
        'isOwner' => $isOwner,
    ]);

})->name('profile');
