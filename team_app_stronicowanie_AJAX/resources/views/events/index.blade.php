@extends('layout')

@section('content')
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-blue-900 mb-6">📅 Treningi</h1>

        <!-- FILTROWANIE -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <form method="GET" action="/events" id="filter-form">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <input
                        type="date"
                        name="data_od"
                        value="{{ request('data_od') }}"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <input
                        type="date"
                        name="data_do"
                        value="{{ request('data_do') }}"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div class="flex gap-3 mt-4">
                    <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Filtruj
                    </button>
                    <a href="/events" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                        Resetuj
                    </a>
                </div>
            </form>
        </div>

        <!-- LISTA TRENINGÓW -->
        <div id="events-list">
        @forelse($events as $event)
            <div class="bg-white rounded-xl shadow p-6 mb-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-blue-900">{{ $event->nazwa }}</h2>
                    <p class="text-gray-500 text-sm mt-1">
                        📍 {{ $event->miejsce ?? 'Brak miejsca' }} &nbsp;|&nbsp;
                        🕐 {{ $event->data_i_czas_start->format('d.m.Y H:i') }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- STATUS -->
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
                    <a href="/events/{{ $event->idEvent }}" class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                        Szczegóły
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
                Brak treningów dla podanych filtrów.
            </div>
        @endforelse
        </div>

        <!-- STRONICOWANIE -->
        @if($events->hasPages())
            <div class="mt-6" id="pagination">
                {{ $events->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput  = document.querySelector('input[name="search"]');
            const statusSelect = document.querySelector('select[name="status"]');
            const dataOd       = document.querySelector('input[name="data_od"]');
            const dataDo       = document.querySelector('input[name="data_do"]');
            const resultsList  = document.getElementById('events-list');

            function fetchEvents() {
                const params = new URLSearchParams({
                    search:  searchInput.value,
                    status:  statusSelect.value,
                    data_od: dataOd.value,
                    data_do: dataDo.value,
                });

                fetch('/events?' + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        resultsList.innerHTML = '';
// Ukryj stronicowanie gdy AJAX aktywny
                        const pagination = document.getElementById('pagination');
                        if (pagination) pagination.style.display = 'none';                        if (data.events.length === 0) {
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
                            const color = colors[event.status] || 'bg-gray-100 text-gray-800';
                            const status = event.status.charAt(0).toUpperCase() + event.status.slice(1);

                            resultsList.innerHTML += `
                    <div class="bg-white rounded-xl shadow p-6 mb-4 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-blue-900">${event.nazwa}</h2>
                            <p class="text-gray-500 text-sm mt-1">
                                📍 ${event.miejsce ?? 'Brak miejsca'} &nbsp;|&nbsp;
                                🕐 ${event.data_i_czas_start}
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium ${color}">
                                ${status}
                            </span>
                            <a href="/events/${event.idEvent}"
                               class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                Szczegóły
                            </a>
                        </div>
                    </div>`;
                        });
                    });
            }

            searchInput.addEventListener('input', fetchEvents);
            statusSelect.addEventListener('change', fetchEvents);
            dataOd.addEventListener('change', fetchEvents);
            dataDo.addEventListener('change', fetchEvents);
        });
    </script>
@endsection
