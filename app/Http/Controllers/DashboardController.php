<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Modul;
use App\Models\Nilai;
use App\Models\PengerjaanQuiz;
use App\Models\PengumpulanTugas;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\User;
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
            'jumlahUser' => User::count(),
            'jumlahUserAktif' => User::where('status', 'aktif')->count(),
            'jumlahGuru' => Guru::count(),
            'jumlahWaliKelas' => Guru::where('jenis_guru', 'wali_kelas')->count(),
            'jumlahGuruBidang' => Guru::where('jenis_guru', 'bidang_studi')->count(),
            'jumlahSiswa' => Siswa::count(),
            'jumlahKelas' => Kelas::count(),
            'jumlahMapel' => MataPelajaran::count(),
            'jumlahModul' => Modul::count(),
            'jumlahTugas' => Tugas::count(),
            'jumlahPengumpulan' => PengumpulanTugas::count(),
            'jumlahQuiz' => Quiz::count(),
            'jumlahPengerjaanQuiz' => PengerjaanQuiz::count(),
            'jumlahNilai' => Nilai::count(),
            'kelasList' => Kelas::withCount('siswa')->with('waliKelas')->orderBy('nama_kelas')->get(),
            'mapelList' => MataPelajaran::orderBy('kategori')->orderBy('nama_mapel')->get(),
            'modulTerbaru' => Modul::with('mengajar.kelas', 'mengajar.mataPelajaran')->latest()->take(5)->get(),
            'tugasTerbaru' => Tugas::with('mengajar.kelas', 'mengajar.mataPelajaran')->latest()->take(5)->get(),
            'quizTerbaru' => Quiz::with('mengajar.kelas', 'mengajar.mataPelajaran')->latest()->take(5)->get(),
        ]);
    }
}
