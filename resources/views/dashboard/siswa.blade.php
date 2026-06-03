@extends('layouts.app')

@section('title', 'Dashboard Siswa - SD Negeri 1 Kaduela')

@section('content')
    @include('dashboard.partials.styles')

    <section class="role-dashboard">
        <div class="role-dashboard-wrap">
            <div class="role-dashboard-top">
                <div>
                    <h1 class="role-dashboard-title">Dashboard Siswa</h1>
                    <p class="role-dashboard-subtitle">{{ $siswa?->nama_siswa ?? auth()->user()->username }} - {{ $siswa?->kelas?->nama_kelas ?? 'Belum ada kelas' }}</p>
                </div>

                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            </div>

            <div class="role-dashboard-grid">
                <article class="role-dashboard-card"><span>Tugas Dikumpulkan</span><strong>{{ $jumlahTugasDikumpulkan }}</strong></article>
                <article class="role-dashboard-card"><span>Quiz Dikerjakan</span><strong>{{ $jumlahQuizDikerjakan }}</strong></article>
                <article class="role-dashboard-card"><span>Data Nilai</span><strong>{{ $jumlahNilai }}</strong></article>
                <article class="role-dashboard-card"><span>Rata-rata</span><strong>{{ $rataNilaiAkhir ? number_format($rataNilaiAkhir, 1) : '-' }}</strong></article>
            </div>

            <article class="role-dashboard-panel">
                <h2>Aktivitas</h2>
                <ul class="role-dashboard-list">
                    <li>Siswa dapat melihat modul, mengumpulkan tugas, mengerjakan quiz, dan melihat nilai.</li>
                    <li>Dashboard ini masih sederhana dan siap dikembangkan menjadi menu lengkap.</li>
                </ul>
            </article>
        </div>
    </section>
@endsection
