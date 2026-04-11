<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('idRola');
            $table->string('nazwa', 50)->unique();
            $table->string('opis', 200)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('aktywna_od')->useCurrent();
            $table->timestamp('aktywna_do')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
