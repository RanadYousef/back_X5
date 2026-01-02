<?php

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
//  المسارات المتاحة للجميع (الزوار والمشتركين والكل)
Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
//  category الراواتات الخاصة ب 
//تم استثناء 'index', 'show' لانها عامة و تظهر للزائر و المشترك ايضا
Route::middleware(['auth', 'permission:manage categories'])->group(function () {
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
});
