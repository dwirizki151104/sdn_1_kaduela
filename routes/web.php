<?php

use App\Http\Controllers\Akademik\GuruController;
use App\Http\Controllers\Akademik\JawabanSiswaController;
use App\Http\Controllers\Akademik\KelasController;
use App\Http\Controllers\Akademik\MataPelajaranController;
use App\Http\Controllers\Akademik\MengajarController;
use App\Http\Controllers\Akademik\ModulController;
use App\Http\Controllers\Akademik\NilaiController;
use App\Http\Controllers\Akademik\PengerjaanQuizController;
use App\Http\Controllers\Akademik\PengumpulanTugasController;
use App\Http\Controllers\Akademik\PilihanJawabanController;
use App\Http\Controllers\Akademik\QuizController;
use App\Http\Controllers\Akademik\SiswaController;
use App\Http\Controllers\Akademik\SoalQuizController;
use App\Http\Controllers\Akademik\TugasController;
use App\Http\Controllers\Akademik\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
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

Route::middleware('guest')->group(function () {
    Route::get('/e-learning/login', [AuthenticatedSessionController::class, 'create'])->name('elearning.login');
    Route::post('/e-learning/login', [AuthenticatedSessionController::class, 'store'])->name('login');

    Route::get('/e-learning/daftar', [RegisteredUserController::class, 'create'])->name('elearning.register');
    Route::post('/e-learning/daftar', [RegisteredUserController::class, 'store'])->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::prefix('akademik')->name('akademik.')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('guru', GuruController::class);
        Route::apiResource('kelas', KelasController::class);
        Route::apiResource('siswa', SiswaController::class);
        Route::apiResource('mata-pelajaran', MataPelajaranController::class);
        Route::apiResource('mengajar', MengajarController::class);
        Route::apiResource('modul', ModulController::class);
        Route::apiResource('tugas', TugasController::class);
        Route::apiResource('pengumpulan-tugas', PengumpulanTugasController::class);
        Route::apiResource('quiz', QuizController::class);
        Route::apiResource('soal-quiz', SoalQuizController::class);
        Route::apiResource('pilihan-jawaban', PilihanJawabanController::class);
        Route::apiResource('pengerjaan-quiz', PengerjaanQuizController::class);
        Route::apiResource('jawaban-siswa', JawabanSiswaController::class);
        Route::apiResource('nilai', NilaiController::class);
    });
});
