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
        Schema::create('Cotizacion_Producto', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_cotizacion');
            $table->unsignedBigInteger('ID_producto');
            
            $table->foreign('ID_cotizacion')->references('ID_cotizacion')->on('Cotizacion')->onDelete('cascade');
            $table->foreign('ID_producto')->references('ID_producto')->on('Producto')->onDelete('cascade');
            
            $table->primary(['ID_cotizacion', 'ID_producto']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Cotizacion_Producto');
    }
};
