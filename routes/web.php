<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/character/create', [CharacterController::class, 'create'])->name('character.create');
    Route::post('/character', [CharacterController::class, 'store'])->name('character.store');

    // Akses game hanya jika sudah punya karakter
    Route::middleware(['auth', 'has.character'])
    ->get('/game', [GameController::class, 'index'])
    ->name('game');

    Route::middleware(['auth', 'has.character'])->get('/adventure', function () {
    return view('game.adventure');
})->name('adventure');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
