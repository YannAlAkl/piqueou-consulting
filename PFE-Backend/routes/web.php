<?php

use App\Http\Controllers\Admin\adminController;
use App\Http\Controllers\Admin\AnalaystController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [adminController::class, 'index'])->name('dashboard');
    Route::get('/clients', [adminController::class, 'index'])->defaults('role', 'client')->name('client');
    Route::get('/analysts', [adminController::class, 'index'])->defaults('role', 'analyst')->name('analyst');
    /// Analyste Manager Routes
    Route::get('/analysts/gestion',[AnalaystController::class,'index'])->name('analyst.index');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
