<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/protected-data', function () {
    return view('protected');
})->middleware('api.token');
