@extends('layouts.app')

@section('title', 'Dashboard Guru - SD Negeri 1 Kaduela')

@section('content')
    @include('dashboard.partials.styles')

    <style>
        .teacher-workspace {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(280px, 0.85fr);
            gap: 16px;
            margin-top: 16px;
        }

        .teacher-form-grid {
            display: grid;
            gap: 14px;
            margin-top: 14px;
        }

        .teacher-form-row {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .teacher-field label {
            display: block;
            color: #475569;
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .teacher-field input,
        .teacher-field select,
        .teacher-field textarea {
            width: 100%;
            min-height: 42px;
            border: 1px solid #d8e7e2;
            border-radius: 8px;
            background: #f8fbfa;
            color: #111827;
            font: inherit;
            font-size: 0.84rem;
            font-weight: 700;
            outline: none;
            padding: 9px 11px;
        }

        .teacher-field textarea {
            min-height: 92px;
            resize: vertical;
        }

        .teacher-field input:focus,
        .teacher-field select:focus,
        .teacher-field textarea:focus {
            border-color: #0f5a45;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 90, 69, 0.1);
        }

        .teacher-file-drop {
            align-items: center;
            background: #f2fbf7;
            border: 1.5px dashed #6ee7b7;
            border-radius: 8px;
            cursor: pointer;
            display: grid;
            min-height: 178px;
            padding: 24px;
            place-items: center;
            text-align: center;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .teacher-file-drop:hover,
        .teacher-file-drop.is-dragging {
            background: #ecfdf5;
            border-color: #0f5a45;
        }

        .teacher-file-input {
            height: 1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            width: 1px;
        }

        .teacher-file-icon {
            align-items: center;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
            color: #047857;
            display: inline-flex;
            height: 62px;
            justify-content: center;
            margin-bottom: 18px;
            width: 62px;
        }

        .teacher-file-title {
            color: #0f172a;
            display: block;
            font-size: 0.98rem;
            font-weight: 900;
        }

        .teacher-file-help {
            color: #334155;
            display: block;
            font-size: 0.78rem;
            font-weight: 800;
            margin-top: 8px;
        }

        .teacher-submit {
            justify-self: end;
            border: 0;
            border-radius: 8px;
            background: #0f5a45;
            color: #ffffff;
            cursor: pointer;
            font-weight: 900;
            min-height: 42px;
            padding: 0 16px;
        }

        .teacher-muted {
            color: #64748b;
            font-size: 0.78rem;
            font-weight: 700;
            margin-top: 4px;
        }

        .teacher-list-item {
            display: grid;
            gap: 5px;
        }

        .teacher-list-meta {
            color: #64748b;
            font-size: 0.74rem;
            font-weight: 800;
        }

        .teacher-list-action {
            color: #0f5a45;
            font-size: 0.78rem;
            font-weight: 900;
            text-decoration: none;
        }

        .teacher-panel-head {
            align-items: center;
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }

        .teacher-panel-link {
            align-items: center;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            color: #0f5a45;
            display: inline-flex;
            font-size: 0.74rem;
            font-weight: 900;
            min-height: 34px;
            padding: 0 10px;
            text-decoration: none;
            white-space: nowrap;
        }

        .teacher-panel-link:hover {
            background: #ecfdf5;
        }

        .teacher-alert {
            border-radius: 8px;
            font-size: 0.84rem;
            font-weight: 800;
            margin-bottom: 16px;
            padding: 12px 14px;
        }

        .teacher-alert-success {
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #047857;
        }

        .teacher-alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
        }

        @media (max-width: 900px) {
            .teacher-workspace,
            .teacher-form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

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

            @if (session('success'))
                <div class="teacher-alert teacher-alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="teacher-alert teacher-alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="teacher-workspace">
                <div>
                    <article class="role-dashboard-panel">
                        <h2>Berikan Materi</h2>
                        <form class="teacher-form-grid" action="{{ route('guru.materi.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="teacher-field">
                                <label for="materi-id-mengajar">Kelas dan Mata Pelajaran</label>
                                <select id="materi-id-mengajar" name="id_mengajar" required>
                                    <option value="">Pilih jadwal mengajar</option>
                                    @foreach ($guru?->mengajar ?? [] as $mengajar)
                                        <option value="{{ $mengajar->id_mengajar }}" @selected(old('id_mengajar') == $mengajar->id_mengajar)>
                                            {{ $mengajar->kelas->nama_kelas }} - {{ $mengajar->mataPelajaran->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="teacher-form-row">
                                <div class="teacher-field">
                                    <label for="judul-modul">Judul Materi</label>
                                    <input id="judul-modul" name="judul_modul" type="text" value="{{ old('judul_modul') }}" required>
                                </div>
                            </div>
                            <div class="teacher-field">
                                <label for="file-modul">File Modul</label>
                                <label class="teacher-file-drop" data-file-drop for="file-modul">
                                    <input class="teacher-file-input" id="file-modul" name="file_modul" type="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.jpg,.jpeg,.png" data-file-input required>
                                    <span>
                                        <span class="teacher-file-icon" aria-hidden="true">
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <path d="m17 8-5-5-5 5" />
                                                <path d="M12 3v12" />
                                            </svg>
                                        </span>
                                        <span class="teacher-file-title" data-file-name>Seret file ke sini atau klik untuk memilih</span>
                                        <span class="teacher-file-help">Format: PDF, DOC, DOCX, PPT, PPTX, ZIP, JPG, PNG. Maksimal 10 MB.</span>
                                    </span>
                                </label>
                            </div>
                            <button class="teacher-submit" type="submit">Simpan Materi</button>
                        </form>
                    </article>

                    <article class="role-dashboard-panel">
                        <h2>Berikan Tugas</h2>
                        <form class="teacher-form-grid" action="{{ route('guru.tugas.store') }}" method="post">
                            @csrf
                            <div class="teacher-form-row">
                                <div class="teacher-field">
                                    <label for="tugas-id-mengajar">Kelas dan Mata Pelajaran</label>
                                    <select id="tugas-id-mengajar" name="id_mengajar" required>
                                        <option value="">Pilih jadwal mengajar</option>
                                        @foreach ($guru?->mengajar ?? [] as $mengajar)
                                            <option value="{{ $mengajar->id_mengajar }}" @selected(old('id_mengajar') == $mengajar->id_mengajar)>
                                                {{ $mengajar->kelas->nama_kelas }} - {{ $mengajar->mataPelajaran->nama_mapel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="teacher-field">
                                    <label for="deadline">Deadline</label>
                                    <input id="deadline" name="deadline" type="datetime-local" value="{{ old('deadline') }}" required>
                                </div>
                            </div>
                            <div class="teacher-field">
                                <label for="judul-tugas">Judul Tugas</label>
                                <input id="judul-tugas" name="judul_tugas" type="text" value="{{ old('judul_tugas') }}" required>
                            </div>
                            <div class="teacher-field">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi">{{ old('deskripsi') }}</textarea>
                            </div>
                            <button class="teacher-submit" type="submit">Simpan Tugas</button>
                        </form>
                    </article>

                    <article class="role-dashboard-panel">
                        <h2>Buat Quiz atau Ulangan</h2>
                        <form class="teacher-form-grid" action="{{ route('guru.quiz.store') }}" method="post">
                            @csrf
                            <div class="teacher-field">
                                <label for="quiz-id-mengajar">Kelas dan Mata Pelajaran</label>
                                <select id="quiz-id-mengajar" name="id_mengajar" required>
                                    <option value="">Pilih jadwal mengajar</option>
                                    @foreach ($guru?->mengajar ?? [] as $mengajar)
                                        <option value="{{ $mengajar->id_mengajar }}" @selected(old('id_mengajar') == $mengajar->id_mengajar)>
                                            {{ $mengajar->kelas->nama_kelas }} - {{ $mengajar->mataPelajaran->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="teacher-form-row">
                                <div class="teacher-field">
                                    <label for="judul-quiz">Judul Quiz/Ulangan</label>
                                    <input id="judul-quiz" name="judul_quiz" type="text" value="{{ old('judul_quiz') }}" required>
                                </div>
                                <div class="teacher-field">
                                    <label for="durasi">Durasi Menit</label>
                                    <input id="durasi" name="durasi" type="number" min="1" max="600" value="{{ old('durasi', 30) }}" required>
                                </div>
                            </div>
                            <div class="teacher-form-row">
                                <div class="teacher-field">
                                    <label for="tanggal-mulai">Mulai</label>
                                    <input id="tanggal-mulai" name="tanggal_mulai" type="datetime-local" value="{{ old('tanggal_mulai') }}" required>
                                </div>
                                <div class="teacher-field">
                                    <label for="tanggal-selesai">Selesai</label>
                                    <input id="tanggal-selesai" name="tanggal_selesai" type="datetime-local" value="{{ old('tanggal_selesai') }}" required>
                                </div>
                            </div>
                            <div class="teacher-field">
                                <label for="status">Status</label>
                                <select id="status" name="status" required>
                                    <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                                    <option value="aktif" @selected(old('status') === 'aktif')>Aktif</option>
                                </select>
                            </div>
                            <button class="teacher-submit" type="submit">Simpan Quiz</button>
                        </form>
                    </article>
                </div>

                <aside>
                    <article class="role-dashboard-panel">
                        <div class="teacher-panel-head">
                            <h2>Data Mengajar</h2>
                            <a class="teacher-panel-link" href="{{ route('admin.master.index', 'nilai') }}">Nilai Siswa</a>
                        </div>
                        <ul class="role-dashboard-list">
                            @forelse ($guru?->mengajar ?? [] as $mengajar)
                                <li>{{ $mengajar->mataPelajaran->nama_mapel }} - {{ $mengajar->kelas->nama_kelas }}</li>
                            @empty
                                <li>Belum ada data mengajar.</li>
                            @endforelse
                        </ul>
                    </article>

                    <article class="role-dashboard-panel">
                        <div class="teacher-panel-head">
                            <h2>Materi Terbaru</h2>
                            <a class="teacher-panel-link" href="{{ route('admin.master.index', 'modul') }}">Lihat Materi</a>
                        </div>
                        <ul class="role-dashboard-list">
                            @forelse ($modulTerbaru as $modul)
                                <li class="teacher-list-item">
                                    <strong>{{ $modul->judul_modul }}</strong>
                                    <span class="teacher-list-meta">{{ $modul->mengajar->kelas->nama_kelas }} - {{ $modul->mengajar->mataPelajaran->nama_mapel }}</span>
                                    <span class="teacher-list-meta">{{ $modul->tanggal_upload?->format('d M Y H:i') }}</span>
                                    <a class="teacher-list-action" href="{{ asset('storage/' . $modul->file_modul) }}" target="_blank">Lihat file</a>
                                    <a class="teacher-list-action" href="{{ route('admin.master.index', 'modul') }}">Kelola materi</a>
                                </li>
                            @empty
                                <li>Belum ada materi.</li>
                            @endforelse
                        </ul>
                    </article>

                    <article class="role-dashboard-panel">
                        <div class="teacher-panel-head">
                            <h2>Tugas Terbaru</h2>
                            <a class="teacher-panel-link" href="{{ route('admin.master.index', 'pengumpulan-tugas') }}">Lihat Pengumpulan</a>
                        </div>
                        <ul class="role-dashboard-list">
                            @forelse ($tugasTerbaru as $tugas)
                                <li class="teacher-list-item">
                                    <strong>{{ $tugas->judul_tugas }}</strong>
                                    <span class="teacher-list-meta">{{ $tugas->mengajar->kelas->nama_kelas }} - {{ $tugas->mengajar->mataPelajaran->nama_mapel }}</span>
                                    <span class="teacher-list-meta">Deadline {{ $tugas->deadline?->format('d M Y H:i') }}</span>
                                    <a class="teacher-list-action" href="{{ route('admin.master.index', 'pengumpulan-tugas') }}">Lihat pengumpulan siswa</a>
                                </li>
                            @empty
                                <li>Belum ada tugas.</li>
                            @endforelse
                        </ul>
                    </article>

                    <article class="role-dashboard-panel">
                        <div class="teacher-panel-head">
                            <h2>Quiz/Ulangan Terbaru</h2>
                            <a class="teacher-panel-link" href="{{ route('admin.master.index', 'nilai-quiz') }}">Lihat Nilai</a>
                        </div>
                        <ul class="role-dashboard-list">
                            @forelse ($quizTerbaru as $quiz)
                                <li class="teacher-list-item">
                                    <strong>{{ $quiz->judul_quiz }}</strong>
                                    <span class="teacher-list-meta">{{ $quiz->mengajar->kelas->nama_kelas }} - {{ $quiz->mengajar->mataPelajaran->nama_mapel }}</span>
                                    <span class="teacher-list-meta">{{ ucfirst($quiz->status) }} | {{ $quiz->durasi }} menit | {{ $quiz->soal->count() }} soal</span>
                                    <a class="teacher-list-action" href="{{ route('admin.quiz.questions.index', $quiz->id_quiz) }}">Kelola soal</a>
                                    <a class="teacher-list-action" href="{{ route('admin.master.index', 'nilai-quiz') }}">Lihat nilai siswa</a>
                                </li>
                            @empty
                                <li>Belum ada quiz atau ulangan.</li>
                            @endforelse
                        </ul>
                    </article>
                </aside>
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
