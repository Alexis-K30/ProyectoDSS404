<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorias extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre_categoria',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Productos::class, 'categoria_id');
    }
}
