<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login', 100)->unique()->after('id');
            $table->string('imie', 100)->after('login');
            $table->string('nazwisko', 100)->after('imie');
            $table->string('telefon', 20)->nullable()->after('email');
            // RODO
            $table->unsignedBigInteger('kto_utworzyl_id')->nullable()->after('remember_token');
            $table->timestamp('kiedy_utworzono')->useCurrent()->after('kto_utworzyl_id');
            $table->unsignedBigInteger('kto_aktualizowal_id')->nullable()->after('kiedy_utworzono');
            $table->timestamp('kiedy_aktualizowano')->useCurrent()->useCurrentOnUpdate()->after('kto_aktualizowal_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'login', 'imie', 'nazwisko', 'telefon',
                'kto_utworzyl_id', 'kiedy_utworzono',
                'kto_aktualizowal_id', 'kiedy_aktualizowano',
            ]);
        });
    }
};
