<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rsvp_statuses', function (Blueprint $table) {
            $table->id('idStatus');
            $table->string('nazwa', 50)->unique();
            // wartości: zgloszona, potwierdzona, nieobecna, usprawiedliwiona
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rsvp_statuses');
    }
};
