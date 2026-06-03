@extends('layouts.app')

@section('title', 'Login Siswa - SD Negeri 1 Kaduela')
@section('meta_description', 'Login siswa untuk mengakses E-Learning SD Negeri 1 Kaduela.')

@section('content')
    <style>
        .student-login-page {
            min-height: calc(100vh - 70px);
            background: #11664f;
            color: #ffffff;
            padding: 58px 16px 90px;
        }

        .student-login-wrap {
            width: 100%;
            max-width: 430px;
            margin: 0 auto;
            text-align: center;
        }

        .student-login-title {
            font-size: clamp(1.75rem, 5vw, 2.3rem);
            font-weight: 900;
            line-height: 1.08;
        }

        .student-login-subtitle {
            margin-top: 10px;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.88);
        }

        .student-login-panel {
            margin-top: 42px;
            border-radius: 16px;
            background: #ffffff;
            padding: 28px 28px 30px;
            color: #111827;
            text-align: left;
            box-shadow: 0 20px 42px rgba(0, 0, 0, 0.2);
        }

        .student-login-form {
            display: grid;
            gap: 13px;
        }

        .student-field {
            display: grid;
            gap: 7px;
        }

        .student-field label {
            font-size: 0.78rem;
            font-weight: 900;
            color: #111827;
        }

        .student-field input {
            min-height: 42px;
            width: 100%;
            border: 1px solid #b8c0bd;
            border-radius: 8px;
            background: #ffffff;
            padding: 9px 11px;
            color: #111827;
            font-size: 0.86rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .student-field input:focus {
            border-color: #11664f;
            box-shadow: 0 0 0 3px rgba(17, 102, 79, 0.14);
        }

        .student-login-btn {
            display: flex;
            min-height: 42px;
            width: 100%;
            align-items: center;
            justify-content: center;
            margin-top: 12px;
            border: 0;
            border-radius: 8px;
            background: #11664f;
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 900;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .student-login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.14);
            filter: brightness(1.04);
        }

        .student-register-link {
            margin-top: 24px;
            text-align: center;
            font-size: 0.75rem;
            color: #111827;
        }

        .student-register-link a {
            color: #111827;
            font-weight: 900;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .student-login-page {
                padding-top: 44px;
            }

            .student-login-panel {
                margin-top: 34px;
                padding: 24px 18px 28px;
            }
        }
    </style>

    <section class="student-login-page">
        <div class="student-login-wrap">
            <h1 class="student-login-title">Login Siswa</h1>
            <p class="student-login-subtitle">E-Learning SD Negeri 1 Kaduela</p>

            <article class="student-login-panel">
                <form class="student-login-form" action="#" method="get">
                    <div class="student-field">
                        <label for="nama">Nama Lengkap *</label>
                        <input id="nama" name="nama" type="text" required autocomplete="name">
                    </div>

                    <div class="student-field">
                        <label for="password">Password *</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password">
                    </div>

                    <button class="student-login-btn" type="submit">Login</button>
                </form>

                <p class="student-register-link">
                    Belum punya akun? <a href="{{ route('elearning.register') }}">Daftar di sini</a>
                </p>
            </article>
        </div>
    </section>
@endsection
