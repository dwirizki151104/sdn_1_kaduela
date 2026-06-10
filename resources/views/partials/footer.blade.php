<footer class="bg-[#183d32] px-4 py-10 text-white md:py-14">
    <div class="mx-auto grid w-full max-w-6xl gap-9 border-b border-white/20 pb-9 md:grid-cols-[1.25fr_0.8fr_0.9fr_1fr] md:gap-10">
        <div>
            <h2 class="mb-3 text-lg font-extrabold">SD Negeri 1 Kaduela</h2>
            <p class="max-w-sm text-xs leading-relaxed text-white/75">
                Lembaga pendidikan dasar yang berkomitmen memberikan pendidikan berkualitas untuk membentuk generasi cerdas, berakhlak mulia, dan berprestasi.
            </p>
        </div>

        <div>
            <h2 class="mb-3 text-sm font-extrabold">Menu</h2>
            <div class="grid gap-2 text-xs text-white/75">
                <a class="transition hover:text-white" href="{{ url('/') }}">Beranda</a>
                <a class="transition hover:text-white" href="{{ route('profil') }}">Profil Sekolah</a>
                <a class="transition hover:text-white" href="{{ route('spmb') }}">SPMB</a>
                <a class="transition hover:text-white" href="{{ route('galeri') }}">Galeri</a>
                <a class="transition hover:text-white" href="{{ url('/#elearning') }}">E-Learning</a>
            </div>
        </div>

        <div>
            <h2 class="mb-3 text-sm font-extrabold">Kontak</h2>
            <p class="text-xs leading-relaxed text-white/75">
                03523214455<br>
                sdn1kaduela@gmail.com
            </p>
        </div>

        <div>
            <h2 class="mb-3 text-sm font-extrabold">Alamat</h2>
            <p class="max-w-sm text-xs leading-relaxed text-white/75">
                Desa Kaduela, Kec. Pasawahan<br>
                Kabupaten Kuningan, Jawa Barat 45559
            </p>
        </div>
    </div>
    <p class="mt-8 text-center text-xs text-white/70">&copy; 2026 SD Negeri 1 Kaduela. All rights reserved.</p>
</footer>
