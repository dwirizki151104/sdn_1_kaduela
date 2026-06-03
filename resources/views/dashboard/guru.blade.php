@extends('layouts.app')

@section('title', 'Dashboard Guru - SD Negeri 1 Kaduela')

@section('content')
    @include('dashboard.partials.styles')

    <section class="role-dashboard">
        <div class="role-dashboard-wrap">
            <div class="role-dashboard-top">
                <div>
                    <h1 class="role-dashboard-title">Dashboard Guru</h1>
                    <p class="role-dashboard-subtitle">{{ $guru?->nama_guru ?? auth()->user()->username }}</p>
                </div>

                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            </div>

            <div class="role-dashboard-grid">
                <article class="role-dashboard-card"><span>Jadwal Mengajar</span><strong>{{ $jumlahMengajar }}</strong></article>
                <article class="role-dashboard-card"><span>Modul</span><strong>{{ $jumlahModul }}</strong></article>
                <article class="role-dashboard-card"><span>Tugas</span><strong>{{ $jumlahTugas }}</strong></article>
                <article class="role-dashboard-card"><span>Quiz</span><strong>{{ $jumlahQuiz }}</strong></article>
            </div>

            <article class="role-dashboard-panel">
                <h2>Data Mengajar</h2>
                <ul class="role-dashboard-list">
                    @forelse ($guru?->mengajar ?? [] as $mengajar)
                        <li>{{ $mengajar->mataPelajaran->nama_mapel }} - {{ $mengajar->kelas->nama_kelas }}</li>
                    @empty
                        <li>Belum ada data mengajar.</li>
                    @endforelse
                </ul>
            </article>
        </div>
    </section>
@endsection
