@extends('layout')

@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-blue-900 mb-6">⚙️ Zarządzanie użytkownikami</h1>

        <!-- FILTROWANIE -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <form method="GET" action="/admin/users">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Szukaj po imieniu, nazwisku, loginie..."
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <select name="rola" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Wszystkie role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->nazwa }}" {{ request('rola') == $role->nazwa ? 'selected' : '' }}>
                                {{ ucfirst($role->nazwa) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition w-full">
                            Filtruj
                        </button>
                        <a href="/admin/users" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition w-full text-center">
                            Resetuj
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- LISTA UŻYTKOWNIKÓW -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Użytkownik</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Login</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Role</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Akcje</th>
                </tr>
                </thead>
                <tbody id="users-list" class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $user->imie }} {{ $user->nazwisko }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->login }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @foreach($user->getRoles() as $role)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium mr-1">
                                {{ ucfirst($role) }}
                            </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="/admin/users/{{ $user->id }}/edit"
                               class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                Edytuj rolę
                            </a>
                            <form method="POST" action="/admin/users/{{ $user->id }}"
                                  onsubmit="return confirm('Czy na pewno chcesz usunąć tego użytkownika?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 transition text-sm">
                                    Usuń
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Brak użytkowników dla podanych filtrów.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- STRONICOWANIE -->
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->withQueryString()->links() }}
            </div>
    @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.querySelector('input[name="search"]');
                const rolaSelect  = document.querySelector('select[name="rola"]');
                const resultsList = document.getElementById('users-list');

                function fetchUsers() {
                    const params = new URLSearchParams({
                        search: searchInput.value,
                        rola:   rolaSelect.value,
                    });

                    fetch('/admin/users?' + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            const tbody = document.getElementById('users-list');
                            tbody.innerHTML = '';

                            if (data.users.length === 0) {
                                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Brak użytkowników dla podanych filtrów.
                        </td>
                    </tr>`;
                                return;
                            }

                            data.users.forEach(user => {
                                const roles = user.role.map(r =>
                                    `<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium mr-1">${r.charAt(0).toUpperCase() + r.slice(1)}</span>`
                                ).join('');

                                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">${user.imie} ${user.nazwisko}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">${user.login}</td>
                        <td class="px-6 py-4 text-gray-600">${user.email}</td>
                        <td class="px-6 py-4">${roles}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="/admin/users/${user.id}/edit"
                               class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                Edytuj rolę
                            </a>
                            <form method="POST" action="/admin/users/${user.id}"
                                  onsubmit="return confirm('Czy na pewno chcesz usunąć tego użytkownika?')">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 transition text-sm">
                                    Usuń
                                </button>
                            </form>
                        </td>
                    </tr>`;
                            });
                        });
                }

                searchInput.addEventListener('input', fetchUsers);
                rolaSelect.addEventListener('change', fetchUsers);
            });
        </script>
@endsection
