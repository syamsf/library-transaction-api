<?php

use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BorrowController;
use Illuminate\Support\Facades\Route;

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'fetchAll']);
    Route::get('/{id}', [BookController::class, 'fetchById']);
    Route::post('/', [BookController::class, 'create']);
    Route::put('/{id}', [BookController::class, 'update']);
    Route::delete('/{id}', [BookController::class, 'destroy']);
});

Route::prefix('borrows')->group(function () {
    Route::get('/', [BorrowController::class, 'fetchAll']);
    Route::get('/{id}', [BorrowController::class, 'fetchById']);
    Route::post('/', [BorrowController::class, 'borrow']);
});
