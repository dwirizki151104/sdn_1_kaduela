@extends('layouts.app')

@section('title', 'SPMB - SD Negeri 1 Kaduela')
@section('meta_description', 'Informasi Sistem Penerimaan Murid Baru SD Negeri 1 Kaduela tahun ajaran 2026/2027.')

@section('content')
    <section class="bg-[#0f5a45] px-4 py-12 text-center text-white sm:py-14 md:py-20">
        <div class="mx-auto max-w-4xl">
            <h1 class="text-[clamp(2rem,5vw,3.1rem)] font-black leading-tight">Sistem Penerimaan Murid Baru</h1>
            <p class="mt-2 text-base font-semibold text-white/90">Tahun Ajaran 2026/2027</p>
            <p class="mt-1 text-sm font-semibold text-white/80">SD Negeri 1 Kaduela</p>
        </div>
    </section>

    <section class="border-y-2 border-amber-400 bg-[#f5edcf] px-4 py-7 md:py-8">
        <div class="mx-auto max-w-4xl rounded-md bg-[#fff8df] px-5 py-4 shadow-sm">
            <h2 class="text-base font-black text-slate-900">Pengumuman Penting</h2>
            <div class="mt-2 grid gap-1.5 text-sm font-semibold leading-relaxed text-slate-800">
                <p>Pendaftaran dilakukan secara <strong class="font-black text-emerald-700">MANUAL</strong> (langsung datang ke sekolah)</p>
                <p>Periode Pendaftaran: 1 April 2026 - 30 Mei 2026</p>
                <p>Jam Pelayanan: Senin - Jumat, 08.00 - 14.00 WIB</p>
                <p>Lokasi: SD Negeri 1 Kaduela, Desa Kaduela, Kec. Pasawahan, Kab. Kuningan</p>
                <p>Kontak: 03523214455</p>
            </div>
        </div>
    </section>

    <section class="bg-white px-4 py-11 md:py-16" id="proses">
        <div class="mx-auto max-w-4xl">
            <h2 class="text-center text-2xl font-black text-slate-950">Proses Pendaftaran</h2>

            <div class="mx-auto mt-9 grid max-w-3xl gap-6">
                @foreach ([
                    ['Ambil Formulir Pendaftaran', 'Datang ke sekolah untuk mengambil formulir pendaftaran atau unduh formulir di website.'],
                    ['Isi Formulir', 'Lengkapi formulir pendaftaran dengan data yang benar dan lengkap.'],
                    ['Siapkan Berkas', 'Siapkan semua berkas persyaratan yang dibutuhkan.'],
                    ['Serahkan ke Sekolah', 'Datang ke sekolah untuk menyerahkan formulir dan berkas pada petugas.'],
                    ['Tunggu Pengumuman', 'Pengumuman hasil seleksi akan diumumkan melalui website atau papan pengumuman sekolah.'],
                ] as [$title, $description])
                    <article class="rounded-md border border-slate-200 border-l-4 border-l-[#0f5a45] bg-white px-5 py-4 shadow-[0_2px_0_rgba(15,23,42,.18)]">
                        <h3 class="text-sm font-black text-slate-950">{{ $title }}</h3>
                        <p class="mt-1 text-xs font-semibold leading-relaxed text-slate-600">{{ $description }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[#eeeeee] px-4 py-11 md:py-16">
        <div class="mx-auto max-w-4xl">
            <h2 class="text-center text-2xl font-black text-slate-950">Berkas yang Dibutuhkan</h2>

            <article class="mx-auto mt-8 max-w-3xl rounded-md bg-white p-5 shadow-sm md:p-6">
                <div class="rounded-md border border-blue-100 bg-blue-50 px-4 py-3 text-xs font-semibold leading-relaxed text-slate-700">
                    <strong>Catatan:</strong> Pastikan semua fotokopi dokumen sudah dilegalisir dan asli dibawa untuk ditunjukkan saat pendaftaran.
                </div>

                <ul class="mx-auto mt-5 grid max-w-xl gap-2 text-sm font-semibold leading-relaxed text-slate-800">
                    <li class="before:mr-2 before:text-[#0f5a45] before:content-['•']">Fotokopi Akta Kelahiran (2 lembar)</li>
                    <li class="before:mr-2 before:text-[#0f5a45] before:content-['•']">Fotokopi Kartu Keluarga (2 lembar)</li>
                    <li class="before:mr-2 before:text-[#0f5a45] before:content-['•']">Fotokopi KTP Orang Tua (2 lembar)</li>
                    <li class="before:mr-2 before:text-[#0f5a45] before:content-['•']">Pas Foto ukuran 3x4 (4 lembar)</li>
                    <li class="before:mr-2 before:text-[#0f5a45] before:content-['•']">Ijazah/STTB TK (jika ada)</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="bg-white px-4 py-11 md:py-16">
        <div class="mx-auto max-w-4xl">
            <h2 class="text-center text-2xl font-black text-slate-950">Syarat Pendaftaran</h2>

            <div class="mx-auto mt-9 grid max-w-3xl gap-5 md:grid-cols-3">
                @foreach ([
                    ['Usia', 'Minimal 6 tahun atau maksimal 7 tahun pada 1 Juli 2026'],
                    ['Domisili', 'Berdomisili di wilayah Kecamatan Pasawahan atau sekitarnya'],
                    ['Kesehatan', 'Sehat jasmani dan rohani dibuktikan dengan surat keterangan dokter'],
                ] as [$title, $description])
                    <article class="min-h-32 rounded-md border border-slate-200 bg-white p-5 text-center shadow-[0_2px_0_rgba(15,23,42,.14)]">
                        <h3 class="text-sm font-black text-slate-950">{{ $title }}</h3>
                        <p class="mt-2 text-xs font-semibold leading-relaxed text-slate-600">{{ $description }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[#eeeeee] px-4 py-11 md:py-16" id="lokasi">
        <div class="mx-auto max-w-4xl">
            <h2 class="text-center text-2xl font-black text-slate-950">Lokasi Sekolah</h2>

            <div class="mx-auto mt-8 max-w-3xl overflow-hidden rounded-md bg-white shadow-[0_2px_8px_rgba(15,23,42,.16)]">
                <article class="grid gap-4 p-5 md:grid-cols-[minmax(0,1fr)_220px] md:items-center">
                    <div>
                        <h3 class="text-base font-black text-slate-950">SD Negeri 1 Kaduela</h3>
                        <p class="mt-2 text-sm font-semibold leading-relaxed text-slate-600">
                            Desa Kaduela, Kecamatan Pasawahan, Kabupaten Kuningan, Jawa Barat 45559.
                        </p>
                        <p class="mt-2 text-xs font-semibold leading-relaxed text-slate-500">
                            Gunakan peta untuk melihat titik lokasi sekolah dan rute menuju tempat pendaftaran.
                        </p>
                    </div>
                    <a class="inline-flex min-h-11 items-center justify-center rounded-md bg-[#0f5a45] px-4 text-xs font-black text-white transition hover:bg-[#103f35]" href="https://www.google.com/maps/search/?api=1&query=SD%20Negeri%201%20Kaduela%20Pasawahan%20Kuningan" target="_blank">
                        Buka Google Maps
                    </a>
                </article>

                <div class="h-[280px] border-t border-slate-100 bg-slate-100 sm:h-[340px] md:h-[420px]">
                    <iframe
                        class="h-full w-full"
                        src="https://www.google.com/maps?q=SD%20Negeri%201%20Kaduela%20Pasawahan%20Kuningan&output=embed"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta lokasi SD Negeri 1 Kaduela"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
@endsection
