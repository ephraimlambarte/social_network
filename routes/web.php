<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Middleware\FriendRequestActionMiddleware;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FriendController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/add-friend/{user}', [FriendRequestController::class, 'addFriend']);
    Route::delete('/remove-friend/{user}', [FriendController::class, 'removeFriend']);
    Route::get('/my-friends', [FriendController::class, 'myFriends']);
    Route::get('/my-friend-requests', [FriendRequestController::class, 'myFriendRequests']);
    Route::middleware([FriendRequestActionMiddleware::class])->group(function () {
        Route::post('/accept-friend-request/{friendRequest}', [FriendRequestController::class, 'acceptFriendRequest']);
        Route::post('/ignore-friend-request/{friendRequest}', [FriendRequestController::class, 'ignoreFriendRequest']);
    });

    Route::post('/send-message/{user}', [MessageController::class, 'sendMessage']);
    Route::get('/messages/{user}', [MessageController::class, 'getMessages']);
    Route::get('/my-inbox', [MessageController::class, 'getUserInbox']);
    Route::post('/read-messages', [MessageController::class, 'readMessages']);



    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/friends', function () {
        return Inertia::render('Friends');
    })->name('friends');
});

require __DIR__.'/auth.php';
