<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_rsvp', function (Blueprint $table) {
            $table->unsignedBigInteger('idEvent');
            $table->unsignedBigInteger('idUser');
            $table->primary(['idEvent', 'idUser']);
            $table->unsignedBigInteger('idStatus');
            $table->timestamp('czas_zgloszenia')->useCurrent();
            $table->timestamp('czas_aktualizacji')->useCurrent()->useCurrentOnUpdate();
            $table->text('uwagi')->nullable();

            $table->foreign('idEvent')
                ->references('idEvent')
                ->on('team_events')
                ->cascadeOnDelete();

            $table->foreign('idUser')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('idStatus')
                ->references('idStatus')
                ->on('rsvp_statuses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_rsvp');
    }
};
