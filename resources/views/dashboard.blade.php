@extends('layouts.app')

@section('title', 'Dashboard E-Learning - SD Negeri 1 Kaduela')

@section('content')
    <style>
        .dashboard-page {
            min-height: calc(100vh - 70px);
            background: #f7faf8;
            padding: 42px 16px 70px;
            color: #111827;
        }

        .dashboard-wrap {
            width: min(100%, 980px);
            margin: 0 auto;
        }

        .dashboard-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
        }

        .dashboard-title {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 900;
            color: #0f5a45;
        }

        .logout-btn {
            border: 0;
            border-radius: 8px;
            background: #0f5a45;
            color: #fff;
            padding: 10px 15px;
            font-weight: 800;
            cursor: pointer;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .dashboard-card {
            border: 1px solid #d8e7e2;
            border-radius: 8px;
            background: #fff;
            padding: 18px;
        }

        .dashboard-card span {
            display: block;
            color: #64748b;
            font-size: 0.76rem;
            font-weight: 800;
        }

        .dashboard-card strong {
            display: block;
            margin-top: 8px;
            font-size: 2rem;
            color: #111827;
        }

        @media (max-width: 760px) {
            .dashboard-top {
                align-items: flex-start;
                flex-direction: column;
            }

            .dashboard-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>

    <section class="dashboard-page">
        <div class="dashboard-wrap">
            <div class="dashboard-top">
                <div>
                    <h1 class="dashboard-title">Dashboard E-Learning</h1>
                    <p>Masuk sebagai {{ auth()->user()->role }} - {{ auth()->user()->username }}</p>
                </div>

                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            </div>

            <div class="dashboard-grid">
                <article class="dashboard-card"><span>Guru</span><strong>{{ $jumlahGuru }}</strong></article>
                <article class="dashboard-card"><span>Siswa</span><strong>{{ $jumlahSiswa }}</strong></article>
                <article class="dashboard-card"><span>Kelas</span><strong>{{ $jumlahKelas }}</strong></article>
                <article class="dashboard-card"><span>Mata Pelajaran</span><strong>{{ $jumlahMapel }}</strong></article>
            </div>
        </div>
    </section>
@endsection
