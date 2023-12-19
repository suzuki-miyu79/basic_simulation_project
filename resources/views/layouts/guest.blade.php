<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <h1 class="header__logo">Atte</h1>
    </header>
    <main class="main">
        @yield('content')
    </main>
    <footer class="footer">
        <p class="footer__logo">Atte,inc.</p>
    </footer>
</body>

</html>