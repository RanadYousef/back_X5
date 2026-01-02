<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;

Route::middleware(['auth', 'role:admin|employee'])->group(function () {

    // مسارات إدارة الكتب
    Route::resource('books', BookController::class);

    // عرض جميع عمليات الاستعارة
    Route::get('borrowings', [BorrowingController::class, 'index'])
        ->name('borrowings.index');
   // عرض نموذج إنشاء عملية استعارة
    Route::get('borrowings/create', [BorrowingController::class, 'create'])
        ->name('borrowings.create');
   // تنفيذ عملية استعارة كتاب
    Route::post('borrowings', [BorrowingController::class, 'store'])
        ->name('borrowings.store');
    // إرجاع كتاب مستعار
    Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])
        ->name('borrowings.return');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
