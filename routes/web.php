<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages');
    Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store']);

    Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show']);
    Route::post('/users/{user}', [\App\Http\Controllers\UserController::class, 'sendLike']);
    Route::get('/users-like/{user}', [\App\Http\Controllers\UserController::class, 'infoLike']);
    Route::get('/users/', [\App\Http\Controllers\UserController::class, 'allUsers'])->name('users');

    Route::get('/friends/{user}', [\App\Http\Controllers\FriendController::class, 'friends'])->name('friends');
    Route::get('/friend-info/{user_from}/{user_to}', [\App\Http\Controllers\FriendController::class, 'infoFriend']);
    Route::post('/friend/add/{user_from}/{user_to}', [\App\Http\Controllers\FriendController::class, 'store']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
