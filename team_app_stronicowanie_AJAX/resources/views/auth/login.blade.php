@extends('layout')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-8 mt-10">
        <h2 class="text-2xl font-bold text-blue-900 mb-6 text-center">Logowanie</h2>

        @if($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

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

            <!-- HASŁO -->
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1">Hasło</label>
                <input
                    type="password"
                    name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Wpisz hasło"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-900 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold"
            >
                Zaloguj się
            </button>
        </form>

        <p class="text-center text-gray-500 mt-4">
            Nie masz konta?
            <a href="/register" class="text-blue-600 hover:underline">Zarejestruj się</a>
        </p>
    </div>
@endsection
