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
        Schema::create('items_pedido', function (Blueprint $table) {
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->integer('item_id');
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_lista', 10, 2)->default(0);
            $table->decimal('descuento', 4, 2)->default(0);
            $table->primary(['pedido_id', 'item_id']);

            $table->index('producto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items__pedidos');
    }
};
