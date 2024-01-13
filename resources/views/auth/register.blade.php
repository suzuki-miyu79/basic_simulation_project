@extends('layouts.guest')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <div class="register__content">
        <div class="register__heading">
            <h2>会員登録</h2>
        </div>
        <form method="POST" action="{{ route('register') }}" class="register-form">
            @csrf
            <div class="register-form__input">
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" placeholder="名前"
                    :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="register-form__input">
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" placeholder="メールアドレス"
                    :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="register-form__input">
                <x-text-input id="password" class="block mt-1 w-full" placeholder="パスワード" type="password" name="password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="register-form__input">
                <x-text-input id="password_confirmation" class="block mt-1 w-full" placeholder="確認用パスワード" type="password"
                    name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <div class="register-form__button">
                <button class="register-form__button-submit">会員登録</button>
            </div>
        </form>
        <p class="has-account">アカウントをお持ちの方はこちらから</p>
        <a href="/login" class="login__link">ログイン</a>
    </div>
@endsection
