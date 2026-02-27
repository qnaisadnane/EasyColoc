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
    Route::post('/invitations/{invitation}/decline', [\App\Http\Controllers\InvitationController::class, 'decline'])->name('invitations.decline');
    
    // Dépenses
    Route::post('/colocations/{colocation}/expenses', [\App\Http\Controllers\DepenseController::class, 'store'])->name('depenses.store');
    Route::patch('/expenses/{expense}', [\App\Http\Controllers\DepenseController::class, 'update'])->name('depenses.update');
    Route::delete('/expenses/{expense}', [\App\Http\Controllers\DepenseController::class, 'destroy'])->name('depenses.destroy');

    // Tâches & Courses
    Route::post('/colocations/{colocation}/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/{task}/complete', [\App\Http\Controllers\TaskController::class, 'complete'])->name('tasks.complete');
    Route::delete('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::post('/colocations/{colocation}/shopping', [\App\Http\Controllers\ShoppingController::class, 'store'])->name('shopping.store');
    Route::post('/shopping/{item}/toggle', [\App\Http\Controllers\ShoppingController::class, 'toggle'])->name('shopping.toggle');
    Route::delete('/shopping/{item}', [\App\Http\Controllers\ShoppingController::class, 'destroy'])->name('shopping.destroy');
});

require __DIR__.'/auth.php';
