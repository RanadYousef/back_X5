<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\CategoryController;


Route::get('/', function () {
    return view('welcome');
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


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
