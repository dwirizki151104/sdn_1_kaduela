@extends('layouts.app')

@section('title', 'Registrasi Siswa - SD Negeri 1 Kaduela')
@section('meta_description', 'Registrasi akun siswa untuk mengakses E-Learning SD Negeri 1 Kaduela.')

@section('content')
    <style>
        .register-page {
            min-height: calc(100vh - 70px);
            background:
                radial-gradient(circle at 20% 10%, rgba(245, 180, 52, 0.2), transparent 25rem),
                radial-gradient(circle at 85% 0%, rgba(255, 255, 255, 0.12), transparent 18rem),
                linear-gradient(180deg, #0f5a45 0%, #11664f 58%, #0d4f3d 100%);
            color: #ffffff;
            padding: 52px 16px 76px;
        }

        .register-wrap {
            width: 100%;
            max-width: 430px;
            margin: 0 auto;
            text-align: center;
        }

        .register-title {
            font-size: clamp(1.75rem, 5vw, 2.3rem);
            font-weight: 900;
            line-height: 1.08;
        }

        .register-subtitle {
            margin-top: 10px;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.86);
        }

        .register-panel {
            margin-top: 34px;
            border-radius: 18px;
            background: #ffffff;
            padding: 30px 26px 24px;
            color: #111827;
            text-align: left;
            box-shadow: 0 22px 45px rgba(0, 0, 0, 0.22);
        }

        .register-form {
            display: grid;
            gap: 14px;
        }

        .register-field {
            display: grid;
            gap: 7px;
        }

        .register-field label {
            font-size: 0.78rem;
            font-weight: 900;
            color: #111827;
        }

        .register-field input,
        .register-field select {
            min-height: 44px;
            width: 100%;
            border: 1px solid #d2ded9;
            border-radius: 10px;
            background: #f8fbfa;
            padding: 10px 13px;
            color: #111827;
            font-size: 0.83rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .register-field input:focus,
        .register-field select:focus {
            border-color: #11664f;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(17, 102, 79, 0.14);
        }

        .register-help {
            margin-top: -4px;
            font-size: 0.68rem;
            color: #4b5563;
        }

        .password-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .register-note {
            border: 1px solid #d8e7e2;
            border-radius: 10px;
            background: #f2f8f5;
            padding: 12px 13px;
            color: #111827;
            box-shadow: inset 0 -2px 0 rgba(15, 90, 69, 0.06);
        }

        .register-note strong,
        .register-note span {
            display: block;
            font-size: 0.72rem;
            line-height: 1.28;
        }

        .register-btn {
            display: flex;
            min-height: 44px;
            width: 100%;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, #0f5a45, #177458);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 900;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.14);
            filter: brightness(1.04);
        }

        .login-link {
            margin-top: 18px;
            text-align: center;
            font-size: 0.75rem;
            color: #64748b;
        }

        .login-link a {
            color: #11664f;
            font-weight: 900;
            text-decoration: none;
        }

        .back-login {
            display: inline-flex;
            margin-top: 26px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
        }

        .back-login:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .register-page {
                padding-top: 44px;
            }

            .register-panel {
                margin-top: 34px;
                padding: 24px 18px 22px;
            }

            .password-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="register-page">
        <div class="register-wrap">
            <h1 class="register-title">Registrasi Siswa</h1>
            <p class="register-subtitle">Daftar untuk mengakses E-Learning SD Negeri 1 Kaduela</p>

            <article class="register-panel">
                <form class="register-form" action="{{ route('elearning.login') }}" method="get">
                    <div class="register-field">
                        <label for="nama">Nama Lengkap *</label>
                        <input id="nama" name="nama" type="text" required>
                    </div>

                    <div class="register-field">
                        <label for="kelas">Kelas *</label>
                        <select id="kelas" name="kelas" required>
                            <option value="" selected disabled>Pilih kelas</option>
                            <option value="1">Kelas 1</option>
                            <option value="2">Kelas 2</option>
                            <option value="3">Kelas 3</option>
                            <option value="4">Kelas 4</option>
                            <option value="5">Kelas 5</option>
                            <option value="6">Kelas 6</option>
                        </select>
                    </div>

                    <div class="register-field">
                        <label for="whatsapp">No. WhatsApp Orang Tua *</label>
                        <input id="whatsapp" name="whatsapp" type="tel" required>
                    </div>

                    <p class="register-help">Untuk notifikasi hasil ujian kelas 6</p>

                    <div class="password-grid">
                        <div class="register-field">
                            <label for="password">Password *</label>
                            <input id="password" name="password" type="password" required>
                        </div>

                        <div class="register-field">
                            <label for="password_confirmation">Konfirmasi Password *</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required>
                        </div>
                    </div>

                    <div class="register-note">
                        <strong>Catatan:</strong>
                        <span>Pastikan semua data yang diisi sudah benar.</span>
                        <span>Password akan digunakan untuk login ke sistem e-learning.</span>
                    </div>

                    <button class="register-btn" type="submit">Daftar Sekarang</button>
                </form>

                <p class="login-link">Sudah punya akun? <a href="{{ route('elearning.login') }}">Login di sini</a></p>
            </article>

            <a class="back-login" href="{{ route('elearning.login') }}">Kembali Ke Pilihan Login</a>
        </div>
    </section>
@endsection
