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
        Schema::create('Cotizacion', function (Blueprint $table) {
            $table->id('ID_cotizacion');
            $table->date('FechaCotizacion');
            $table->date('Vencimiento')->nullable();
            $table->unsignedBigInteger('ID_Empleado');
            $table->unsignedBigInteger('IDCliente');
            $table->unsignedBigInteger('ID_Producto');
            
            $table->foreign('ID_Empleado')->references('ID_cliente')->on('Usuario');
            $table->foreign('IDCliente')->references('ID_cliente')->on('Usuario');
            $table->foreign('ID_Producto')->references('ID_producto')->on('Producto');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Cotizacion');
    }
};
