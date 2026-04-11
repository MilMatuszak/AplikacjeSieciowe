@extends('layout')

@section('content')
    <div class="max-w-5xl mx-auto">
        <a href="/coach/events" class="text-blue-600 hover:underline text-sm">← Wróć do listy treningów</a>

        <div class="flex justify-between items-center mt-4 mb-6">
            <h1 class="text-3xl font-bold text-blue-900">📋 Lista obecności</h1>
            <span class="text-gray-500 text-sm">{{ $event->nazwa }} — {{ $event->data_i_czas_start->format('d.m.Y H:i') }}</span>
        </div>

        <!-- STATYSTYKI OBECNOŚCI -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            @php
                $total         = $attendees->total();
                $potwierdzone  = $attendees->getCollection()->where('status_nazwa', 'potwierdzona')->count();
                $zgloszone     = $attendees->getCollection()->where('status_nazwa', 'zgloszona')->count();
                $nieobecne     = $attendees->getCollection()->where('status_nazwa', 'nieobecna')->count();
                $usprawiedliwione = $attendees->getCollection()->where('status_nazwa', 'usprawiedliwiona')->count();
            @endphp
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <p class="text-gray-500 text-sm">Łącznie zgłoszeń</p>
                <p class="text-3xl font-bold text-blue-900">{{ $total }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <p class="text-gray-500 text-sm">Potwierdzonych</p>
                <p class="text-3xl font-bold text-green-700">{{ $potwierdzone }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <p class="text-gray-500 text-sm">Zgłoszonych</p>
                <p class="text-3xl font-bold text-blue-700">{{ $zgloszone }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <p class="text-gray-500 text-sm">Nieobecnych</p>
                <p class="text-3xl font-bold text-red-600">{{ $nieobecne }}</p>
            </div>
        </div>

        <!-- LISTA -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Zawodnik</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Login</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Zgłoszono</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Uwagi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($attendees as $attendee)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $attendee->imie }} {{ $attendee->nazwisko }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $attendee->login }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($attendee->status_nazwa) {
                                    'zgloszona'        => 'bg-blue-100 text-blue-800',
                                    'potwierdzona'     => 'bg-green-100 text-green-800',
                                    'nieobecna'        => 'bg-red-100 text-red-800',
                                    'usprawiedliwiona' => 'bg-yellow-100 text-yellow-800',
                                    default            => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <form method="POST" action="/coach/events/{{ $event->idEvent }}/attendance/{{ $attendee->idUser }}">
                                @csrf
                                @method('PUT')
                                <select name="idStatus" onchange="this.form.submit()"
                                        class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->idStatus }}"
                                            {{ $attendee->idStatus == $status->idStatus ? 'selected' : '' }}>
                                            {{ ucfirst($status->nazwa) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            {{ \Carbon\Carbon::parse($attendee->czas_zgloszenia)->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            {{ $attendee->uwagi ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Brak zgłoszeń na ten trening.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- STRONICOWANIE -->
        @if($attendees->hasPages())
            <div class="mt-6">
                {{ $attendees->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
