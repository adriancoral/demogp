<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware(['accept.json.only'])->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('user.register');
    Route::post('login', [AuthController::class, 'login'])->name('user.login');
});

Route::middleware(['auth:sanctum', 'accept.json.only'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('user.logout');

    Route::get('users', [UserController::class, 'index'])->name('user.index');
    Route::get('me', [UserController::class, 'me'])->name('user.me');
});


