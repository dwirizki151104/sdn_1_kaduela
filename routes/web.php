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
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\QuizQuestionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruContentController;
use App\Http\Controllers\SiswaContentController;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $tahunBerdiri = 1991;

    return view('welcome', [
        'schoolStats' => [
            ['number' => Siswa::count(), 'label' => 'Siswa Aktif'],
            ['number' => Guru::count(), 'label' => 'Guru Berpengalaman'],
            ['number' => MataPelajaran::count(), 'label' => 'Mata Pelajaran'],
            ['number' => Carbon::now()->year - $tahunBerdiri . '+', 'label' => 'Tahun Berdiri'],
        ],
    ]);
});

Route::get('/profil-sekolah', function () {
    return view('profil');
})->name('profil');

Route::get('/spmb', function () {
    return view('spmb');
})->name('spmb');

Route::get('/galeri', function () {
    return view('galeri');
})->name('galeri');

Route::get('/e-learning', fn () => redirect()->route('elearning.login'))->name('elearning');

Route::middleware('guest')->group(function () {
    Route::get('/e-learning/login', [AuthenticatedSessionController::class, 'create'])->name('elearning.login');
    Route::post('/e-learning/login', [AuthenticatedSessionController::class, 'store'])->name('login');

    Route::get('/e-learning/daftar', [RegisteredUserController::class, 'create'])->name('elearning.register');
    Route::post('/e-learning/daftar', [RegisteredUserController::class, 'store'])->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::prefix('guru')->name('guru.')->group(function () {
        Route::post('/materi', [GuruContentController::class, 'storeModul'])->name('materi.store');
        Route::post('/tugas', [GuruContentController::class, 'storeTugas'])->name('tugas.store');
        Route::post('/quiz', [GuruContentController::class, 'storeQuiz'])->name('quiz.store');
    });

    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::post('/tugas/{tugas}/kumpulkan', [SiswaContentController::class, 'submitTugas'])->name('tugas.submit');
        Route::get('/quiz/{quiz}/kerjakan', [SiswaContentController::class, 'showQuiz'])->name('quiz.show');
        Route::post('/quiz/{quiz}/kerjakan', [SiswaContentController::class, 'submitQuiz'])->name('quiz.submit');
    });

    Route::prefix('admin/quiz/{quiz}/questions')->name('admin.quiz.questions.')->group(function () {
        Route::get('/', [QuizQuestionController::class, 'index'])->name('index');
        Route::post('/', [QuizQuestionController::class, 'store'])->name('store');
        Route::put('/{question}', [QuizQuestionController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuizQuestionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/master/{resource}')->name('admin.master.')->group(function () {
        Route::get('/', [MasterDataController::class, 'index'])->name('index');
        Route::get('/create', [MasterDataController::class, 'create'])->name('create');
        Route::post('/', [MasterDataController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MasterDataController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MasterDataController::class, 'update'])->name('update');
        Route::delete('/{id}', [MasterDataController::class, 'destroy'])->name('destroy');
    });

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
