<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_types', function (Blueprint $table) {
            $table->id('idTyp');
            $table->string('nazwa', 50)->unique();
            // wartości: trening, mecz, zgrupowanie, spotkanie
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_types');
    }
};
