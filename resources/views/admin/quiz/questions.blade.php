@php
    $usesAdminShell = auth()->user()?->role === 'admin';
@endphp

@extends($usesAdminShell ? 'layouts.admin' : 'layouts.app')

@section('title', 'Kelola Soal Quiz - SD Negeri 1 Kaduela')

@section('content')
    @unless ($usesAdminShell)
        @include('dashboard.partials.styles')
    @endunless

    @if ($usesAdminShell)
        @component('admin.partials.shell')
    @else
        <section class="role-dashboard">
            <div class="role-dashboard-wrap">
    @endif
        <div class="mb-5 flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-teal-700">Quiz Online</p>
                <h1 class="mt-1 text-2xl font-black tracking-normal text-slate-950 sm:text-3xl">Kelola Soal: {{ $quiz->judul_quiz }}</h1>
                <p class="mt-1 text-sm font-semibold text-slate-500">
                    {{ $quiz->mengajar->kelas->nama_kelas ?? '-' }} | {{ $quiz->mengajar->mataPelajaran->nama_mapel ?? '-' }} | {{ $quiz->durasi }} menit
                </p>
            </div>

            <a class="inline-flex min-h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-black text-slate-700 shadow-sm transition hover:bg-slate-50" href="{{ $usesAdminShell ? route('admin.master.index', 'quiz') : route('dashboard') }}">
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5">
            <div class="mb-4">
                <h2 class="text-base font-black text-slate-950">Tambah Soal Baru</h2>
                <p class="mt-1 text-xs font-bold text-slate-500">Isi pertanyaan, empat pilihan jawaban, lalu tandai satu jawaban benar.</p>
            </div>

            <form action="{{ route('admin.quiz.questions.store', $quiz->id_quiz) }}" method="post">
                @csrf
                <div class="grid gap-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-black uppercase tracking-wide text-slate-600" for="pertanyaan">Pertanyaan</label>
                        <textarea class="min-h-28 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100" id="pertanyaan" name="pertanyaan">{{ old('pertanyaan') }}</textarea>
                    </div>

                    <div class="grid gap-3 md:grid-cols-[180px_minmax(0,1fr)]">
                        <div>
                            <label class="mb-1.5 block text-xs font-black uppercase tracking-wide text-slate-600" for="bobot">Bobot</label>
                            <input class="min-h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100" id="bobot" name="bobot" type="number" min="0.01" max="100" step="0.01" value="{{ old('bobot', 1) }}">
                        </div>
                    </div>

                    <div class="grid gap-3 md:grid-cols-2">
                        @foreach (['A', 'B', 'C', 'D'] as $option)
                            <label class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                <span class="mb-2 flex items-center justify-between gap-3">
                                    <span class="text-xs font-black uppercase tracking-wide text-slate-600">Pilihan {{ $option }}</span>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-black text-emerald-700">
                                        <input class="size-4 accent-emerald-700" name="correct_answer" type="radio" value="{{ $option }}" @checked(old('correct_answer') === $option)>
                                        Benar
                                    </span>
                                </span>
                                <input class="min-h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" name="choices[{{ $option }}]" type="text" value="{{ old("choices.$option") }}">
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5 flex justify-end border-t border-slate-100 pt-5">
                    <button class="inline-flex min-h-11 items-center rounded-lg bg-[#0f5a45] px-5 text-sm font-black text-white shadow-lg shadow-emerald-900/10 transition hover:bg-[#103f35]" type="submit">
                        Simpan Soal
                    </button>
                </div>
            </form>
        </section>

        <section class="mt-5 grid gap-4">
            @forelse ($quiz->soal as $question)
                @php
                    $choices = $question->pilihanJawaban->keyBy('opsi');
                    $correct = $question->pilihanJawaban->firstWhere('is_benar', true)?->opsi;
                @endphp

                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5">
                    <form action="{{ route('admin.quiz.questions.update', [$quiz->id_quiz, $question->id_soal]) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-base font-black text-slate-950">Soal #{{ $loop->iteration }}</h3>
                                <p class="mt-1 text-xs font-bold text-slate-500">Bobot saat ini: {{ $question->bobot }}</p>
                            </div>
                            <button class="inline-flex min-h-10 items-center justify-center rounded-lg border border-blue-200 px-4 text-xs font-black text-blue-700 transition hover:bg-blue-50" type="submit">
                                Update Soal
                            </button>
                        </div>

                        <div class="grid gap-4">
                            <div>
                                <label class="mb-1.5 block text-xs font-black uppercase tracking-wide text-slate-600" for="pertanyaan-{{ $question->id_soal }}">Pertanyaan</label>
                                <textarea class="min-h-24 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100" id="pertanyaan-{{ $question->id_soal }}" name="pertanyaan">{{ old('pertanyaan', $question->pertanyaan) }}</textarea>
                            </div>

                            <div class="max-w-[180px]">
                                <label class="mb-1.5 block text-xs font-black uppercase tracking-wide text-slate-600" for="bobot-{{ $question->id_soal }}">Bobot</label>
                                <input class="min-h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100" id="bobot-{{ $question->id_soal }}" name="bobot" type="number" min="0.01" max="100" step="0.01" value="{{ old('bobot', $question->bobot) }}">
                            </div>

                            <div class="grid gap-3 md:grid-cols-2">
                                @foreach (['A', 'B', 'C', 'D'] as $option)
                                    <label class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <span class="mb-2 flex items-center justify-between gap-3">
                                            <span class="text-xs font-black uppercase tracking-wide text-slate-600">Pilihan {{ $option }}</span>
                                            <span class="inline-flex items-center gap-1.5 text-xs font-black text-emerald-700">
                                                <input class="size-4 accent-emerald-700" name="correct_answer" type="radio" value="{{ $option }}" @checked($correct === $option)>
                                                Benar
                                            </span>
                                        </span>
                                        <input class="min-h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" name="choices[{{ $option }}]" type="text" value="{{ old("choices.$option", $choices[$option]->isi_pilihan ?? '') }}">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </form>

                    <form class="mt-4 border-t border-slate-100 pt-4 text-right" action="{{ route('admin.quiz.questions.destroy', [$quiz->id_quiz, $question->id_soal]) }}" method="post" onsubmit="return confirm('Hapus soal ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="inline-flex min-h-10 items-center justify-center rounded-lg border border-rose-200 px-4 text-xs font-black text-rose-700 transition hover:bg-rose-50" type="submit">
                            Hapus Soal
                        </button>
                    </form>
                </article>
            @empty
                <div class="rounded-lg border border-slate-200 bg-white px-4 py-8 text-center text-sm font-semibold text-slate-500 shadow-xl shadow-slate-900/5">
                    Belum ada soal untuk quiz ini.
                </div>
            @endforelse
        </section>

    @if ($usesAdminShell)
        @endcomponent
    @else
            </div>
        </section>
    @endif
@endsection
