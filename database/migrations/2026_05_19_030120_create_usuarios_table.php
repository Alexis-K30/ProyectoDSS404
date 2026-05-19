<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telefono', 25)->nullable();
            $table->string('calle')->nullable();
            $table->string('ciudad', 50)->nullable();
            $table->string('estado_dir', 25)->nullable();
            $table->string('codigo_postal', 5)->nullable();
            $table->tinyInteger('rol')->default(1);   // 1=cliente, 2=personal, 3=admin
            $table->tinyInteger('estado')->default(1); // 1=activo, 0=bloqueado, 2=expirado
            $table->timestamp('email_verificado_en')->nullable();
            $table->timestamps();

            $table->index(['rol', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
