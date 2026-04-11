@extends('layout')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-8 mt-10">
        <h2 class="text-2xl font-bold text-blue-900 mb-6 text-center">Rejestracja</h2>

        @if($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf

            <!-- IMIĘ -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Imię</label>
                <input
                    type="text"
                    name="imie"
                    value="{{ old('imie') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Wpisz imię"
                >
            </div>

            <!-- NAZWISKO -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Nazwisko</label>
                <input
                    type="text"
                    name="nazwisko"
                    value="{{ old('nazwisko') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Wpisz nazwisko"
                >
            </div>

            <!-- LOGIN -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Login</label>
                <input
                    type="text"
                    name="login"
                    value="{{ old('login') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Wpisz login"
                >
            </div>

            <!-- EMAIL -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Wpisz email"
                >
            </div>

            <!-- HASŁO -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Hasło</label>
                <input
                    type="password"
                    name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Minimum 8 znaków"
                >
            </div>

            <!-- POWTÓRZ HASŁO -->
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1">Powtórz hasło</label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Powtórz hasło"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-900 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold"
            >
                Zarejestruj się
            </button>
        </form>

        <p class="text-center text-gray-500 mt-4">
            Masz już konto?
            <a href="/login" class="text-blue-600 hover:underline">Zaloguj się</a>
        </p>
    </div>
@endsection
