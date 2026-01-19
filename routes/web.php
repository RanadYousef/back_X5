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
use App\Http\Controllers\ChatController;

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
    Route::prefix('books')->group(function () {
        Route::get('books/trashed', [BookController::class, 'trashed'])->name('books.trashed');
        Route::post('books/{id}/restore', [BookController::class, 'restore'])->name('books.restore');
        Route::delete('books/{id}/force-delete', [BookController::class, 'forceDelete'])->name('books.forceDelete');
    });
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/history', [BorrowingController::class, 'history'])->name('borrowings.history');

    Route::post('/borrowings/approve/{borrowRequest}', [BorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('/borrowings/reject/{borrowRequest}', [BorrowingController::class, 'reject'])->name('borrowings.reject');

    Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::post('/reports/generate', [ReportController::class, 'generateReport'])
        ->name('reports.generate');

});
Route::group(['middleware' => ['auth', 'role:admin|employee']], function () {

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::post('/reports/generate', [ReportController::class, 'generateReport'])->name('reports.generate');

    Route::get('/reports/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.export_pdf');
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

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserManagementController::class);

    Route::patch(
        'users/{id}/restore',
        [UserManagementController::class, 'restore']
    )->name('users.restore');

    Route::delete(
        'users/{id}/force-delete',
        [UserManagementController::class, 'forceDelete']
    )->name('users.forceDelete');
});

Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/create', [ChatController::class, 'create'])->name('chat.create');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::delete('/chat/{conversation}', [ChatController::class, 'destroy'])->name('chat.destroy');
});


require __DIR__ . '/auth.php';




























































































































