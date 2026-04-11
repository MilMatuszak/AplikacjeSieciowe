<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserController extends Controller
{
    // LISTA UŻYTKOWNIKÓW
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('imie', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('nazwisko', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('login', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->rola) {
            $query->whereExists(function($q) use ($request) {
                $q->select(DB::raw(1))
                    ->from('user_roles')
                    ->join('roles', 'user_roles.idRola', '=', 'roles.idRola')
                    ->whereColumn('user_roles.idUser', 'users.id')
                    ->where('roles.nazwa', $request->rola)
                    ->where('user_roles.jest_aktywna', true);
            });
        }

        $users = $query->orderBy('nazwisko')->paginate(15);
        $roles = DB::table('roles')->where('is_active', true)->get();

        // Jeśli żądanie AJAX — zwróć JSON
        if ($request->ajax()) {
            return response()->json([
                'users' => $users->map(function($user) {
                    return [
                        'id'       => $user->id,
                        'imie'     => $user->imie,
                        'nazwisko' => $user->nazwisko,
                        'login'    => $user->login,
                        'email'    => $user->email,
                        'role'     => $user->getRoles(),
                    ];
                }),
                'total' => $users->total(),
            ]);
        }

        return view('admin.users.index', compact('users', 'roles'));
    }

    // FORMULARZ EDYCJI ROLI
    public function edit($id)
    {
        $user  = User::findOrFail($id);
        $roles = DB::table('roles')->where('is_active', true)->get();
        $currentRoles = DB::table('user_roles')
            ->where('idUser', $id)
            ->where('jest_aktywna', true)
            ->pluck('idRola')
            ->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'currentRoles'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|array',
            'role.*' => 'exists:roles,idRola',
        ]);

        $targetUser = User::findOrFail($id);
        $admin      = auth()->user();

        // Dezaktywuj wszystkie stare role
        DB::table('user_roles')
            ->where('idUser', $targetUser->id)
            ->where('jest_aktywna', true)
            ->update([
                'jest_aktywna'   => false,
                'data_odebrania' => now(),
                'kto_odbral_id'  => $admin->id,
            ]);

        // Nadaj wszystkie wybrane role
        foreach ($request->role as $idRola) {
            $existing = DB::table('user_roles')
                ->where('idUser', $targetUser->id)
                ->where('idRola', $idRola)
                ->first();

            if ($existing) {
                DB::table('user_roles')
                    ->where('idUser', $targetUser->id)
                    ->where('idRola', $idRola)
                    ->update([
                        'jest_aktywna'   => true,
                        'data_nadania'   => now(),
                        'kto_nadan_id'   => $admin->id,
                        'data_odebrania' => null,
                        'kto_odbral_id'  => null,
                    ]);
            } else {
                DB::table('user_roles')->insert([
                    'idUser'       => $targetUser->id,
                    'idRola'       => $idRola,
                    'data_nadania' => now(),
                    'kto_nadan_id' => $admin->id,
                    'jest_aktywna' => true,
                ]);
            }
        }

        return redirect('/admin/users')
            ->with('success', 'Role użytkownika zostały zaktualizowane!');
    }
    // RESET HASŁA
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password'            => Hash::make($request->password),
            'kto_aktualizowal_id' => auth()->id(),
        ]);

        return redirect('/admin/users')
            ->with('success', 'Hasło zostało zresetowane!');
    }

    // USUNIĘCIE UŻYTKOWNIKA
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Nie można usunąć samego siebie
        if ($user->id === auth()->id()) {
            return redirect('/admin/users')
                ->with('error', 'Nie możesz usunąć własnego konta!');
        }

        $user->delete();

        return redirect('/admin/users')
            ->with('success', 'Użytkownik został usunięty!');
    }

    // AKTUALIZACJA DANYCH UŻYTKOWNIKA
    public function updateData(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'imie'     => 'required|string|max:100',
            'nazwisko' => 'required|string|max:100',
            'login'    => 'required|string|max:100|unique:users,login,' . $id,
            'email'    => 'required|email|unique:users,email,' . $id,
        ]);

        $user->update([
            'imie'                => $request->imie,
            'nazwisko'            => $request->nazwisko,
            'login'               => $request->login,
            'email'               => $request->email,
            'kto_aktualizowal_id' => auth()->id(),
        ]);

        return redirect('/admin/users')
            ->with('success', 'Dane użytkownika zostały zaktualizowane!');
    }
}

