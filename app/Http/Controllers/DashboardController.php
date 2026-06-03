<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'jumlahGuru' => Guru::count(),
            'jumlahSiswa' => Siswa::count(),
            'jumlahKelas' => Kelas::count(),
            'jumlahMapel' => MataPelajaran::count(),
        ]);
    }
}
