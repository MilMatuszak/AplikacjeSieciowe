@extends('layout')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-blue-900">📋 Moje treningi</h1>
            <a href="/coach/events/create" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">
                + Nowy trening
            </a>
        </div>

        <!-- FILTROWANIE -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <form method="GET" action="/coach/events">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Szukaj po nazwie lub miejscu..."
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Wszystkie statusy</option>
                        <option value="zaplanowane" {{ request('status') == 'zaplanowane' ? 'selected' : '' }}>Zaplanowane</option>
                        <option value="trwa" {{ request('status') == 'trwa' ? 'selected' : '' }}>Trwa</option>
                        <option value="zakonczone" {{ request('status') == 'zakonczone' ? 'selected' : '' }}>Zakończone</option>
                        <option value="odwolane" {{ request('status') == 'odwolane' ? 'selected' : '' }}>Odwołane</option>
                    </select>
                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition w-full">
                            Szukaj
                        </button>
                        <a href="/coach/events" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition w-full text-center">
                            Resetuj
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div id="coach-events-list">


        @forelse($events as $event)
            <div class="bg-white rounded-xl shadow p-6 mb-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-blue-900">{{ $event->nazwa }}</h2>
                        <p class="text-gray-500 text-sm mt-1">
                            📍 {{ $event->miejsce ?? 'Brak miejsca' }} &nbsp;|&nbsp;
                            🕐 {{ $event->data_i_czas_start->format('d.m.Y H:i') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
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
                        <a href="/coach/events/{{ $event->idEvent }}/attendance"
                           class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition text-sm">
                            Obecność
                        </a>
                        <a href="/coach/events/{{ $event->idEvent }}/edit"
                           class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                            Edytuj
                        </a>
                        <form method="POST" action="/coach/events/{{ $event->idEvent }}"
                              onsubmit="return confirm('Czy na pewno chcesz usunąć ten trening?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 transition text-sm">
                                Usuń
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
                Nie masz jeszcze żadnych treningów.
                <a href="/coach/events/create" class="text-blue-600 hover:underline ml-1">Dodaj pierwszy!</a>
            </div>
        @endforelse
        </div>


    @if($events->hasPages())
            <div class="mt-6">
                {{ $events->withQueryString()->links() }}
            </div>
    @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput  = document.querySelector('input[name="search"]');
                const statusSelect = document.querySelector('select[name="status"]');
                const resultsList  = document.getElementById('coach-events-list');

                function fetchEvents() {
                    const params = new URLSearchParams({
                        search: searchInput.value,
                        status: statusSelect.value,
                    });

                    fetch('/coach/events?' + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            resultsList.innerHTML = '';

                            if (data.events.length === 0) {
                                resultsList.innerHTML = `
                    <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
                        Brak treningów dla podanych filtrów.
                    </div>`;
                                return;
                            }

                            data.events.forEach(event => {
                                const colors = {
                                    'zaplanowane': 'bg-blue-100 text-blue-800',
                                    'trwa':        'bg-green-100 text-green-800',
                                    'zakonczone':  'bg-gray-100 text-gray-800',
                                    'odwolane':    'bg-red-100 text-red-800',
                                };
                                const color  = colors[event.status] || 'bg-gray-100 text-gray-800';
                                const status = event.status.charAt(0).toUpperCase() + event.status.slice(1);

                                resultsList.innerHTML += `
                    <div class="bg-white rounded-xl shadow p-6 mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-blue-900">${event.nazwa}</h2>
                                <p class="text-gray-500 text-sm mt-1">
                                    📍 ${event.miejsce ?? 'Brak miejsca'} &nbsp;|&nbsp;
                                    🕐 ${event.data_i_czas_start}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-full text-sm font-medium ${color}">
                                    ${status}
                                </span>
                                <a href="/coach/events/${event.idEvent}/attendance"
                                   class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition text-sm">
                                    Obecność
                                </a>
                                <a href="/coach/events/${event.idEvent}/edit"
                                   class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                    Edytuj
                                </a>
                            </div>
                        </div>
                    </div>`;
                            });
                        });
                }

                searchInput.addEventListener('input', fetchEvents);
                statusSelect.addEventListener('change', fetchEvents);
            });
        </script>

@endsection
