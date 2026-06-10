@extends('layouts.app')

@section('title', 'Dashboard Siswa - SD Negeri 1 Kaduela')

@section('content')
    @include('dashboard.partials.styles')

    <style>
        .student-workspace {
            display: grid;
            gap: 16px;
            margin-top: 16px;
        }

        .student-panel-header {
            align-items: flex-start;
            display: flex;
            gap: 12px;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .student-panel-header p {
            margin-top: 4px;
        }

        .student-list {
            display: grid;
            gap: 12px;
        }

        .student-item {
            border: 1px solid #edf3f0;
            border-radius: 8px;
            display: grid;
            gap: 10px;
            padding: 14px;
        }

        .student-item-top {
            align-items: flex-start;
            display: flex;
            gap: 12px;
            justify-content: space-between;
        }

        .student-item h3 {
            color: #111827;
            font-size: 0.95rem;
            font-weight: 900;
            line-height: 1.35;
        }

        .student-meta,
        .student-panel-header p {
            color: #64748b;
            font-size: 0.78rem;
            font-weight: 800;
        }

        .student-badge {
            border-radius: 999px;
            background: #ecfdf5;
            color: #047857;
            flex: 0 0 auto;
            font-size: 0.7rem;
            font-weight: 900;
            padding: 5px 9px;
        }

        .student-badge-muted {
            background: #f1f5f9;
            color: #475569;
        }

        .student-action {
            align-items: center;
            background: #0f5a45;
            border-radius: 8px;
            color: #ffffff;
            display: inline-flex;
            font-size: 0.8rem;
            font-weight: 900;
            justify-content: center;
            min-height: 38px;
            padding: 0 13px;
            text-decoration: none;
        }

        .student-link {
            color: #0f5a45;
            font-weight: 900;
            text-decoration: none;
        }

        .student-submit {
            border: 0;
            cursor: pointer;
        }

        .student-file-drop {
            align-items: center;
            background: #f8fbfa;
            border: 1.5px dashed #6ee7b7;
            border-radius: 8px;
            cursor: pointer;
            display: grid;
            min-height: 118px;
            padding: 18px;
            place-items: center;
            text-align: center;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .student-file-drop:hover,
        .student-file-drop.is-dragging {
            background: #ecfdf5;
            border-color: #0f5a45;
        }

        .student-file-input {
            height: 1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            width: 1px;
        }

        .student-file-title {
            color: #0f172a;
            display: block;
            font-size: 0.86rem;
            font-weight: 900;
        }

        .student-file-help {
            color: #475569;
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            margin-top: 5px;
        }

        .student-alert {
            border-radius: 8px;
            font-size: 0.84rem;
            font-weight: 800;
            margin-bottom: 16px;
            padding: 12px 14px;
        }

        .student-alert-success {
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #047857;
        }

        .student-alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
        }

        .student-quiz-form {
            border-top: 1px solid #edf3f0;
            display: grid;
            gap: 14px;
            padding-top: 12px;
        }

        .student-question {
            display: grid;
            gap: 8px;
        }

        .student-question strong {
            color: #111827;
            font-size: 0.86rem;
        }

        .student-choice {
            align-items: flex-start;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #334155;
            display: flex;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 800;
            padding: 9px;
        }

        .student-choice input {
            accent-color: #0f5a45;
            margin-top: 2px;
        }

        @media (max-width: 760px) {
            .student-item-top,
            .student-panel-header {
                flex-direction: column;
            }
        }
    </style>

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

            @if (session('success'))
                <div class="student-alert student-alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="student-alert student-alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="student-workspace">
                <article class="role-dashboard-panel">
                    <div class="student-panel-header">
                        <div>
                            <h2>Materi Pembelajaran</h2>
                            <p>Materi yang dikirim guru sesuai kelas kamu.</p>
                        </div>
                        <span class="student-badge">{{ $materiList->count() }} materi</span>
                    </div>

                    <div class="student-list">
                        @forelse ($materiList as $modul)
                            <section class="student-item">
                                <div class="student-item-top">
                                    <div>
                                        <h3>{{ $modul->judul_modul }}</h3>
                                        <p class="student-meta">{{ $modul->mengajar->mataPelajaran->nama_mapel }} | {{ $modul->mengajar->guru->nama_guru ?? 'Guru' }} | {{ $modul->tanggal_upload?->format('d M Y H:i') }}</p>
                                    </div>
                                    <a class="student-action" href="{{ asset('storage/' . $modul->file_modul) }}" target="_blank">Buka Materi</a>
                                </div>
                            </section>
                        @empty
                            <p class="student-meta">Belum ada materi untuk kelas kamu.</p>
                        @endforelse
                    </div>
                </article>

                <article class="role-dashboard-panel">
                    <div class="student-panel-header">
                        <div>
                            <h2>Tugas</h2>
                            <p>Kirim jawaban dalam bentuk video, dokumen, gambar, atau arsip.</p>
                        </div>
                        <span class="student-badge">{{ $jumlahTugasDikumpulkan }} terkumpul</span>
                    </div>

                    <div class="student-list">
                        @forelse ($tugasList as $tugas)
                            @php
                                $pengumpulan = $tugas->pengumpulan->first();
                            @endphp

                            <section class="student-item">
                                <div class="student-item-top">
                                    <div>
                                        <h3>{{ $tugas->judul_tugas }}</h3>
                                        <p class="student-meta">{{ $tugas->mengajar->mataPelajaran->nama_mapel }} | Deadline {{ $tugas->deadline?->format('d M Y H:i') }}</p>
                                        @if (filled($tugas->deskripsi))
                                            <p class="student-meta">{{ $tugas->deskripsi }}</p>
                                        @endif
                                    </div>
                                    <span class="student-badge {{ $pengumpulan ? '' : 'student-badge-muted' }}">{{ $pengumpulan ? 'Sudah dikirim' : 'Belum dikirim' }}</span>
                                </div>

                                @if ($pengumpulan)
                                    <p class="student-meta">
                                        Dikirim {{ $pengumpulan->tanggal_kumpul?->format('d M Y H:i') }}
                                        @if (filled($pengumpulan->nilai))
                                            | Nilai {{ $pengumpulan->nilai }}
                                        @endif
                                        | <a class="student-link" href="{{ asset('storage/' . $pengumpulan->file_jawaban) }}" target="_blank">Lihat jawaban</a>
                                    </p>
                                @endif

                                <form class="student-list" action="{{ route('siswa.tugas.submit', $tugas->id_tugas) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <label class="student-file-drop" data-file-drop for="file-jawaban-{{ $tugas->id_tugas }}">
                                        <input class="student-file-input" id="file-jawaban-{{ $tugas->id_tugas }}" name="file_jawaban" type="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.jpg,.jpeg,.png,.mp4,.mov,.avi,.mkv,.webm" data-file-input required>
                                        <span>
                                            <span class="student-file-title" data-file-name>Seret file ke sini atau klik untuk memilih</span>
                                            <span class="student-file-help">Format dokumen/gambar/video. Maksimal 50 MB.</span>
                                        </span>
                                    </label>
                                    <button class="student-action student-submit" type="submit">{{ $pengumpulan ? 'Kirim Ulang Tugas' : 'Kirim Tugas' }}</button>
                                </form>
                            </section>
                        @empty
                            <p class="student-meta">Belum ada tugas untuk kelas kamu.</p>
                        @endforelse
                    </div>
                </article>

                <article class="role-dashboard-panel">
                    <div class="student-panel-header">
                        <div>
                            <h2>Quiz dan Ulangan</h2>
                            <p>Kerjakan quiz aktif sesuai jadwal yang diberikan guru.</p>
                        </div>
                        <span class="student-badge">{{ $jumlahQuizDikerjakan }} dikerjakan</span>
                    </div>

                    <div class="student-list">
                        @forelse ($quizList as $quiz)
                            @php
                                $pengerjaan = $quiz->pengerjaan->first();
                                $isOpen = $quiz->status === 'aktif' && now()->between($quiz->tanggal_mulai, $quiz->tanggal_selesai);
                            @endphp

                            <section class="student-item">
                                <div class="student-item-top">
                                    <div>
                                        <h3>{{ $quiz->judul_quiz }}</h3>
                                        <p class="student-meta">{{ $quiz->mengajar->mataPelajaran->nama_mapel }} | {{ $quiz->durasi }} menit | {{ $quiz->tanggal_mulai?->format('d M Y H:i') }} - {{ $quiz->tanggal_selesai?->format('d M Y H:i') }}</p>
                                    </div>
                                    <span class="student-badge {{ $pengerjaan?->waktu_selesai ? '' : 'student-badge-muted' }}">
                                        {{ $pengerjaan?->waktu_selesai ? 'Sudah dikerjakan' : ucfirst($quiz->status) }}
                                    </span>
                                </div>

                                @if ($pengerjaan?->waktu_selesai)
                                    <p class="student-meta">Dikerjakan {{ $pengerjaan->waktu_selesai?->format('d M Y H:i') }}.</p>
                                @elseif (! $isOpen)
                                    <p class="student-meta">
                                        @if (now()->lt($quiz->tanggal_mulai))
                                            Quiz mulai pada {{ $quiz->tanggal_mulai?->format('d M Y H:i') }}.
                                        @else
                                            Quiz sudah melewati jadwal.
                                        @endif
                                    </p>
                                @elseif ($quiz->soal->isEmpty())
                                    <p class="student-meta">Quiz belum memiliki soal.</p>
                                @else
                                    <a class="student-action" href="{{ route('siswa.quiz.show', $quiz->id_quiz) }}">Mulai</a>
                                @endif
                            </section>
                        @empty
                            <p class="student-meta">Belum ada quiz atau ulangan untuk kelas kamu.</p>
                        @endforelse
                    </div>
                </article>
            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('[data-file-drop]').forEach((dropZone) => {
            const input = dropZone.querySelector('[data-file-input]');
            const fileName = dropZone.querySelector('[data-file-name]');

            const showFile = () => {
                if (input.files.length > 0) {
                    fileName.textContent = input.files[0].name;
                }
            };

            input.addEventListener('change', showFile);

            ['dragenter', 'dragover'].forEach((eventName) => {
                dropZone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropZone.classList.add('is-dragging');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                dropZone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropZone.classList.remove('is-dragging');
                });
            });

            dropZone.addEventListener('drop', (event) => {
                if (event.dataTransfer.files.length > 0) {
                    input.files = event.dataTransfer.files;
                    showFile();
                }
            });
        });
    </script>
@endsection
