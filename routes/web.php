<?php

use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoomController::class, 'home'])->name('home');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])
    ->where('room', '[a-z0-9-]+')
    ->name('rooms.show');

Route::view('/about', 'about')->name('about');
Route::redirect('/room', '/rooms')->name('rooms.legacy');
