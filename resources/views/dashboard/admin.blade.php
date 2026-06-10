@extends('layouts.admin')

@section('title', 'Dashboard Admin - SD Negeri 1 Kaduela')

@section('content')
    @php
        $masterCards = [
            ['title' => 'Manajemen User', 'desc' => 'Akun admin, guru, siswa, status aktif, dan hak akses.', 'count' => $jumlahUser, 'href' => route('admin.master.index', 'users'), 'tone' => 'emerald'],
            ['title' => 'Data Guru', 'desc' => 'Profil guru wali kelas dan guru bidang studi.', 'count' => $jumlahGuru, 'href' => route('admin.master.index', 'guru'), 'tone' => 'blue'],
            ['title' => 'Data Siswa', 'desc' => 'Profil siswa, NIS, jenis kelamin, alamat, dan kelas.', 'count' => $jumlahSiswa, 'href' => route('admin.master.index', 'siswa'), 'tone' => 'amber'],
            ['title' => 'Data Kelas', 'desc' => 'Kelas 1 sampai 6 beserta wali kelasnya.', 'count' => $jumlahKelas, 'href' => route('admin.master.index', 'kelas'), 'tone' => 'rose'],
            ['title' => 'Mata Pelajaran', 'desc' => 'Tematik dan mata pelajaran khusus PJOK, PAI, SBK.', 'count' => $jumlahMapel, 'href' => route('admin.master.index', 'mata-pelajaran'), 'tone' => 'cyan'],
            ['title' => 'Data Mengajar', 'desc' => 'Relasi guru, kelas, dan mata pelajaran.', 'count' => $jumlahWaliKelas + $jumlahGuruBidang, 'href' => route('admin.master.index', 'mengajar'), 'tone' => 'violet'],
        ];

        $toneClass = [
            'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
            'blue' => 'bg-blue-50 text-blue-700 ring-blue-100',
            'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
            'rose' => 'bg-rose-50 text-rose-700 ring-rose-100',
            'cyan' => 'bg-cyan-50 text-cyan-700 ring-cyan-100',
            'violet' => 'bg-violet-50 text-violet-700 ring-violet-100',
        ];
    @endphp

    @component('admin.partials.shell')
        <header class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-teal-700">Admin Panel</p>
                <h1 class="mt-1 text-2xl font-black tracking-normal text-slate-950 sm:text-4xl">Dashboard Akademik</h1>
                <p class="mt-2 max-w-2xl text-sm font-semibold leading-relaxed text-slate-500">
                    Kelola data master sekolah dan pantau aktivitas e-learning dalam satu ruang kerja.
                </p>
            </div>

            <div class="flex min-w-[220px] items-center gap-3 rounded-lg border border-slate-200 bg-white/85 p-3 shadow-lg shadow-slate-900/5">
                <span class="grid size-11 place-items-center rounded-lg bg-blue-50 text-sm font-black text-blue-700">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
                <div>
                    <strong class="block text-sm font-black text-slate-900">{{ auth()->user()->username }}</strong>
                    <span class="block text-xs font-extrabold text-slate-500">{{ auth()->user()->role }} aktif</span>
                </div>
            </div>
        </header>

        <section class="mb-4 grid gap-4 rounded-lg border border-emerald-100 bg-white p-5 shadow-xl shadow-slate-900/5 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
            <div>
                <h2 class="text-lg font-black text-emerald-950">Ringkasan Operasional Sekolah</h2>
                <p class="mt-2 max-w-3xl text-sm font-semibold leading-relaxed text-slate-500">
                    Dashboard ini membantu admin memeriksa kesiapan data kelas, guru, siswa, konten pembelajaran, dan evaluasi online.
                </p>
            </div>
            <span class="inline-flex w-fit rounded-full bg-amber-100 px-3 py-2 text-xs font-black text-amber-800">Tahun Ajaran Aktif</span>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan sistem">
            <article class="relative overflow-hidden rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5 before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-teal-600">
                <span class="text-xs font-black uppercase tracking-wide text-slate-500">User Aktif</span>
                <strong class="mt-4 block text-4xl font-black leading-none text-slate-950">{{ $jumlahUserAktif }}</strong>
                <small class="mt-3 block text-xs font-black text-teal-700">Total akun: {{ $jumlahUser }}</small>
            </article>
            <article class="relative overflow-hidden rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5 before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-blue-600">
                <span class="text-xs font-black uppercase tracking-wide text-slate-500">Guru</span>
                <strong class="mt-4 block text-4xl font-black leading-none text-slate-950">{{ $jumlahGuru }}</strong>
                <small class="mt-3 block text-xs font-black text-blue-700">Wali: {{ $jumlahWaliKelas }} | Bidang: {{ $jumlahGuruBidang }}</small>
            </article>
            <article class="relative overflow-hidden rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5 before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-amber-500">
                <span class="text-xs font-black uppercase tracking-wide text-slate-500">Siswa</span>
                <strong class="mt-4 block text-4xl font-black leading-none text-slate-950">{{ $jumlahSiswa }}</strong>
                <small class="mt-3 block text-xs font-black text-amber-700">Tersebar di {{ $jumlahKelas }} kelas</small>
            </article>
            <article class="relative overflow-hidden rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5 before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-rose-500">
                <span class="text-xs font-black uppercase tracking-wide text-slate-500">Konten E-Learning</span>
                <strong class="mt-4 block text-4xl font-black leading-none text-slate-950">{{ $jumlahModul + $jumlahTugas + $jumlahQuiz }}</strong>
                <small class="mt-3 block text-xs font-black text-rose-700">Modul, tugas, dan quiz</small>
            </article>
        </section>

        <section class="mt-4 grid gap-4 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-base font-black text-slate-950">Data Master</h2>
                        <p class="mt-1 text-xs font-bold text-slate-500">Pengelolaan utama sistem akademik.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black text-slate-500">CRUD Aktif</span>
                </div>

                <div class="grid gap-3 md:grid-cols-2">
                    @foreach ($masterCards as $card)
                        <a class="group min-h-32 rounded-lg border border-slate-200 bg-slate-50 p-4 text-decoration-none transition hover:-translate-y-0.5 hover:border-emerald-200 hover:bg-white hover:shadow-lg hover:shadow-slate-900/5" href="{{ $card['href'] }}">
                            <div class="flex items-center justify-between gap-3">
                                <strong class="text-sm font-black text-slate-950">{{ $card['title'] }}</strong>
                                <span class="rounded-full px-2.5 py-1 text-xs font-black ring-1 {{ $toneClass[$card['tone']] }}">{{ $card['count'] }}</span>
                            </div>
                            <span class="mt-3 block text-xs font-semibold leading-relaxed text-slate-500">{{ $card['desc'] }}</span>
                            <span class="mt-4 inline-flex text-xs font-black text-emerald-700">Kelola data &gt;</span>
                        </a>
                    @endforeach
                </div>
            </article>

            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-base font-black text-slate-950">Monitoring Kelas</h2>
                        <p class="mt-1 text-xs font-bold text-slate-500">Jumlah siswa dan wali kelas.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black text-slate-500">{{ $jumlahKelas }} kelas</span>
                </div>

                <ul class="grid gap-2.5">
                    @forelse ($kelasList as $kelas)
                        <li class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                            <strong class="block text-sm font-black text-slate-900">{{ $kelas->nama_kelas }}</strong>
                            <span class="mt-1 block text-xs font-semibold text-slate-500">{{ $kelas->siswa_count }} siswa | Wali: {{ $kelas->waliKelas->nama_guru ?? 'Belum diatur' }}</span>
                        </li>
                    @empty
                        <li class="rounded-lg border border-slate-100 bg-slate-50 p-3 text-sm font-semibold text-slate-500">Belum ada kelas.</li>
                    @endforelse
                </ul>
            </article>
        </section>

        <section class="mt-4 grid gap-4 md:grid-cols-3" aria-label="Ringkasan aktivitas">
            <article class="grid grid-cols-[42px_minmax(0,1fr)] gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-xl shadow-slate-900/5">
                <span class="grid size-11 place-items-center rounded-lg bg-emerald-50 text-xs font-black text-emerald-700">PT</span>
                <div>
                    <strong class="block text-sm font-black text-slate-950">Pengumpulan Tugas</strong>
                    <span class="mt-1 block text-xs font-semibold leading-relaxed text-slate-500">{{ $jumlahPengumpulan }} pengumpulan siswa tercatat.</span>
                </div>
            </article>
            <article class="grid grid-cols-[42px_minmax(0,1fr)] gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-xl shadow-slate-900/5">
                <span class="grid size-11 place-items-center rounded-lg bg-blue-50 text-xs font-black text-blue-700">QZ</span>
                <div>
                    <strong class="block text-sm font-black text-slate-950">Pengerjaan Quiz</strong>
                    <span class="mt-1 block text-xs font-semibold leading-relaxed text-slate-500">{{ $jumlahPengerjaanQuiz }} pengerjaan quiz tersimpan.</span>
                </div>
            </article>
            <article class="grid grid-cols-[42px_minmax(0,1fr)] gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-xl shadow-slate-900/5">
                <span class="grid size-11 place-items-center rounded-lg bg-amber-50 text-xs font-black text-amber-700">NL</span>
                <div>
                    <strong class="block text-sm font-black text-slate-950">Rekap Nilai</strong>
                    <span class="mt-1 block text-xs font-semibold leading-relaxed text-slate-500">{{ $jumlahNilai }} data nilai siap dipantau.</span>
                </div>
            </article>
        </section>

        <section class="mt-4 grid gap-4 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-base font-black text-slate-950">Rekap Pengumpulan Per Tugas</h2>
                        <p class="mt-1 text-xs font-bold text-slate-500">Pantau jumlah siswa yang sudah mengumpulkan tugas.</p>
                    </div>
                    <span class="w-fit rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-black text-emerald-700">{{ $jumlahPengumpulan }} total pengumpulan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-3 py-2.5 text-xs font-black uppercase tracking-wide text-slate-500">Tugas</th>
                                <th class="px-3 py-2.5 text-xs font-black uppercase tracking-wide text-slate-500">Kelas</th>
                                <th class="px-3 py-2.5 text-right text-xs font-black uppercase tracking-wide text-slate-500">Terkumpul</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($rekapPengumpulanTugas as $tugas)
                                @php
                                    $jumlahSiswaKelas = $tugas->mengajar?->kelas?->siswa()->count() ?? 0;
                                @endphp

                                <tr class="transition hover:bg-emerald-50/45">
                                    <td class="px-3 py-3">
                                        <strong class="block text-sm font-black text-slate-900">{{ $tugas->judul_tugas }}</strong>
                                        <span class="mt-1 block text-xs font-semibold text-slate-500">{{ $tugas->mengajar->mataPelajaran->nama_mapel ?? '-' }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm font-bold text-slate-600">{{ $tugas->mengajar->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-right">
                                        <span class="inline-flex min-h-8 items-center rounded-lg bg-emerald-50 px-3 text-xs font-black text-emerald-700">
                                            {{ $tugas->pengumpulan_count }} / {{ $jumlahSiswaKelas }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-3 py-8 text-center text-sm font-semibold text-slate-500" colspan="3">Belum ada tugas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-base font-black text-slate-950">Pengumpulan Terbaru</h2>
                        <p class="mt-1 text-xs font-bold text-slate-500">Daftar siswa yang sudah mengirim jawaban tugas.</p>
                    </div>
                    <span class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black text-slate-500">Database siswa</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-3 py-2.5 text-xs font-black uppercase tracking-wide text-slate-500">Siswa</th>
                                <th class="px-3 py-2.5 text-xs font-black uppercase tracking-wide text-slate-500">Tugas</th>
                                <th class="px-3 py-2.5 text-xs font-black uppercase tracking-wide text-slate-500">Waktu</th>
                                <th class="px-3 py-2.5 text-right text-xs font-black uppercase tracking-wide text-slate-500">File</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($pengumpulanTerbaru as $pengumpulan)
                                <tr class="transition hover:bg-emerald-50/45">
                                    <td class="px-3 py-3">
                                        <strong class="block text-sm font-black text-slate-900">{{ $pengumpulan->siswa->nama_siswa ?? '-' }}</strong>
                                        <span class="mt-1 block text-xs font-semibold text-slate-500">{{ $pengumpulan->siswa->kelas->nama_kelas ?? '-' }}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <strong class="block text-sm font-black text-slate-900">{{ $pengumpulan->tugas->judul_tugas ?? '-' }}</strong>
                                        <span class="mt-1 block text-xs font-semibold text-slate-500">
                                            {{ $pengumpulan->tugas->mengajar->mataPelajaran->nama_mapel ?? '-' }}
                                            @if (filled($pengumpulan->nilai))
                                                | Nilai {{ $pengumpulan->nilai }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm font-bold text-slate-600">{{ $pengumpulan->tanggal_kumpul?->format('d M Y H:i') ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-right">
                                        @if (filled($pengumpulan->file_jawaban))
                                            <a class="inline-flex min-h-8 items-center rounded-lg bg-emerald-50 px-3 text-xs font-black text-emerald-700 transition hover:bg-emerald-100" href="{{ asset('storage/' . $pengumpulan->file_jawaban) }}" target="_blank">
                                                Buka File
                                            </a>
                                        @else
                                            <span class="text-xs font-bold text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-3 py-8 text-center text-sm font-semibold text-slate-500" colspan="4">Belum ada pengumpulan tugas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <section class="mt-4 rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-base font-black text-slate-950">Nilai Quiz dan Ulangan Harian</h2>
                    <p class="mt-1 text-xs font-bold text-slate-500">Nilai otomatis dari database pengerjaan quiz siswa.</p>
                </div>
                <span class="w-fit rounded-full bg-blue-50 px-3 py-1.5 text-xs font-black text-blue-700">{{ $jumlahPengerjaanQuiz }} pengerjaan quiz</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-2.5 text-xs font-black uppercase tracking-wide text-slate-500">Siswa</th>
                            <th class="px-3 py-2.5 text-xs font-black uppercase tracking-wide text-slate-500">Quiz/Ulangan</th>
                            <th class="px-3 py-2.5 text-xs font-black uppercase tracking-wide text-slate-500">Waktu Selesai</th>
                            <th class="px-3 py-2.5 text-right text-xs font-black uppercase tracking-wide text-slate-500">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pengerjaanQuizTerbaru as $pengerjaan)
                            <tr class="transition hover:bg-blue-50/45">
                                <td class="px-3 py-3">
                                    <strong class="block text-sm font-black text-slate-900">{{ $pengerjaan->siswa->nama_siswa ?? '-' }}</strong>
                                    <span class="mt-1 block text-xs font-semibold text-slate-500">{{ $pengerjaan->siswa->kelas->nama_kelas ?? '-' }}</span>
                                </td>
                                <td class="px-3 py-3">
                                    <strong class="block text-sm font-black text-slate-900">{{ $pengerjaan->quiz->judul_quiz ?? '-' }}</strong>
                                    <span class="mt-1 block text-xs font-semibold text-slate-500">
                                        {{ $pengerjaan->quiz->mengajar->mataPelajaran->nama_mapel ?? '-' }}
                                        | {{ $pengerjaan->quiz->mengajar->kelas->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-sm font-bold text-slate-600">{{ $pengerjaan->waktu_selesai?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="whitespace-nowrap px-3 py-3 text-right">
                                    <span class="inline-flex min-h-8 items-center rounded-lg bg-blue-50 px-3 text-xs font-black text-blue-700">
                                        {{ filled($pengerjaan->nilai) ? number_format((float) $pengerjaan->nilai, 2) : '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-3 py-8 text-center text-sm font-semibold text-slate-500" colspan="4">Belum ada nilai quiz atau ulangan harian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endcomponent
@endsection
