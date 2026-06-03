@extends('layouts.app')

@section('title', 'SD Negeri 1 Kaduela')

@section('content')
    <section
        class="grid min-h-[360px] place-items-center bg-cover bg-center px-4 text-center text-white md:min-h-[430px]"
        style="background-image: linear-gradient(rgba(0,0,0,.32), rgba(0,0,0,.62)), url('https://images.unsplash.com/photo-1596495577886-d920f1fb7238?auto=format&fit=crop&w=1800&q=82')"
        aria-labelledby="hero-title"
    >
        <div class="animate-fade-up w-full max-w-[920px] pt-6 [text-shadow:0_2px_10px_rgba(0,0,0,.42)]">
            <h1 id="hero-title" class="text-[clamp(2rem,5vw,3.1rem)] font-black leading-none">
                Selamat Datang di SD Negeri 1 Kaduela
            </h1>
            <p class="mx-auto mt-2 max-w-[610px] text-[clamp(.88rem,2vw,1.05rem)] font-semibold leading-tight">
                Membentuk generasi cerdas, berakhlak mulia, dan berprestasi melalui pendidikan berkualitas
            </p>
            <a class="mt-4 inline-flex min-h-10 items-center justify-center gap-2 rounded-md bg-[#0b6549] px-4 py-2 text-xs font-extrabold text-white shadow-[0_2px_0_rgba(0,0,0,.16)] transition hover:-translate-y-0.5 hover:bg-[#176b51]" href="{{ route('elearning') }}">
                E-Learning <span aria-hidden="true">></span>
            </a>
        </div>
    </section>

    <section class="bg-[#efefee] px-4 py-11 md:py-14" aria-label="Statistik sekolah">
        <div class="mx-auto grid w-full max-w-[980px] gap-4 sm:grid-cols-2 md:gap-8 lg:grid-cols-4">
            @foreach ([['74', 'Siswa Aktif'], ['12', 'Guru Berpengalaman'], ['15', 'Mata Pelajaran'], ['35+', 'Tahun Berdiri']] as [$number, $label])
                <article class="grid min-h-32 place-items-center content-center gap-2 rounded-md bg-white p-5 text-center shadow-[0_2px_5px_rgba(17,24,39,.18)] transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <strong class="text-2xl font-black">{{ $number }}</strong>
                    <span class="text-xs text-[#49534f]">{{ $label }}</span>
                </article>
            @endforeach
        </div>
    </section>

    <section class="border-b border-[#d9ddda] bg-white px-4 py-11 md:py-16" id="elearning">
        <div class="mx-auto grid w-full max-w-[980px] items-center gap-6 md:grid-cols-[minmax(250px,370px)_minmax(0,1fr)] md:gap-12">
            <img
                class="aspect-[1.33] w-full rounded-md object-cover shadow-lg"
                src="https://images.unsplash.com/photo-1603354350317-6f7aaa5911c5?auto=format&fit=crop&w=720&q=80"
                alt="Ilustrasi pembelajaran digital untuk siswa sekolah dasar"
            >

            <div>
                <h2 class="text-[clamp(1.5rem,3vw,2.05rem)] font-black leading-tight">Platform E-Learning Interaktif</h2>
                <p class="mt-2 max-w-[560px] text-sm leading-relaxed text-[#65716d]">
                    Kami menyediakan platform pembelajaran online yang memudahkan siswa untuk mengakses materi pelajaran,
                    mengerjakan tugas, dan berinteraksi dengan guru kapan saja dan di mana saja.
                </p>

                <ul class="mt-4 grid gap-3">
                    <li class="grid gap-0.5">
                        <strong class="text-sm font-black">Materi Lengkap</strong>
                        <span class="text-xs text-[#65716d]">Akses materi pelajaran dari semua mata pelajaran</span>
                    </li>
                    <li class="grid gap-0.5">
                        <strong class="text-sm font-black">Kuis & Latihan</strong>
                        <span class="text-xs text-[#65716d]">Uji pemahaman dengan kuis interaktif</span>
                    </li>
                </ul>

                <a class="mt-5 inline-flex min-h-10 items-center justify-center gap-2 rounded-md bg-[#0b6549] px-4 py-2 text-xs font-extrabold text-white shadow-[0_2px_0_rgba(0,0,0,.16)] transition hover:-translate-y-0.5 hover:bg-[#176b51]" href="{{ route('elearning') }}">
                    Mulai Belajar <span aria-hidden="true">></span>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-[#f5f5f5] px-4 py-11 md:py-20" id="spmb">
        <div class="mx-auto w-full max-w-[980px]">
            <h2 class="mb-10 text-center text-[clamp(1.5rem,3vw,2.05rem)] font-black leading-tight">Berita & Pengumuman</h2>

            <div class="grid gap-5 md:grid-cols-3 md:gap-8">
                @foreach ([
                    ['Sistem Penerimaan Murid Baru 2026/2027', 'Pendaftaran siswa baru tahun ajaran 2026/2027 telah dibuka. Informasi lengkap dapat dilihat di pengumuman sekolah.', 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=640&q=80', 'Pengumuman penerimaan murid baru'],
                    ['Workshop Pembelajaran Digital', 'Guru-guru mengikuti pelatihan pembelajaran digital untuk meningkatkan kualitas e-learning.', 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=640&q=80', 'Workshop pembelajaran digital untuk guru'],
                    ['Perpustakaan Digital Diluncurkan', 'Siswa kini dapat mengakses ribuan buku digital melalui platform perpustakaan online sekolah.', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=640&q=80', 'Siswa menggunakan komputer di perpustakaan digital'],
                ] as [$title, $description, $image, $alt])
                    <article class="overflow-hidden rounded-md bg-white shadow-[0_2px_5px_rgba(17,24,39,.18)] transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <img class="aspect-[1.65] w-full object-cover" src="{{ $image }}" alt="{{ $alt }}">
                        <div class="min-h-36 p-4">
                            <h3 class="mb-2 text-sm font-black leading-tight">{{ $title }}</h3>
                            <p class="text-xs leading-relaxed text-[#65716d]">{{ $description }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
