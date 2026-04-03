<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/login', function () {
    return view('welcome');
});
Route::get('/protected-data', function () {
    return view('protected');
})->middleware('api.token')->name('protectedOK'); // этот роут может перестать быть нужным, проверить после

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.token'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/api/fetch-data', [DataController::class, 'fetchAllData']);
});