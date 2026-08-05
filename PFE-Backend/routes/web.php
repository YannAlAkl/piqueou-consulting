<?php

use App\Http\Controllers\Admin\adminController;
use App\Http\Controllers\Admin\AnalaystController;
use App\Http\Controllers\Admin\clientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/analysts', function () {
//     return view('analysts');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [adminController::class, 'analyst'])->name('dashboard');
Route::get('/analysts', [adminController::class, 'analyst'])->name('analyst');

    /// Analyst Management Routes
    Route::get('/analysts/gestion', [AnalaystController::class, 'index'])->name('analyst.index');
    Route::get('/analysts/create', [AnalaystController::class, 'create'])->name('analyst.create');
    Route::post('/analysts', [AnalaystController::class, 'store'])->name('analyst.store');
    Route::get('/analysts/{id}', [AnalaystController::class, 'show'])->name('analyst.show');
    Route::get('/analysts/{id}/edit', [AnalaystController::class, 'edit'])->name('analyst.edit');
    Route::put('/analysts/{id}', [AnalaystController::class, 'uptade'])->name('analyst.update');
    Route::delete('/analysts/{id}', [AnalaystController::class, 'destroy'])->name('analyst.destroy');

/// Client Management Routes
    Route::get('/clients', [clientController::class, 'index'])->name('client.index');
    Route::get('/clients/create', [clientController::class, 'create'])->name('client.create');
    Route::post('/clients', [clientController::class, 'store'])->name('client.store');
    Route::get('/clients/{id}', [clientController::class, 'show'])->name('client.show');
    Route::get('/clients/{id}/edit', [clientController::class, 'edit'])->name('client.edit');
    Route::put('/clients/{id}', [clientController::class, 'uptade'])->name('client.update');
    Route::delete('/clients/{id}', [clientController::class, 'destroy'])->name('client.destroy');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard-client', function () {
        return view('client.dashboard');
    })->name('dashboard');
});


require __DIR__.'/auth.php';
