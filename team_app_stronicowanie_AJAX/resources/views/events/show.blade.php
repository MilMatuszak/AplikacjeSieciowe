@extends('layout')

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="/events" class="text-blue-600 hover:underline text-sm">← Wróć do listy</a>

        <div class="bg-white rounded-xl shadow p-8 mt-4">
            <div class="flex justify-between items-start mb-6">
                <h1 class="text-3xl font-bold text-blue-900">{{ $event->nazwa }}</h1>
                @php
                    $statusColor = match($event->status) {
                        'zaplanowane' => 'bg-blue-100 text-blue-800',
                        'trwa'        => 'bg-green-100 text-green-800',
                        'zakonczone'  => 'bg-gray-100 text-gray-800',
                        'odwolane'    => 'bg-red-100 text-red-800',
                        default       => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                {{ ucfirst($event->status) }}
            </span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-gray-500 text-sm">📅 Rozpoczęcie</p>
                    <p class="font-semibold">{{ $event->data_i_czas_start->format('d.m.Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">🏁 Zakończenie</p>
                    <p class="font-semibold">{{ $event->data_i_czas_koniec->format('d.m.Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">📍 Miejsce</p>
                    <p class="font-semibold">{{ $event->miejsce ?? 'Nie podano' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">👤 Dodał</p>
                    <p class="font-semibold">{{ $event->utworzyl->imie ?? 'Nieznany' }} {{ $event->utworzyl->nazwisko ?? '' }}</p>
                </div>
            </div>

            @if($event->opis)
                <div class="border-t pt-4 mb-6">
                    <p class="text-gray-500 text-sm mb-2">📝 Opis</p>
                    <p class="text-gray-700">{{ $event->opis }}</p>
                </div>
            @endif

            <!-- ZGŁOSZENIE OBECNOŚCI -->
            @if($event->status == 'zaplanowane' || $event->status == 'trwa')
                <div class="border-t pt-6">
                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($rsvp)
                        <!-- JUŻ ZGŁOSZONY -->
                        <div class="flex items-center justify-between">
                            <p class="text-green-700 font-semibold">✅ Zgłoszono obecność</p>
                            <form method="POST" action="/events/{{ $event->idEvent }}/rsvp">
                                @csrf
                                @method('PUT')
                                <select name="idStatus" onchange="this.form.submit()"
                                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->idStatus }}"
                                            {{ $rsvp->idStatus == $status->idStatus ? 'selected' : '' }}>
                                            {{ ucfirst($status->nazwa) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    @else
                        <!-- NIE ZGŁOSZONY -->
                        <form method="POST" action="/events/{{ $event->idEvent }}/rsvp">
                            @csrf
                            <button type="submit"
                                    class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition font-semibold">
                                ✋ Zgłoś obecność
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
