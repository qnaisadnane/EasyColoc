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
    Route::get('/invitations/{token}', [\App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
});

require __DIR__.'/auth.php';
