<?php

use App\Http\Controllers\BookStoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('book-add', [BookStoreController::class, 'bookAdd']);
    Route::get('fetch-book', [BookStoreController::class, 'fetchBook']);
    Route::post('book-update', [BookStoreController::class, 'bookUpdate']);
    Route::post('delete-book', [BookStoreController::class, 'deleteBook']);
    Route::get('fetch-book-id', [BookStoreController::class, 'fetchBookId']);
});
