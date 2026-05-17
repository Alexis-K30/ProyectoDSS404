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
        Schema::create('Usuario', function (Blueprint $table) {
            $table->id('ID_cliente');
            $table->string('Nombre');
            $table->string('Telefono')->nullable();
            $table->enum('TipoUsuario', ['Cliente', 'Empleado', 'Administrador']);
            $table->date('FechaCrea')->nullable();
            $table->unsignedBigInteger('ID_administrador')->nullable();
            
            $table->foreign('ID_administrador')
                  ->references('ID_cliente')
                  ->on('Usuario')
                  ->onDelete('set null');
                  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Usuario');
    }
};
