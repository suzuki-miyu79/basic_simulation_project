<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    @yield('css')
</head>

<body>
    <header class="header">
        <h1 class="header__logo">Atte</h1>
        <nav class="header__nav">
            <ul class="header__nav-menu">
                <li><a href="/">ホーム</a></li>
                <li><a href="/attendance-list">日付一覧</a></li>
                <li><a href="/users">ユーザーページ</a></li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <li>
                        <a href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </a>
                    </li>
                </form>
            </ul>
        </nav>
    </header>
    <main class="main">
        @yield('content')
    </main>
    <footer class="footer">
        <p class="footer__logo">Atte,inc.</p>
    </footer>
</body>

</html>
