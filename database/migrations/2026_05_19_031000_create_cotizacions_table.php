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
            
            $table->foreign('ID_Empleado')->references('id')->on('usuarios');
            $table->foreign('IDCliente')->references('id')->on('usuarios');
            $table->foreign('ID_Producto')->references('id')->on('productos');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Cotizacion');
    }
};
