@extends('layout')

@section('content')
    <div class="max-w-4xl mx-auto">
        <a href="/coach/players" class="text-blue-600 hover:underline text-sm">← Wróć do listy</a>

        <h1 class="text-3xl font-bold text-blue-900 mt-4 mb-6">
            📊 Statystyki — {{ $player->imie }} {{ $player->nazwisko }}
        </h1>

        <!-- PODSUMOWANIE -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <p class="text-gray-500 text-sm mb-1">Treningi łącznie</p>
                <p class="text-4xl font-bold text-blue-900">{{ $stats->treningi_lacznie ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <p class="text-gray-500 text-sm mb-1">Treningi odbyte</p>
                <p class="text-4xl font-bold text-green-700">{{ $stats->treningi_odbyte ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <p class="text-gray-500 text-sm mb-1">Wskaźnik frekwencji</p>
                <p class="text-4xl font-bold text-purple-700">{{ $stats->wskaznik_frekwencji ?? 0 }}%</p>
            </div>
        </div>

        <!-- HISTORIA -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold text-blue-900">Historia obecności</h2>
            </div>
            <table class="w-full">
                <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Trening</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Data</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Miejsce</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($rsvps as $rsvp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $rsvp->nazwa }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($rsvp->data_i_czas_start)->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $rsvp->miejsce ?? 'Nie podano' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($rsvp->status_nazwa) {
                                    'zgloszona'        => 'bg-blue-100 text-blue-800',
                                    'potwierdzona'     => 'bg-green-100 text-green-800',
                                    'nieobecna'        => 'bg-red-100 text-red-800',
                                    'usprawiedliwiona' => 'bg-yellow-100 text-yellow-800',
                                    default            => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                            {{ ucfirst($rsvp->status_nazwa) }}
                        </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Brak historii obecności.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- STRONICOWANIE -->
        @if($rsvps->hasPages())
            <div class="mt-6">
                {{ $rsvps->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
