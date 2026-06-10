@php
    $adminMenu = [
        ['label' => 'Dashboard', 'icon' => 'M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-12h8V3h-8v6Z', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
        ['label' => 'Data User', 'resource' => 'users', 'icon' => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75', 'href' => route('admin.master.index', 'users'), 'active' => request()->is('admin/master/users*')],
        ['label' => 'Data Guru', 'resource' => 'guru', 'icon' => 'M22 10 12 5 2 10l10 5 10-5ZM6 12v5c3 3 9 3 12 0v-5', 'href' => route('admin.master.index', 'guru'), 'active' => request()->is('admin/master/guru*')],
        ['label' => 'Data Siswa', 'resource' => 'siswa', 'icon' => 'M4 19.5V6.5A2.5 2.5 0 0 1 6.5 4H20v16H6.5A2.5 2.5 0 0 1 4 17.5Zm3-12h10M7 11h7M7 14h8', 'href' => route('admin.master.index', 'siswa'), 'active' => request()->is('admin/master/siswa*')],
        ['label' => 'Data Kelas', 'resource' => 'kelas', 'icon' => 'M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16M9 8h1m4 0h1M9 12h1m4 0h1M9 16h1m4 0h1', 'href' => route('admin.master.index', 'kelas'), 'active' => request()->is('admin/master/kelas*')],
        ['label' => 'Mata Pelajaran', 'resource' => 'mata-pelajaran', 'icon' => 'M12 6.25c-2-1.5-5-2-8-1.25v13c3-.75 6-.25 8 1.25m0-13c2-1.5 5-2 8-1.25v13c-3-.75-6-.25-8 1.25m0-13v13', 'href' => route('admin.master.index', 'mata-pelajaran'), 'active' => request()->is('admin/master/mata-pelajaran*')],
        ['label' => 'Data Mengajar', 'resource' => 'mengajar', 'icon' => 'M7 8h10M7 12h6m-8 9h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Zm12-7 3 3 4-6', 'href' => route('admin.master.index', 'mengajar'), 'active' => request()->is('admin/master/mengajar*')],
        ['label' => 'Modul', 'resource' => 'modul', 'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Zm0 0v6h6M8 13h8M8 17h6', 'href' => route('admin.master.index', 'modul'), 'active' => request()->is('admin/master/modul*')],
        ['label' => 'Tugas', 'resource' => 'tugas', 'icon' => 'M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11', 'href' => route('admin.master.index', 'tugas'), 'active' => request()->is('admin/master/tugas*')],
        ['label' => 'Pengumpulan Tugas', 'resource' => 'pengumpulan-tugas', 'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Zm0 0v6h6M9 15l2 2 4-5', 'href' => route('admin.master.index', 'pengumpulan-tugas'), 'active' => request()->is('admin/master/pengumpulan-tugas*')],
        ['label' => 'Quiz', 'resource' => 'quiz', 'icon' => 'M9.09 9a3 3 0 1 1 5.83 1c0 2-3 2.5-3 4m.08 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'href' => route('admin.master.index', 'quiz'), 'active' => request()->is('admin/master/quiz*')],
        ['label' => 'Jawaban Siswa', 'resource' => 'jawaban-siswa', 'icon' => 'M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11M7 7h7M7 11h5M7 15h3', 'href' => route('admin.master.index', 'jawaban-siswa'), 'active' => request()->is('admin/master/jawaban-siswa*')],
        ['label' => 'Nilai Quiz', 'resource' => 'nilai-quiz', 'icon' => 'M4 19V5m0 14h16M8 17V9m4 8V5m4 12v-6M18 7l2 2 3-5', 'href' => route('admin.master.index', 'nilai-quiz'), 'active' => request()->is('admin/master/nilai-quiz*')],
        ['label' => 'Nilai', 'resource' => 'nilai', 'icon' => 'M4 19V5m0 14h16M8 17V9m4 8V5m4 12v-6', 'href' => route('admin.master.index', 'nilai'), 'active' => request()->is('admin/master/nilai') || request()->is('admin/master/nilai/*')],
    ];

    $guruAllowedResources = ['modul', 'tugas', 'pengumpulan-tugas', 'quiz', 'jawaban-siswa', 'nilai-quiz', 'nilai'];

    if (auth()->user()?->role === 'guru') {
        $adminMenu = array_values(array_filter($adminMenu, fn ($item) => empty($item['resource']) || in_array($item['resource'], $guruAllowedResources, true)));
    }
@endphp

<aside class="bg-[#123b33] p-3 text-white lg:sticky lg:top-0 lg:h-screen lg:overflow-hidden lg:p-4">
    <div class="flex min-h-0 flex-col rounded-lg border border-white/10 bg-white/[0.04] p-4 shadow-2xl shadow-emerald-950/20 lg:h-full">
        <div class="grid shrink-0 grid-cols-[46px_minmax(0,1fr)] items-center gap-3 border-b border-white/10 pb-4">
            <span class="grid size-[46px] place-items-center rounded-lg bg-amber-300 text-emerald-950 shadow-lg shadow-emerald-950/15">
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 19.5V6.5A2.5 2.5 0 0 1 6.5 4H20v16H6.5A2.5 2.5 0 0 1 4 17.5Z" />
                    <path d="M8 8h8M8 12h7M8 16h5" />
                </svg>
            </span>
            <div>
                <strong class="block text-[0.98rem] font-black leading-tight">SD Negeri 1 Kaduela</strong>
                <span class="mt-1 block text-[0.68rem] font-bold leading-snug text-white/65">Sistem Informasi Akademik dan E-Learning</span>
            </div>
        </div>

        <p class="mb-2 mt-4 shrink-0 px-2 text-[0.66rem] font-black uppercase tracking-widest text-white/45">Navigasi</p>

        <nav class="grid min-h-0 flex-1 gap-1.5 overflow-y-auto pr-1 [scrollbar-color:rgba(245,208,111,.7)_transparent] [scrollbar-width:thin]" aria-label="Menu admin">
            @foreach ($adminMenu as $item)
                <a class="group flex min-h-11 items-center justify-between gap-3 rounded-lg px-2.5 py-2 text-[0.78rem] font-extrabold transition {{ $item['active'] ? 'bg-white/15 text-white shadow-[inset_3px_0_0_#f5d06f]' : 'text-white/75 hover:bg-white/10 hover:text-white' }}" href="{{ $item['href'] }}">
                    <span class="inline-flex min-w-0 items-center gap-3">
                        <span class="grid size-8 shrink-0 place-items-center rounded-lg {{ $item['active'] ? 'bg-amber-300 text-emerald-950' : 'bg-white/10 text-white/85 group-hover:bg-white/15 group-hover:text-white' }}">
                            <svg class="size-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $item['icon'] }}" />
                            </svg>
                        </span>
                        <span class="truncate">{{ $item['label'] }}</span>
                    </span>
                    <svg class="size-4 shrink-0 text-white/35 transition group-hover:translate-x-0.5 group-hover:text-white/65" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            @endforeach
        </nav>

        <div class="mt-4 shrink-0 border-t border-white/10 pt-4">
            <p class="mb-3 text-[0.7rem] font-bold leading-relaxed text-white/55">{{ auth()->user()?->role === 'guru' ? 'Guru dapat memantau materi, tugas, quiz, jawaban, dan nilai sesuai data mengajarnya.' : 'Admin mengelola data master sekolah dan memantau aktivitas pembelajaran digital.' }}</p>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="flex min-h-11 w-full items-center justify-between rounded-lg px-2.5 py-2 text-left text-[0.78rem] font-extrabold text-white/75 transition hover:bg-white/10 hover:text-white" type="submit">
                    <span class="inline-flex items-center gap-3">
                        <span class="grid size-8 place-items-center rounded-lg bg-white/10 text-white/85">
                            <svg class="size-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <path d="m16 17 5-5-5-5" />
                                <path d="M21 12H9" />
                            </svg>
                        </span>
                        Logout
                    </span>
                    <svg class="size-4 text-white/35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
