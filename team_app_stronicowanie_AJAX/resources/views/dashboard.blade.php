@extends('layout')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-blue-900 mb-6">
            Witaj, {{ auth()->user()->imie }}! 👋
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- ZAWODNIK -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-3">🏃 Treningi</h2>
                <p class="text-gray-500 text-sm">Przeglądaj nadchodzące treningi i zgłaszaj obecność.</p>
                <a href="/events" class="mt-4 inline-block bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                    Zobacz treningi
                </a>
            </div>

            <!-- TRENER -->
            @if(auth()->user()->hasRole('coach'))
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">📋 Panel trenera</h2>
                    <p class="text-gray-500 text-sm">Zarządzaj treningami i sprawdzaj listę zawodników.</p>
                    <a href="/coach/events" class="mt-4 inline-block bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition text-sm">
                        Zarządzaj
                    </a>
                </div>
            @endif

            <!-- ADMIN -->
            @if(auth()->user()->hasRole('admin'))
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">⚙️ Panel admina</h2>
                    <p class="text-gray-500 text-sm">Zarządzaj użytkownikami i rolami w systemie.</p>
                    <a href="/admin/users" class="mt-4 inline-block bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition text-sm">
                        Zarządzaj
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
