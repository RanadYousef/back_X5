<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BorrowingRequestController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\LibraryLocationController;

Route::get('/library/location', [LibraryLocationController::class, 'show']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/borrowings/current', [BorrowingRequestController::class, 'currentBorrowings']);
    Route::get('/borrowings/history', [BorrowingRequestController::class, 'borrowingHistory']);
    Route::post('/borrowings/request-borrow', [BorrowingRequestController::class, 'requestBorrow']);
    Route::post('/borrowings/request-return/{borrowingId}', [BorrowingRequestController::class, 'requestReturn']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/my-reviews', [ReviewController::class, 'myReviews']);
    Route::post('/reviews/{id}', [ReviewController::class, 'store']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->withTrashed();
});



//BOOKS API ROUTES
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show'])->withTrashed();
Route::get('/books/{id}', [BookController::class, 'show']);
Route::get('/suggestions/most-borrowed', [BookController::class, 'mostBorrowed']);
Route::get('/suggestions/top-rated', [BookController::class, 'topRated']);


// categories routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}/books', [CategoryController::class, 'books']);
Route::get('/categories/search', [CategoryController::class, 'search']);
// email support route
Route::post('/support/send', [SupportController::class, 'send']);
