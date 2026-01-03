<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;

use Illuminate\Http\Request;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\CategoryController;


use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
}); 

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


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

Route::group(['middleware' => ['auth', 'role:admin|employee']], function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generateReport'])->name('reports.generate');
});

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserManagementController::class);
});
// RolePermission الراواتات الخاصة ب 
// يديرها الادمن فقط
Route::middleware(['auth', 'role:admin'])->group(function () {
Route::resource('roles', RolePermissionController::class);
});
//  category الراواتات الخاصة ب 
//تم استثناء 'index', 'show' لانها عامة و تظهر للزائر و المشترك ايضا
Route::middleware(['auth', 'permission:manage categories'])->group(function () {
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
});
//  المسارات المتاحة للجميع (الزوار والمشتركين والكل)
Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');









require __DIR__.'/auth.php';
