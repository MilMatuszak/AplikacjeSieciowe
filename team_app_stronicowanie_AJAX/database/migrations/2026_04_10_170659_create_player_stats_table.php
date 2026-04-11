<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_stats', function (Blueprint $table) {
            $table->id('idStat');
            $table->unsignedBigInteger('idUser');
            $table->integer('treningi_odbyte')->default(0);
            $table->integer('treningi_lacznie')->default(0);
            $table->decimal('wskaznik_frekwencji', 5, 2)->default(0.00);
            $table->timestamp('ostatnia_aktualizacja')->useCurrent()->useCurrentOnUpdate();

            $table->unique('idUser');

            $table->foreign('idUser')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_stats');
    }
};
