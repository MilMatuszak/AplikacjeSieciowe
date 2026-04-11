<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id('idTeam');
            $table->string('nazwa', 100);
            $table->string('sport', 50);
            $table->text('opis')->nullable();
            // RODO
            $table->unsignedBigInteger('kto_utworzyl_id')->nullable();
            $table->timestamp('kiedy_utworzono')->useCurrent();

            $table->foreign('kto_utworzyl_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
