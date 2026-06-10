@extends('layouts.app')

@section('title', 'Kerjakan Quiz - SD Negeri 1 Kaduela')

@section('content')
    @include('dashboard.partials.styles')

    <style>
        .quiz-workspace {
            display: grid;
            gap: 16px;
            margin-top: 16px;
        }

        .quiz-shell {
            border: 1px solid #d9ebe4;
            border-radius: 8px;
            background: #ffffff;
            padding: 20px;
        }

        .quiz-top {
            align-items: flex-start;
            border-bottom: 1px solid #edf3f0;
            display: flex;
            gap: 14px;
            justify-content: space-between;
            padding-bottom: 16px;
        }

        .quiz-top h1 {
            color: #064e3b;
            font-size: 1.35rem;
            font-weight: 900;
            line-height: 1.25;
        }

        .quiz-meta {
            color: #475569;
            font-size: 0.82rem;
            font-weight: 800;
            margin-top: 6px;
        }

        .quiz-badge {
            border-radius: 999px;
            background: #ecfdf5;
            color: #047857;
            flex: 0 0 auto;
            font-size: 0.72rem;
            font-weight: 900;
            padding: 7px 10px;
        }

        .quiz-form {
            display: grid;
            gap: 16px;
            margin-top: 18px;
        }

        .quiz-question {
            border: 1px solid #edf3f0;
            border-radius: 8px;
            display: grid;
            gap: 10px;
            padding: 14px;
        }

        .quiz-question strong {
            color: #111827;
            font-size: 0.92rem;
            line-height: 1.45;
        }

        .quiz-choice {
            align-items: flex-start;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #334155;
            display: flex;
            gap: 9px;
            font-size: 0.84rem;
            font-weight: 800;
            padding: 10px;
        }

        .quiz-choice input {
            accent-color: #0f5a45;
            margin-top: 2px;
        }

        .quiz-actions {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }

        .quiz-button {
            align-items: center;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            display: inline-flex;
            font-size: 0.84rem;
            font-weight: 900;
            justify-content: center;
            min-height: 42px;
            padding: 0 16px;
            text-decoration: none;
        }

        .quiz-button-primary {
            background: #0f5a45;
            color: #ffffff;
        }

        .quiz-button-secondary {
            background: #f1f5f9;
            color: #334155;
        }

        .quiz-alert {
            border-radius: 8px;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
            font-size: 0.84rem;
            font-weight: 800;
            padding: 12px 14px;
        }

        @media (max-width: 760px) {
            .quiz-top {
                flex-direction: column;
            }

            .quiz-actions {
                justify-content: stretch;
            }

            .quiz-button {
                width: 100%;
            }
        }
    </style>

    <section class="role-dashboard">
        <div class="role-dashboard-wrap">
            <div class="quiz-shell">
                <div class="quiz-top">
                    <div>
                        <h1>{{ $quiz->judul_quiz }}</h1>
                        <p class="quiz-meta">
                            {{ $quiz->mengajar->mataPelajaran->nama_mapel ?? '-' }}
                            | {{ $quiz->durasi }} menit
                            | Mulai {{ $pengerjaan->waktu_mulai?->format('d M Y H:i') }}
                        </p>
                    </div>
                    <span class="quiz-badge">{{ $quiz->soal->count() }} soal</span>
                </div>

                @if ($errors->any())
                    <div class="quiz-workspace">
                        <div class="quiz-alert">{{ $errors->first() }}</div>
                    </div>
                @endif

                <form class="quiz-form" action="{{ route('siswa.quiz.submit', $quiz->id_quiz) }}" method="post">
                    @csrf

                    @foreach ($quiz->soal as $soal)
                        <div class="quiz-question">
                            <strong>{{ $loop->iteration }}. {{ $soal->pertanyaan }}</strong>
                            @foreach ($soal->pilihanJawaban->sortBy('opsi') as $pilihan)
                                <label class="quiz-choice">
                                    <input name="answers[{{ $soal->id_soal }}]" type="radio" value="{{ $pilihan->id_pilihan }}" required>
                                    <span>{{ $pilihan->opsi }}. {{ $pilihan->isi_pilihan }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="quiz-actions">
                        <a class="quiz-button quiz-button-secondary" href="{{ route('dashboard') }}">Kembali</a>
                        <button class="quiz-button quiz-button-primary" type="submit">Kirim Jawaban Quiz</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
