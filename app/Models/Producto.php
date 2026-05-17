<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'Producto';
    protected $primaryKey = 'ID_producto';

    protected $fillable = [
        'Nombre',
        'Precio',
        'Talla',
    ];

    /**
     * Cotizaciones que tienen este producto como producto principal.
     */
    public function cotizacionesDirectas()
    {
        return $this->hasMany(Cotizacion::class, 'ID_Producto', 'ID_producto');
    }

    /**
     * Cotizaciones que incluyen este producto (Relación Muchos a Muchos).
     */
    public function cotizaciones()
    {
        return $this->belongsToMany(Cotizacion::class, 'Cotizacion_Producto', 'ID_producto', 'ID_cotizacion');
    }
}
