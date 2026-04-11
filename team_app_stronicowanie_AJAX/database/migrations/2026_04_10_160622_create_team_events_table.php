<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_events', function (Blueprint $table) {
            $table->id('idEvent');
            $table->string('nazwa', 150);
            $table->text('opis')->nullable();
            $table->dateTime('data_i_czas_start');
            $table->dateTime('data_i_czas_koniec');
            $table->string('miejsce', 200)->nullable();
            $table->enum('status', ['zaplanowane', 'trwa', 'zakonczone', 'odwolane'])
                ->default('zaplanowane');
            // RODO
            $table->unsignedBigInteger('kto_utworzyl_id')->nullable();
            $table->timestamp('kiedy_utworzono')->useCurrent();
            $table->unsignedBigInteger('kto_aktualizowal_id')->nullable();
            $table->timestamp('kiedy_aktualizowano')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('kto_utworzyl_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('kto_aktualizowal_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_events');
    }
};
