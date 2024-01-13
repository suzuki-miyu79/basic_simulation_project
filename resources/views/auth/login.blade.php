@extends('layouts.guest')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <div class="login__content">
        <div class="login__heading">
            <h2>ログイン</h2>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="login-form__input">
                <input id="email" class="block mt-1 w-full" type="email" name="email" placeholder="メールアドレス"
                    :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="login-form__input">
                <input id="password" class="block mt-1 w-full" placeholder="パスワード" type="password" name="password" required
                    autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="login-form__button">
                <button class="login-form__button-submit">ログイン</button>
            </div>
        </form>
        <p class="none-account">アカウントをお持ちでない方はこちらから</p>
        <a href="/register" class="register__link">会員登録</a>
    </div>
@endsection
