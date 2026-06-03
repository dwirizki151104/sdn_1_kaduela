<header class="sticky top-0 z-30 bg-[#0f5a45]/95 text-white shadow-[0_1px_0_rgba(255,255,255,0.12)] backdrop-blur">
    <nav class="mx-auto w-full max-w-6xl px-4 md:px-6" aria-label="Navigasi utama">
        <input id="menu-toggle" type="checkbox" class="peer sr-only">

        <div class="flex min-h-[70px] items-center justify-between gap-4">
            <a class="group grid gap-0.5" href="{{ url('/') }}">
                <span class="text-lg font-extrabold leading-tight transition group-hover:text-emerald-100">SD Negeri 1 Kaduela</span>
                <span class="text-[0.66rem] leading-tight text-white/85">Pendidikan Berkualitas Untuk Masa Depan</span>
            </a>

            <label class="grid h-10 w-10 cursor-pointer place-items-center rounded-md border border-white/20 text-xl transition hover:bg-white/10 md:hidden" for="menu-toggle" aria-label="Buka menu">
                <span aria-hidden="true">☰</span>
            </label>

            <div class="hidden items-center gap-6 text-xs font-bold md:flex">
                <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                <a class="nav-link" href="{{ route('profil') }}">Profil Sekolah</a>
                <a class="nav-link" href="{{ url('/#spmb') }}">SPMB</a>
                <a class="nav-link" href="{{ url('/#galeri') }}">Galeri</a>
                <a class="nav-link" href="{{ route('elearning') }}">E-Learning</a>
            </div>
        </div>

        <div class="grid max-h-0 overflow-hidden border-t border-white/0 text-sm font-semibold transition-all duration-300 peer-checked:max-h-80 peer-checked:border-white/15 md:hidden">
            <div class="grid gap-1 py-3">
                <a class="rounded-md px-3 py-2 transition hover:bg-white/10" href="{{ url('/') }}">Beranda</a>
                <a class="rounded-md px-3 py-2 transition hover:bg-white/10" href="{{ route('profil') }}">Profil Sekolah</a>
                <a class="rounded-md px-3 py-2 transition hover:bg-white/10" href="{{ url('/#spmb') }}">SPMB</a>
                <a class="rounded-md px-3 py-2 transition hover:bg-white/10" href="{{ url('/#galeri') }}">Galeri</a>
                <a class="rounded-md px-3 py-2 transition hover:bg-white/10" href="{{ route('elearning') }}">E-Learning</a>
            </div>
        </div>
    </nav>
</header>
