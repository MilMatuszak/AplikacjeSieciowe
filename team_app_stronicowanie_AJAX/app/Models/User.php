<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'imie',
        'nazwisko',
        'login',
        'email',
        'password',
        'telefon',
        'kto_utworzyl_id',
        'kto_aktualizowal_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Sprawdź czy użytkownik ma daną rolę
    public function hasRole(string $role): bool
    {
        return DB::table('user_roles')
            ->join('roles', 'user_roles.idRola', '=', 'roles.idRola')
            ->where('user_roles.idUser', $this->id)
            ->where('roles.nazwa', $role)
            ->where('user_roles.jest_aktywna', true)
            ->exists();
    }

    // Pobierz wszystkie role użytkownika
    public function getRoles(): array
    {
        return DB::table('user_roles')
            ->join('roles', 'user_roles.idRola', '=', 'roles.idRola')
            ->where('user_roles.idUser', $this->id)
            ->where('user_roles.jest_aktywna', true)
            ->pluck('roles.nazwa')
            ->toArray();
    }

    // Relacja do user_roles (dla filtrowania)
    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'idUser', 'id');
    }
}
