<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'Usuario';
    protected $primaryKey = 'ID_cliente';

    protected $fillable = [
        'Nombre',
        'Telefono',
        'TipoUsuario',
        'FechaCrea',
        'ID_administrador',
    ];

    /**
     * Relación de jerarquía: Un usuario puede tener un administrador/supervisor.
     */
    public function supervisor()
    {
        return $this->belongsTo(Usuario::class, 'ID_administrador', 'ID_cliente');
    }

    /**
     * Relación de jerarquía: Un administrador puede supervisar a varios usuarios.
     */
    public function subordinados()
    {
        return $this->hasMany(Usuario::class, 'ID_administrador', 'ID_cliente');
    }

    /**
     * Cotizaciones generadas por este usuario como empleado.
     */
    public function cotizacionesComoEmpleado()
    {
        return $this->hasMany(Cotizacion::class, 'ID_Empleado', 'ID_cliente');
    }

    /**
     * Cotizaciones solicitadas por este usuario como cliente.
     */
    public function cotizacionesComoCliente()
    {
        return $this->hasMany(Cotizacion::class, 'IDCliente', 'ID_cliente');
    }
}
