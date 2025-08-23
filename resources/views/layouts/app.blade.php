<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="app-container">
        <header style="padding:16px;border-bottom:1px solid #eee">
            <div style="max-width:1100px;margin:0 auto;display:flex;justify-content:space-between;align-items:center"> 
                <a href="/" style="text-decoration:none;color:inherit"><strong>{{ config('app.name') }}</strong></a>
                <nav>
                    <a href="/admin" style="margin-left:12px;text-decoration:none;color:#2b6cb0">Admin</a>
                </nav>
            </div>
        </header>

        <main style="max-width:1100px;margin:24px auto;padding:0 16px">
            @yield('content')
        </main>

        <footer style="max-width:1100px;margin:48px auto 24px;padding:0 16px;color:#666;font-size:13px">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </footer>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
