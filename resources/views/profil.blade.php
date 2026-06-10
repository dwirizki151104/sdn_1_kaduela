@extends('layouts.app')

@section('title', 'Profil Sekolah - SD Negeri 1 Kaduela')
@section('meta_description', 'Profil SD Negeri 1 Kaduela: sambutan kepala sekolah, visi misi, fasilitas, prestasi, dan tenaga pendidik.')

@section('content')
    <section class="bg-[#0f5a45] px-4 py-12 text-center text-white sm:py-14 md:py-20">
        <div class="animate-fade-up mx-auto max-w-4xl">
            <h1 class="text-[clamp(2rem,5vw,3.1rem)] font-black leading-tight">Mengenal Lebih Dekat SD Negeri 1 Kaduela</h1>
            <p class="mt-2 text-base font-semibold text-white/90">Sekolah dasar yang tumbuh bersama masyarakat Kaduela</p>
            <p class="mt-1 text-sm font-semibold text-white/80">Pembelajaran bermakna, karakter kuat, dan lingkungan belajar yang nyaman</p>
        </div>
    </section>

    <section class="bg-[#f4f4f3] px-4 py-12 md:py-16">
        <div class="mx-auto grid w-full max-w-6xl gap-8 lg:grid-cols-[0.9fr_1.25fr] lg:items-center">
            <div class="animate-fade-up overflow-hidden rounded-lg shadow-xl">
                <img
                    class="aspect-[4/3] w-full object-cover"
                    src="{{ asset('images/kepala-sekolah.jpg') }}"
                    alt="Kepala sekolah SD Negeri 1 Kaduela"
                >
            </div>
            <article class="animate-fade-up rounded-lg bg-white p-6 shadow-[0_12px_35px_rgba(17,24,39,.10)] md:p-8">
                <span class="mb-3 inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-[#0f5a45]">Sambutan</span>
                <h2 class="text-2xl font-black leading-tight md:text-3xl">Sambutan Kepala Sekolah</h2>
                <p class="mt-4 text-sm leading-relaxed text-slate-600">
                    Assalamu'alaikum warahmatullahi wabarakatuh. Puji syukur kita panjatkan ke hadirat Tuhan Yang Maha Esa karena SD Negeri 1 Kaduela terus berkomitmen menghadirkan pendidikan yang ramah anak, disiplin, dan berorientasi pada karakter.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                    Melalui kerja sama guru, siswa, orang tua, dan masyarakat, kami berupaya menciptakan lingkungan belajar yang aktif, kreatif, dan menyenangkan agar setiap anak dapat berkembang sesuai potensinya.
                </p>
                <p class="mt-5 font-semibold text-[#0f5a45]">Kepala SD Negeri 1 Kaduela</p>
            </article>
        </div>
    </section>

    <section class="bg-white px-4 py-12 md:py-16" id="profil">
        <div class="mx-auto grid w-full max-w-6xl gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
            <article class="animate-fade-up">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-[#0f5a45]">Tentang Kami</p>
                <h2 class="text-2xl font-black leading-tight md:text-3xl">Sekolah yang dekat dengan siswa, keluarga, dan lingkungan</h2>
                <p class="mt-4 text-sm leading-relaxed text-slate-600">
                    SD Negeri 1 Kaduela merupakan sekolah dasar negeri yang menjadi ruang tumbuh bagi anak-anak untuk belajar pengetahuan, keterampilan, dan nilai kehidupan. Pembelajaran dirancang agar siswa aktif bertanya, berani mencoba, dan terbiasa bekerja sama.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                    Dengan dukungan tenaga pendidik berpengalaman, fasilitas belajar yang terus dikembangkan, dan budaya sekolah yang positif, kami berusaha memberi pengalaman pendidikan yang utuh bagi setiap siswa.
                </p>
            </article>

            <div class="animate-fade-up overflow-hidden rounded-lg shadow-xl">
                <img
                    class="aspect-[4/3] w-full object-cover"
                    src="{{ asset('images/sekolah.jpg') }}"
                    alt="Gedung dan lingkungan sekolah"
                >
            </div>
        </div>
    </section>

    <section class="bg-[#eef2ef] px-4 py-12 md:py-16">
        <div class="mx-auto grid w-full max-w-6xl gap-5 md:grid-cols-2">
            <article class="animate-fade-up rounded-lg bg-[#0f5a45] p-6 text-white shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-2xl md:p-8">
                <h2 class="text-2xl font-black">Visi</h2>
                <p class="mt-4 text-sm leading-relaxed text-white/90">
                    Mewujudkan peserta didik yang cerdas, berkarakter, berprestasi, peduli lingkungan, serta berlandaskan iman dan takwa.
                </p>
            </article>

            <article class="animate-fade-up rounded-lg p-6 text-white shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-2xl md:p-8" style="background-color: #b7791f;">
                <h2 class="text-2xl font-black">Misi</h2>
                <ul class="mt-4 grid gap-3 text-sm leading-relaxed">
                    <li>Menyelenggarakan pembelajaran aktif, kreatif, dan menyenangkan.</li>
                    <li>Membiasakan budaya disiplin, jujur, tanggung jawab, dan gotong royong.</li>
                    <li>Mengembangkan potensi akademik, seni, olahraga, dan literasi siswa.</li>
                    <li>Membangun lingkungan sekolah yang aman, bersih, dan nyaman.</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="bg-white px-4 py-12 md:py-16">
        <div class="mx-auto w-full max-w-6xl">
            <div class="animate-fade-up mb-8 text-center">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-[#0f5a45]">Fasilitas</p>
                <h2 class="text-2xl font-black md:text-3xl">Fasilitas Sekolah</h2>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Ruang Kelas', 'Ruang belajar nyaman untuk kegiatan harian siswa.'],
                    ['Perpustakaan', 'Sumber bacaan untuk literasi dan eksplorasi pengetahuan.'],
                    ['Lab Komputer', 'Dukungan pembelajaran digital dan keterampilan TIK.'],
                    ['Lapangan', 'Area olahraga, upacara, dan aktivitas luar ruang.'],
                ] as [$facility, $description])
                    <article class="animate-fade-up rounded-lg border border-slate-100 bg-slate-50 p-5 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:bg-white hover:shadow-xl">
                        <div class="mx-auto mb-4 grid h-12 w-12 place-items-center rounded-full bg-[#0f5a45] text-sm font-black text-white">{{ substr($facility, 0, 2) }}</div>
                        <h3 class="font-extrabold">{{ $facility }}</h3>
                        <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ $description }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[#f4f4f3] px-4 py-12 md:py-16">
        <div class="mx-auto w-full max-w-6xl">
            <div class="animate-fade-up mb-8 text-center">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-[#0f5a45]">Prestasi</p>
                <h2 class="text-2xl font-black md:text-3xl">Prestasi Sekolah</h2>
            </div>

            @php
                $literasiImage = file_exists(public_path('images/prestasi-literasi.jpg'))
                    ? asset('images/prestasi-literasi.jpg')
                    : 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=520&q=80';
                $paiImage = file_exists(public_path('images/prestasi-pai.jpg'))
                    ? asset('images/prestasi-pai.jpg')
                    : 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=520&q=80';
                $olahragaImage = file_exists(public_path('images/prestasi-olahraga.jpg'))
                    ? asset('images/prestasi-olahraga.jpg')
                    : 'https://images.unsplash.com/photo-1547347298-4074fc3086f0?auto=format&fit=crop&w=520&q=80';
            @endphp

            <div class="grid gap-5 md:grid-cols-3">
                @foreach ([
                    ['Juara Lomba Literasi', 'Mendorong budaya membaca, bahasa, dan keberanian berprestasi.', $literasiImage],
                    ['Prestasi PAI', 'Mengembangkan karakter, hafalan, dan kecintaan pada nilai keagamaan.', $paiImage],
                    ['Prestasi Lomba Cerita Bergambar', 'Mengembangkan kesehatan jasmani dan sportivitas.', $olahragaImage],
                ] as [$title, $description, $image])
                    <article class="animate-fade-up overflow-hidden rounded-lg bg-white shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                        <img class="aspect-[1.55] w-full object-cover" src="{{ $image }}" alt="{{ $title }}">
                        <div class="p-5">
                            <h3 class="font-extrabold">{{ $title }}</h3>
                            <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ $description }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white px-4 py-12 md:py-16">
        <div class="mx-auto w-full max-w-6xl">
            <div class="animate-fade-up mb-8 text-center">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-[#0f5a45]">Tenaga Pendidik</p>
                <h2 class="text-2xl font-black md:text-3xl">Guru & Tenaga Kependidikan</h2>
            </div>

            <div class="animate-fade-up overflow-hidden rounded-lg shadow-xl">
                <img
                    class="aspect-[2.8] min-h-52 w-full object-cover"
                   src="{{ asset('images/guru-tenaga-kependidikan.jpg') }}"
                    alt="Guru dan tenaga kependidikan SD Negeri 1 Kaduela"
                >
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (['Kepala Sekolah', 'Guru Kelas I', 'Guru Kelas II', 'Guru Kelas III', 'Guru Kelas IV', 'Guru Kelas V', 'Guru Kelas VI', 'Guru PJOK' ,'Guru SBK' ,'Guru PAI' ,'Penjaga Sekolah'] as $role)
                    <article class="animate-fade-up rounded-lg bg-[#f7f8f7] p-5 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-xl">
                        <div class="mx-auto mb-3 h-14 w-14 rounded-full bg-gradient-to-br from-[#0f5a45] to-[#f5b434]"></div>
                        <h3 class="text-sm font-extrabold">{{ $role }}</h3>
                        <p class="mt-1 text-xs text-slate-500">SD Negeri 1 Kaduela</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
