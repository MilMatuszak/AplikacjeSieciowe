@extends('layout')

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="/admin/users" class="text-blue-600 hover:underline text-sm">← Wróć do listy</a>

        <div class="bg-white rounded-xl shadow p-8 mt-4">
            <h1 class="text-2xl font-bold text-blue-900 mb-2">⚙️ Edytuj użytkownika</h1>
            <p class="text-gray-500 mb-6">{{ $user->imie }} {{ $user->nazwisko }} ({{ $user->login }})</p>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- EDYCJA DANYCH -->
            <div class="border rounded-xl p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">👤 Edytuj dane użytkownika</h2>
                <form method="POST" action="/admin/users/{{ $user->id }}/data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Imię</label>
                        <input
                            type="text"
                            name="imie"
                            value="{{ old('imie', $user->imie) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Nazwisko</label>
                        <input
                            type="text"
                            name="nazwisko"
                            value="{{ old('nazwisko', $user->nazwisko) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Login</label>
                        <input
                            type="text"
                            name="login"
                            value="{{ old('login', $user->login) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">
                        Zapisz dane
                    </button>
                </form>
            </div>

            <!-- ZMIANA ROLI -->
            <div class="border rounded-xl p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">🎭 Zmień role</h2>
                <form method="POST" action="/admin/users/{{ $user->id }}/role">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-3">Wybierz role użytkownika</label>
                        <div class="space-y-2">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="role[]"
                                        value="{{ $role->idRola }}"
                                        {{ in_array($role->idRola, $currentRoles) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-900 rounded"
                                    >
                                    <span class="text-gray-700">
                        <strong>{{ ucfirst($role->nazwa) }}</strong>
                        @if($role->opis) — {{ $role->opis }} @endif
                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Zapisz role
                    </button>
                </form>
            </div>

            <!-- RESET HASŁA -->
            <div class="border rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">🔑 Resetuj hasło</h2>
                <form method="POST" action="/admin/users/{{ $user->id }}/password">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Nowe hasło</label>
                        <input
                            type="password"
                            name="password"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Minimum 8 znaków"
                        >
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Powtórz hasło</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Powtórz hasło"
                        >
                    </div>
                    <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition">
                        Resetuj hasło
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
