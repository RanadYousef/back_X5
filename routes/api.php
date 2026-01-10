<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BorrowingRequestController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Route::middleware('auth:sanctum')->group(function () {
Route::get('/borrowings/current', [BorrowingRequestController::class, 'currentBorrowings']);
Route::get('/borrowings/history', [BorrowingRequestController::class, 'borrowingHistory']);
Route::post('/borrowings/request-borrow', [BorrowingRequestController::class, 'requestBorrow']);
Route::post('/borrowings/request-return/{borrowingId}', [BorrowingRequestController::class, 'requestReturn']);
//});
