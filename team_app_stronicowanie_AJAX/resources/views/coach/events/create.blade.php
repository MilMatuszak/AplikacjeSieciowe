@extends('layout')

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="/coach/events" class="text-blue-600 hover:underline text-sm">← Wróć do listy</a>

        <div class="bg-white rounded-xl shadow p-8 mt-4">
            <h1 class="text-2xl font-bold text-blue-900 mb-6">➕ Nowy trening</h1>

            @if($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/coach/events">
                @csrf

                <!-- NAZWA -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Nazwa treningu</label>
                    <input
                        type="text"
                        name="nazwa"
                        value="{{ old('nazwa') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="np. Trening siłowy"
                    >
                </div>

                <!-- DATA ROZPOCZĘCIA -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Data i godzina rozpoczęcia</label>
                    <input
                        type="datetime-local"
                        name="data_i_czas_start"
                        value="{{ old('data_i_czas_start') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- DATA ZAKOŃCZENIA -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Data i godzina zakończenia</label>
                    <input
                        type="datetime-local"
                        name="data_i_czas_koniec"
                        value="{{ old('data_i_czas_koniec') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- TYP WYDARZENIA -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Typ wydarzenia</label>
                    <select name="idTyp" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Wybierz typ</option>
                        @foreach($eventTypes as $type)
                            <option value="{{ $type->idTyp }}" {{ old('idTyp') == $type->idTyp ? 'selected' : '' }}>
                                {{ ucfirst($type->nazwa) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- DRUŻYNA -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Drużyna</label>
                    <select name="idTeam" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Wybierz drużynę</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->idTeam }}" {{ old('idTeam') == $team->idTeam ? 'selected' : '' }}>
                                {{ $team->nazwa }} ({{ $team->sport }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- MIEJSCE -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Miejsce</label>
                    <input
                        type="text"
                        name="miejsce"
                        value="{{ old('miejsce') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="np. Hala sportowa"
                    >
                </div>

                <!-- OPIS -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-1">Opis (opcjonalnie)</label>
                    <textarea
                        name="opis"
                        rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Opis treningu..."
                    >{{ old('opis') }}</textarea>
                </div>

                <button
                    type="submit"
                    class="w-full bg-green-700 text-white py-2 rounded-lg hover:bg-green-600 transition font-semibold"
                >
                    Zapisz trening
                </button>
            </form>
        </div>
    </div>
@endsection
