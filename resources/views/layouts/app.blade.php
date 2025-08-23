<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-table.css') }}">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-" crossorigin="anonymous">
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="d-flex flex-column min-vh-100">
        <header class="border-bottom">
            <div class="container d-flex justify-content-between align-items-center py-3">
<a href="/" class="text-decoration-none fw-bold fs-4" style="color: #4f46e5;">
    {{ config('app.name') }}
</a>


                 <nav>
                    <a href="/admin" class="btn btn-primary">Admin</a>
                </nav>
            </div>
        </header>

        <main class="flex-grow-1">
            <div class="container py-4">
                @yield('content')
            </div>
        </main>

        <footer class="mt-auto border-top py-3 text-muted">
            <div class="container">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
        </footer>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
