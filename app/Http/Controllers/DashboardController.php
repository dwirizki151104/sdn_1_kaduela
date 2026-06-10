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
            $mengajarIds = $guru?->mengajar->pluck('id_mengajar') ?? collect();

            return view('dashboard.guru', [
                'guru' => $guru,
                'jumlahMengajar' => $guru?->mengajar->count() ?? 0,
                'jumlahModul' => Modul::whereIn('id_mengajar', $mengajarIds)->count(),
                'jumlahTugas' => Tugas::whereIn('id_mengajar', $mengajarIds)->count(),
                'jumlahQuiz' => Quiz::whereIn('id_mengajar', $mengajarIds)->count(),
                'modulTerbaru' => Modul::with('mengajar.kelas', 'mengajar.mataPelajaran')
                    ->whereIn('id_mengajar', $mengajarIds)
                    ->latest()
                    ->take(5)
                    ->get(),
                'tugasTerbaru' => Tugas::with('mengajar.kelas', 'mengajar.mataPelajaran')
                    ->whereIn('id_mengajar', $mengajarIds)
                    ->latest()
                    ->take(5)
                    ->get(),
                'quizTerbaru' => Quiz::with('mengajar.kelas', 'mengajar.mataPelajaran', 'soal')
                    ->whereIn('id_mengajar', $mengajarIds)
                    ->latest()
                    ->take(5)
                    ->get(),
            ]);
        }

        if ($user->role === 'siswa') {
            $siswa = $user->siswa?->load(['kelas']);
            $kelasId = $siswa?->id_kelas;

            return view('dashboard.siswa', [
                'siswa' => $siswa,
                'jumlahMateri' => Modul::whereHas('mengajar', fn ($query) => $query->where('id_kelas', $kelasId))->count(),
                'jumlahTugas' => Tugas::whereHas('mengajar', fn ($query) => $query->where('id_kelas', $kelasId))->count(),
                'jumlahQuiz' => Quiz::whereHas('mengajar', fn ($query) => $query->where('id_kelas', $kelasId))->count(),
                'jumlahTugasDikumpulkan' => $siswa?->pengumpulanTugas()->count() ?? 0,
                'jumlahQuizDikerjakan' => $siswa?->pengerjaanQuiz()->count() ?? 0,
                'jumlahNilai' => $siswa?->nilai()->count() ?? 0,
                'rataNilaiAkhir' => $siswa?->nilai()->avg('nilai_akhir'),
                'materiList' => Modul::with('mengajar.guru', 'mengajar.kelas', 'mengajar.mataPelajaran')
                    ->whereHas('mengajar', fn ($query) => $query->where('id_kelas', $kelasId))
                    ->latest()
                    ->get(),
                'tugasList' => Tugas::with([
                    'mengajar.guru',
                    'mengajar.kelas',
                    'mengajar.mataPelajaran',
                    'pengumpulan' => fn ($query) => $query->where('id_siswa', $siswa?->id_siswa),
                ])
                    ->whereHas('mengajar', fn ($query) => $query->where('id_kelas', $kelasId))
                    ->latest()
                    ->get(),
                'quizList' => Quiz::with([
                    'mengajar.guru',
                    'mengajar.kelas',
                    'mengajar.mataPelajaran',
                    'soal.pilihanJawaban',
                    'pengerjaan' => fn ($query) => $query->where('id_siswa', $siswa?->id_siswa),
                ])
                    ->whereHas('mengajar', fn ($query) => $query->where('id_kelas', $kelasId))
                    ->latest()
                    ->get(),
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
            'rekapPengumpulanTugas' => Tugas::with('mengajar.kelas', 'mengajar.mataPelajaran')
                ->withCount('pengumpulan')
                ->latest()
                ->take(8)
                ->get(),
            'pengumpulanTerbaru' => PengumpulanTugas::with('siswa.kelas', 'tugas.mengajar.kelas', 'tugas.mengajar.mataPelajaran')
                ->latest('tanggal_kumpul')
                ->take(10)
                ->get(),
            'pengerjaanQuizTerbaru' => PengerjaanQuiz::with([
                'siswa.kelas',
                'quiz.mengajar.kelas',
                'quiz.mengajar.mataPelajaran',
            ])
                ->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')
                ->take(10)
                ->get(),
        ]);
    }
}
