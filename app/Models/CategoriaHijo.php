<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriaHijo extends Model
{
    use HasFactory;

    protected $table = 'categoria_hijos';

    protected $fillable = [
        'categoria_padre_id',
        'codigo',
        'nombre',
    ];

    public function padre()
    {
        return $this->belongsTo(CategoriaPadre::class, 'categoria_padre_id');
    }

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class);
    }

    protected static function booted()
    {
        static::creating(function ($hijo) {

            $max = self::where('categoria_padre_id', $hijo->categoria_padre_id)
                ->max('codigo');

            $hijo->codigo = $max ? $max + 1 : 1;
        });
    }
}
