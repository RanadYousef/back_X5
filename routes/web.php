<?php

/**
 * Main Web Routes Configuration
 * This file contains all the routes for the application including
 * Authentication, Book Management, Role/Permission Management, and Reporting.
 */

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\DashboardController;

use Illuminate\Http\Request;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\CategoryController;


use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'role:admin|employee'])->group(function () {

    // Book Management Routes
    Route::resource('books', BookController::class);

    // View all borrowing operations
    Route::get('borrowings', [BorrowingController::class, 'index'])
        ->name('borrowings.index');
    // Show the form to create a borrowing operation
    Route::get('borrowings/create', [BorrowingController::class, 'create'])
        ->name('borrowings.create');
    // Execute a book borrowing operation
    Route::post('borrowings', [BorrowingController::class, 'store'])
        ->name('borrowings.store');
    // Return a borrowed book
    Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])
        ->name('borrowings.return');
    Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');

});

Route::middleware(['auth' , 'role:admin'])->group(function () {
    Route::get('/admin/reports', [ReportController::class, 'index'])
        ->middleware('role:admin') 
        ->name('admin.reports.index');

    Route::post('/admin/reports/generate', [ReportController::class, 'generate'])
        ->middleware('role:admin')
        ->name('admin.reports.generate');
        Route::get('/reports/download',[ReportController::class,'downloadPDF'])
        ->name('admin.reports.download')
        ->middleware('role:admin');
});

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserManagementController::class);
});

// Role & Permission Routes
// Managed by Admin only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('roles', RolePermissionController::class);
});

// Category Management Routes
// 'index' and 'show' are excluded because they are public for guests and subscribers
Route::middleware(['auth', 'permission:manage categories'])->group(function () {
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
});

// Public Routes (Available for guests, subscribers, and all users)
Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');




// View all reviews (admin | employee)
Route::middleware(['auth', 'role:admin|employee'])->group(function () {
    Route::get('/reviews', [ReviewController::class, 'index'])
        ->name('reviews.index');
});

// Review Management ( Employee)
Route::middleware(['auth', 'role:employee'])->group(function () {

    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve'])
        ->name('reviews.approve');

    Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject'])
        ->name('reviews.reject');

    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
        ->name('reviews.destroy');
});


require __DIR__ . '/auth.php';