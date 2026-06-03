<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profil-sekolah', function () {
    return view('profil');
})->name('profil');

Route::get('/e-learning', function () {
    return view('e-learning');
})->name('elearning');

Route::get('/e-learning/login', function () {
    return view('e-learning');
})->name('elearning.login');

Route::get('/e-learning/daftar', function () {
    return view('e-learning-register');
})->name('elearning.register');
