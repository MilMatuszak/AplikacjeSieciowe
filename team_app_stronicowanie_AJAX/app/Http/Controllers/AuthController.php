<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    // POKAŻ FORMULARZ LOGOWANIA
    public function showLogin()
    {
        return view('auth.login');
    }

    // LOGOWANIE
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('login', $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'login' => 'Nieprawidłowy login lub hasło.',
            ])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard');
    }

    // POKAŻ FORMULARZ REJESTRACJI
    public function showRegister()
    {
        return view('auth.register');
    }

    // REJESTRACJA
    public function register(Request $request)
    {
        $request->validate([
            'imie'                  => 'required|string|max:100',
            'nazwisko'              => 'required|string|max:100',
            'login'                 => 'required|string|max:100|unique:users,login',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'imie'     => $request->imie,
            'nazwisko' => $request->nazwisko,
            'login'    => $request->login,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // RODO: użytkownik sam stworzył swój rekord
        $user->update(['kto_utworzyl_id' => $user->id]);

        // Domyślna rola: player
        $role = DB::table('roles')->where('nazwa', 'player')->first();
        if ($role) {
            DB::table('user_roles')->insert([
                'idUser'       => $user->id,
                'idRola'       => $role->idRola,
                'data_nadania' => now(),
                'kto_nadan_id' => $user->id,
                'jest_aktywna' => true,
            ]);
        }

        Auth::login($user);
        return redirect('/dashboard');
    }

    // WYLOGOWANIE
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
