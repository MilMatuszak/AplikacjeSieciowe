@extends('layout')

@section('content')
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-blue-900 mb-6">👥 Lista zawodników</h1>

        <!-- FILTROWANIE -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <form method="GET" action="/coach/players">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Szukaj po imieniu, nazwisku, loginie..."
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 col-span-2"
                    >
                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition w-full">
                            Szukaj
                        </button>
                        <a href="/coach/players" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition w-full text-center">
                            Resetuj
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- LISTA -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Zawodnik</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Login</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Frekwencja</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Akcje</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($players as $player)
                    @php
                        $stats = \Illuminate\Support\Facades\DB::table('player_stats')
                            ->where('idUser', $player->id)
                            ->first();
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $player->imie }} {{ $player->nazwisko }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $player->login }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $player->email }}</td>
                        <td class="px-6 py-4">
                        <span class="font-semibold {{ ($stats->wskaznik_frekwencji ?? 0) >= 50 ? 'text-green-700' : 'text-red-600' }}">
                            {{ $stats->wskaznik_frekwencji ?? 0 }}%
                        </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="/coach/players/{{ $player->id }}/stats"
                               class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                Statystyki
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Brak zawodników.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- STRONICOWANIE -->
        @if($players->hasPages())
            <div class="mt-6">
                {{ $players->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
