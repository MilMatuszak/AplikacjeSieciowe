<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_events', function (Blueprint $table) {
            $table->unsignedBigInteger('idTeam')->nullable()->after('miejsce');
            $table->unsignedBigInteger('idTyp')->nullable()->after('idTeam');

            $table->foreign('idTeam')
                ->references('idTeam')
                ->on('teams')
                ->nullOnDelete();

            $table->foreign('idTyp')
                ->references('idTyp')
                ->on('event_types')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('team_events', function (Blueprint $table) {
            $table->dropForeign(['idTeam']);
            $table->dropForeign(['idTyp']);
            $table->dropColumn(['idTeam', 'idTyp']);
        });
    }
};
