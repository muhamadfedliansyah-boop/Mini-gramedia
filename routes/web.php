<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('');

Route::get('/home', function () {
    return view('home');
})->name('home');
