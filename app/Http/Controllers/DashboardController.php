<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Modul;
use App\Models\Nilai;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\Tugas;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        if ($user->role === 'guru') {
            $guru = $user->guru?->load(['kelasWali', 'mengajar.kelas', 'mengajar.mataPelajaran']);

            return view('dashboard.guru', [
                'guru' => $guru,
                'jumlahMengajar' => $guru?->mengajar->count() ?? 0,
                'jumlahModul' => Modul::whereIn('id_mengajar', $guru?->mengajar->pluck('id_mengajar') ?? [])->count(),
                'jumlahTugas' => Tugas::whereIn('id_mengajar', $guru?->mengajar->pluck('id_mengajar') ?? [])->count(),
                'jumlahQuiz' => Quiz::whereIn('id_mengajar', $guru?->mengajar->pluck('id_mengajar') ?? [])->count(),
            ]);
        }

        if ($user->role === 'siswa') {
            $siswa = $user->siswa?->load(['kelas']);

            return view('dashboard.siswa', [
                'siswa' => $siswa,
                'jumlahTugasDikumpulkan' => $siswa?->pengumpulanTugas()->count() ?? 0,
                'jumlahQuizDikerjakan' => $siswa?->pengerjaanQuiz()->count() ?? 0,
                'jumlahNilai' => $siswa?->nilai()->count() ?? 0,
                'rataNilaiAkhir' => $siswa?->nilai()->avg('nilai_akhir'),
            ]);
        }

        return view('dashboard.admin', [
            'jumlahGuru' => Guru::count(),
            'jumlahSiswa' => Siswa::count(),
            'jumlahKelas' => Kelas::count(),
            'jumlahMapel' => MataPelajaran::count(),
        ]);
    }
}
