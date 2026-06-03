@extends('layouts.app')

@section('title', 'Dashboard Admin - SD Negeri 1 Kaduela')

@section('content')
    @include('dashboard.partials.styles')

    <section class="role-dashboard">
        <div class="role-dashboard-wrap">
            <div class="role-dashboard-top">
                <div>
                    <h1 class="role-dashboard-title">Dashboard Admin</h1>
                    <p class="role-dashboard-subtitle">Login sebagai {{ auth()->user()->username }}</p>
                </div>

                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            </div>

            <div class="role-dashboard-grid">
                <article class="role-dashboard-card"><span>Guru</span><strong>{{ $jumlahGuru }}</strong></article>
                <article class="role-dashboard-card"><span>Siswa</span><strong>{{ $jumlahSiswa }}</strong></article>
                <article class="role-dashboard-card"><span>Kelas</span><strong>{{ $jumlahKelas }}</strong></article>
                <article class="role-dashboard-card"><span>Mata Pelajaran</span><strong>{{ $jumlahMapel }}</strong></article>
            </div>

            <article class="role-dashboard-panel">
                <h2>Menu Utama</h2>
                <ul class="role-dashboard-list">
                    <li>Manajemen guru, siswa, kelas, dan mata pelajaran tersedia lewat route `/akademik/...`.</li>
                    <li>Admin dapat mengatur data mengajar, modul, tugas, quiz, dan nilai.</li>
                </ul>
            </article>
        </div>
    </section>
@endsection
