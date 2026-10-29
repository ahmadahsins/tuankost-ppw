<?php

use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoomController::class, 'home'])->name('home');
Route::view('/about', 'about')->name('about');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/search', [RoomController::class, 'search'])->name('rooms.search');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->whereNumber('room')->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->whereNumber('room')->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->whereNumber('room')->name('rooms.destroy');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])
        ->where('room', '[a-z0-9-]+')
        ->name('rooms.show');

    Route::redirect('/room', '/rooms')->name('rooms.legacy');
});
