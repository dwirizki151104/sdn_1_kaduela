@extends('layouts.app')

@section('title', 'Login E-Learning - SD Negeri 1 Kaduela')

@section('content')
    <style>
        .auth-page {
            min-height: calc(100vh - 70px);
            background: #11664f;
            color: #ffffff;
            padding: 56px 16px 82px;
        }

        .auth-wrap {
            width: min(100%, 430px);
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
            margin-top: 34px;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 22px 45px rgba(0, 0, 0, 0.2);
            color: #111827;
            padding: 28px;
            text-align: left;
        }

        .auth-form {
            display: grid;
            gap: 14px;
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
    </style>

    <section class="auth-page">
        <div class="auth-wrap">
            <h1 class="auth-title">Login E-Learning</h1>
            <p class="auth-subtitle">Admin, guru, dan siswa login dari halaman yang sama.</p>

            <article class="auth-panel">
                @if ($errors->any())
                    <div class="auth-error">{{ $errors->first() }}</div>
                @endif

                <form class="auth-form" action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="auth-field">
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="username">
                    </div>

                    <div class="auth-field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password">
                    </div>

                    <button class="auth-btn" type="submit">Login</button>
                </form>

                <p class="auth-link">Belum punya akun siswa? <a href="{{ route('elearning.register') }}">Daftar di sini</a></p>
            </article>
        </div>
    </section>
@endsection
