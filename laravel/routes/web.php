<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    Route::post('/colocations/{colocation}/leave', [\App\Http\Controllers\ColocationController::class, 'leave'])->name('colocations.leave');
    Route::post('/colocations/{colocation}/remove/{user}', [\App\Http\Controllers\ColocationController::class, 'removeMember'])->name('colocations.removeMember');
    Route::resource('colocations', \App\Http\Controllers\ColocationController::class);
    Route::post('/colocations/{colocation}/invite', [\App\Http\Controllers\InvitationController::class, 'store'])->name('invitations.store');
    Route::post('/invitations/{invitation}/accept', [\App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/colocations/{colocation}/settlements', [\App\Http\Controllers\SettlementController::class, 'store'])->name('settlements.store');
    Route::post('/invitations/{invitation}/decline', [\App\Http\Controllers\InvitationController::class, 'decline'])->name('invitations.decline');
    
    // Dépenses
    Route::post('/colocations/{colocation}/expenses', [\App\Http\Controllers\DepenseController::class, 'store'])->name('depenses.store');
    Route::patch('/expenses/{expense}', [\App\Http\Controllers\DepenseController::class, 'update'])->name('depenses.update');
    Route::delete('/expenses/{expense}', [\App\Http\Controllers\DepenseController::class, 'destroy'])->name('depenses.destroy');


    // Administration
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/users/{user}/toggle-ban', [\App\Http\Controllers\AdminController::class, 'toggleBan'])->name('admin.users.ban');
        
        // Catégories
        Route::post('/categories', [\App\Http\Controllers\AdminController::class, 'storeCategory'])->name('admin.categories.store');
        Route::patch('/categories/{category}', [\App\Http\Controllers\AdminController::class, 'updateCategory'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [\App\Http\Controllers\AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
    });
});

require __DIR__.'/auth.php';
