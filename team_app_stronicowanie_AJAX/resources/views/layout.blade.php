<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Team Manager' }}</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- NAWIGACJA -->
<nav class="bg-blue-900 text-white px-6 py-4 flex justify-between items-center">
    <a href="/" class="text-xl font-bold">⚽ Team Manager</a>
    <div class="flex gap-4">
        @auth
            @if(auth()->user()->hasRole('player'))
                <a href="/events" class="hover:text-blue-300">Treningi</a>
                <a href="/my-stats" class="hover:text-blue-300">Moje statystyki</a>
            @endif

            @if(auth()->user()->hasRole('coach'))
                <a href="/coach/events" class="hover:text-blue-300">Moje treningi</a>
                <a href="/coach/players" class="hover:text-blue-300">Zawodnicy</a>
            @endif

            @if(auth()->user()->hasRole('admin'))
                <a href="/admin/users" class="hover:text-blue-300">Użytkownicy</a>
            @endif

            <form method="POST" action="/logout" class="inline">
                @csrf
                <button type="submit" class="hover:text-blue-300">Wyloguj</button>
            </form>
        @else
            <a href="/login" class="hover:text-blue-300">Logowanie</a>
            <a href="/register" class="hover:text-blue-300">Rejestracja</a>
        @endauth
    </div>
</nav>

<!-- KOMUNIKATY -->
@if(session('success'))
    <div class="bg-green-100 text-green-800 px-6 py-3 border-l-4 border-green-500">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-800 px-6 py-3 border-l-4 border-red-500">
        {{ session('error') }}
    </div>
@endif

<!-- TREŚĆ STRONY -->
<main class="container mx-auto px-6 py-8">
    @yield('content')
</main>

</body>
</html>

<!-- STOPKA -->
<footer class="bg-blue-900 text-white mt-12 py-6">
    <div class="container mx-auto px-6 text-center">
        <p class="text-sm text-blue-200">
            &copy; {{ date('Y') }} Team Manager — Aplikacja do zarządzania drużyną sportową
        </p>
        <p class="text-xs text-blue-300 mt-1">
            Zbudowana w Laravel {{ app()->version() }} + Blade + Tailwind CSS
        </p>
    </div>
</footer>
