@extends('layouts.app')

@section('title', 'Registrasi Siswa - SD Negeri 1 Kaduela')

@section('content')
    <style>
        .auth-page {
            min-height: calc(100vh - 70px);
            background: #11664f;
            color: #ffffff;
            padding: 50px 16px 78px;
        }

        .auth-wrap {
            width: min(100%, 520px);
            margin: 0 auto;
            text-align: center;
        }

        .auth-title {
            font-size: clamp(1.75rem, 5vw, 2.35rem);
            font-weight: 900;
        }

        .auth-subtitle {
            margin-top: 8px;
            color: rgba(255, 255, 255, 0.88);
            font-size: 0.84rem;
        }

        .auth-panel {
            margin-top: 30px;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 22px 45px rgba(0, 0, 0, 0.2);
            color: #111827;
            padding: 28px;
            text-align: left;
        }

        .auth-form,
        .auth-grid {
            display: grid;
            gap: 14px;
        }

        .auth-grid {
            grid-template-columns: 1fr 1fr;
        }

        .auth-field {
            display: grid;
            gap: 7px;
        }

        .auth-field label {
            font-size: 0.78rem;
            font-weight: 900;
        }

        .auth-field input,
        .auth-field select {
            min-height: 43px;
            width: 100%;
            border: 1px solid #d2ded9;
            border-radius: 8px;
            background: #f8fbfa;
            padding: 10px 12px;
            outline: none;
        }

        .auth-btn {
            min-height: 43px;
            border: 0;
            border-radius: 8px;
            background: #11664f;
            color: #ffffff;
            font-weight: 900;
            cursor: pointer;
        }

        .auth-link {
            margin-top: 18px;
            text-align: center;
            font-size: 0.78rem;
        }

        .auth-link a {
            color: #11664f;
            font-weight: 900;
            text-decoration: none;
        }

        .auth-error {
            margin-bottom: 14px;
            border: 1px solid #fecaca;
            border-radius: 8px;
            background: #fef2f2;
            color: #991b1b;
            padding: 10px 12px;
            font-size: 0.76rem;
            font-weight: 700;
        }

        @media (max-width: 560px) {
            .auth-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="auth-page">
        <div class="auth-wrap">
            <h1 class="auth-title">Registrasi Siswa</h1>
            <p class="auth-subtitle">Akun siswa akan otomatis masuk ke dashboard siswa.</p>

            <article class="auth-panel">
                @if ($errors->any())
                    <div class="auth-error">{{ $errors->first() }}</div>
                @endif

                <form class="auth-form" action="{{ route('register') }}" method="post">
                    @csrf
                    <div class="auth-grid">
                        <div class="auth-field">
                            <label for="nama_siswa">Nama Siswa</label>
                            <input id="nama_siswa" name="nama_siswa" type="text" value="{{ old('nama_siswa') }}" required>
                        </div>

                        <div class="auth-field">
                            <label for="username">Username</label>
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required autocomplete="username">
                        </div>
                    </div>

                    <div class="auth-grid">
                        <div class="auth-field">
                            <label for="nis">NIS</label>
                            <input id="nis" name="nis" type="text" value="{{ old('nis') }}" required>
                        </div>

                        <div class="auth-field">
                            <label for="id_kelas">Kelas</label>
                            <select id="id_kelas" name="id_kelas" required>
                                <option value="" selected disabled>Pilih kelas</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id_kelas }}" @selected(old('id_kelas') == $kelas->id_kelas)>{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="auth-grid">
                        <div class="auth-field">
                            <label for="jk">Jenis Kelamin</label>
                            <select id="jk" name="jk" required>
                                <option value="" selected disabled>Pilih</option>
                                <option value="L" @selected(old('jk') === 'L')>Laki-laki</option>
                                <option value="P" @selected(old('jk') === 'P')>Perempuan</option>
                            </select>
                        </div>

                        <div class="auth-field">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir') }}">
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="alamat">Alamat</label>
                        <input id="alamat" name="alamat" type="text" value="{{ old('alamat') }}">
                    </div>

                    <div class="auth-field">
                        <label for="no_hp">No WhatsApp</label>
                        <input id="no_hp" name="no_hp" type="text" value="{{ old('no_hp') }}" placeholder="081234567890">
                    </div>

                    <div class="auth-grid">
                        <div class="auth-field">
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" required autocomplete="new-password">
                        </div>

                        <div class="auth-field">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
                        </div>
                    </div>

                    <button class="auth-btn" type="submit">Daftar</button>
                </form>

                <p class="auth-link">Sudah punya akun? <a href="{{ route('elearning.login') }}">Login di sini</a></p>
            </article>
        </div>
    </section>
@endsection
