<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware(['api'])->group(function () {
    Route::get('unauthorized', [MainController::class, 'unauthorized'])->name('unauthorized');

    Route::prefix('administrators')->group(function () {
        Route::post('/register', [AdministratorController::class, 'register'])->name('administrators.register');
        Route::post('/login', [AdministratorController::class, 'login'])->name('administrators.login');
        Route::post('/logout', [AdministratorController::class, 'logout'])->name('administrators.logout');
        Route::get('/email', [AdministratorController::class, 'getEmail'])->name('administrators.getEmail');
    });

    Route::prefix('users')->group(function () {
        Route::post('', [UserController::class, 'create'])->name('users.create');
        Route::post('update/{user_id}', [UserController::class, 'update'])->name('users.update');
        Route::post('delete/{user_id}', [UserController::class, 'delete'])->name('users.delete');
    });

    Route::prefix('requests')->group(function () {
        Route::get('/{request_id}', [RequestController::class, 'get'])->name('requests.get');
        Route::get('/status/pending', [RequestController::class, 'pending'])->name('requests.pending');
        Route::put('approve/{request_id}', [RequestController::class, 'approve'])->name('requests.approve');
        Route::put('decline/{request_id}', [RequestController::class, 'decline'])->name('requests.decline');
    });
});
