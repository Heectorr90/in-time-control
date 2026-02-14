<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriaPadre extends Model
{
    use HasFactory;

    protected $table = 'categoria_padres';

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    public function hijos()
    {
        return $this->hasMany(CategoriaHijo::class);
    }
}
