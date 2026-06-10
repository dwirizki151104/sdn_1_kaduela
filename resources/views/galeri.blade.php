@extends('layouts.app')

@section('title', 'Galeri Foto - SD Negeri 1 Kaduela')
@section('meta_description', 'Galeri dokumentasi kegiatan pembelajaran, ekstrakurikuler, acara, dan event SD Negeri 1 Kaduela.')

@section('content')
    <section class="bg-[#0f5a45] px-4 py-12 text-center text-white sm:py-14 md:py-20">
        <div class="mx-auto max-w-4xl">
            <h1 class="text-[clamp(2rem,5vw,3.1rem)] font-black leading-tight">Galeri Foto</h1>
            <p class="mt-2 text-base font-semibold text-white/90">Dokumentasi kegiatan dan momen berharga</p>
            <p class="mt-1 text-sm font-semibold text-white/80">SD Negeri 1 Kaduela</p>
        </div>
    </section>

    @php
        $galleryImage = fn (string $path, string $fallback) => file_exists(public_path($path)) ? asset($path) : $fallback;

        $gallerySections = [
            [
                'title' => 'Kegiatan Pembelajaran',
                'background' => 'bg-white',
                'items' => [
                    ['image' => $galleryImage('images/galeri/pembelajaran-kelas.jpg', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=640&q=82'), 'alt' => 'Suasana pembelajaran siswa di kelas'],
                    ['image' => $galleryImage('images/galeri/pembelajaran-karya.jpg', 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=640&q=82'), 'alt' => 'Siswa menunjukkan hasil karya pembelajaran'],
                    ['image' => $galleryImage('images/galeri/pembelajaran-kerajinan.jpg', 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=640&q=82'), 'alt' => 'Siswa membuat kerajinan bunga dari tutup botol'],
                ],
            ],
            [
                'title' => 'Kegiatan Ekstrakurikuler',
                'background' => 'bg-[#eeeeee]',
                'items' => [
                    ['image' => 'https://images.unsplash.com/photo-1542621334-a254cf47733d?auto=format&fit=crop&w=640&q=82', 'alt' => 'Kegiatan pramuka dan kelompok siswa'],
                    ['image' => 'https://images.unsplash.com/photo-1518091043644-c1d4457512c6?auto=format&fit=crop&w=640&q=82', 'alt' => 'Bola sepak untuk kegiatan olahraga'],
                    ['image' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=640&q=82', 'alt' => 'Raket bulu tangkis untuk ekstrakurikuler olahraga'],
                ],
            ],
            [
                'title' => 'Acara dan Event',
                'background' => 'bg-white',
                'items' => [
                    ['image' => $galleryImage('images/galeri/event-upacara.jpg', 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=640&q=82'), 'alt' => 'Kegiatan upacara dan barisan siswa di halaman sekolah'],
                    ['image' => $galleryImage('images/galeri/event-keagamaan.jpg', 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=640&q=82'), 'alt' => 'Kegiatan keagamaan siswa SD Negeri 1 Kaduela'],
                    ['image' => $galleryImage('images/galeri/event-foto-bersama.jpg', 'https://images.unsplash.com/photo-1594608661623-aa0bd3a69799?auto=format&fit=crop&w=640&q=82'), 'alt' => 'Foto bersama siswa, guru, dan keluarga sekolah'],
                ],
            ],
        ];
    @endphp

    @foreach ($gallerySections as $section)
        <section class="{{ $section['background'] }} px-4 py-12 md:py-16">
            <div class="mx-auto max-w-5xl">
                <h2 class="text-center text-2xl font-black text-slate-950">{{ $section['title'] }}</h2>

                <div class="mx-auto mt-8 grid max-w-4xl gap-6 sm:grid-cols-2 md:grid-cols-3">
                    @foreach ($section['items'] as $item)
                        <article class="overflow-hidden rounded-md bg-white shadow-[0_2px_6px_rgba(15,23,42,.18)] transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                            <img class="aspect-[4/3] w-full object-cover" src="{{ $item['image'] }}" alt="{{ $item['alt'] }}">
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach
@endsection
