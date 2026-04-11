<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('idUser');
            $table->unsignedBigInteger('idRola');
            $table->primary(['idUser', 'idRola']);
            $table->timestamp('data_nadania')->useCurrent();
            $table->unsignedBigInteger('kto_nadan_id')->nullable();
            $table->timestamp('data_odebrania')->nullable();
            $table->unsignedBigInteger('kto_odbral_id')->nullable();
            $table->boolean('jest_aktywna')->default(true);

            $table->foreign('idUser')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('idRola')
                ->references('idRola')
                ->on('roles')
                ->cascadeOnDelete();

            $table->foreign('kto_nadan_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('kto_odbral_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
