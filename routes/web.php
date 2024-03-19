<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Middleware\FriendRequestActionMiddleware;
use App\Http\Controllers\FriendRequestController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/add-friend/{user}', [FriendRequestController::class, 'addFriend']);
    Route::get('/my-friend-requests', [FriendRequestController::class, 'myFriendRequests']);
    Route::middleware([FriendRequestActionMiddleware::class])->group(function () {
        Route::post('/accept-friend-request/{friendRequest}', [FriendRequestController::class, 'acceptFriendRequest']);
        Route::post('/ignore-friend-request/{friendRequest}', [FriendRequestController::class, 'ignoreFriendRequest']);
    });
 
});

require __DIR__.'/auth.php';
