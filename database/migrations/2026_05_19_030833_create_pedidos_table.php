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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->tinyInteger('estado_pedido')->default(1);
            $table->date('fecha_pedido')->nullable();
            $table->date('fecha_requerida')->nullable();
            $table->date('fecha_envio')->nullable();
            $table->unsignedBigInteger('tienda_id')->nullable();
            $table->unsignedBigInteger('personal_id')->nullable();
            $table->timestamps();

            $table->index('usuario_id');
            $table->index('estado_pedido');
            $table->index('fecha_pedido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
