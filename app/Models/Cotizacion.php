<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'Cotizacion';
    protected $primaryKey = 'ID_cotizacion';

    protected $fillable = [
        'FechaCotizacion',
        'Vencimiento',
        'ID_Empleado',
        'IDCliente',
        'ID_Producto',
    ];

    /**
     * El empleado que generó la cotización.
     */
    public function empleado()
    {
        return $this->belongsTo(Usuario::class, 'ID_Empleado', 'ID_cliente');
    }

    /**
     * El cliente para quien es la cotización.
     */
    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'IDCliente', 'ID_cliente');
    }

    /**
     * El producto principal seleccionado (si aplica).
     */
    public function productoPrincipal()
    {
        return $this->belongsTo(Producto::class, 'ID_Producto', 'ID_producto');
    }

    /**
     * Los productos incluidos en esta cotización (Relación Muchos a Muchos).
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'Cotizacion_Producto', 'ID_cotizacion', 'ID_producto');
    }
}
