<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcategoria extends Model
{
    use HasFactory;

    protected $table = 'subcategorias';

    protected $fillable = [
        'categoria_hijo_id',
        'codigo',
        'nombre',
    ];

    public function hijo()
    {
        return $this->belongsTo(CategoriaHijo::class, 'categoria_hijo_id');
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    protected static function booted()
    {
        static::creating(function ($sub) {

            $max = self::where('categoria_hijo_id', $sub->categoria_hijo_id)
                ->max('codigo');

            $sub->codigo = $max ? $max + 1 : 1;
        });
    }
}
