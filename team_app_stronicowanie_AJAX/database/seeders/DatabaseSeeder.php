<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nazwa' => 'player', 'opis' => 'Zawodnik drużyny'],
            ['nazwa' => 'coach',  'opis' => 'Trener drużyny'],
            ['nazwa' => 'admin',  'opis' => 'Administrator systemu'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore([
                'nazwa'      => $role['nazwa'],
                'opis'       => $role['opis'],
                'is_active'  => true,
                'aktywna_od' => now(),
            ]);
        }
    }
}
