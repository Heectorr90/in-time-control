<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Subcategoria;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipos';

    protected $fillable = [
        'subcategoria_id',
        'codigo_inventario',
        'nombre',
        'numero_serie',
        'estatus',
        'notas',
    ];

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'subcategoria_id');
    }

    // Acceso indirecto (útil para listados)
    public function categoriaHijo()
    {
        return $this->subcategoria->hijo;
    }

    public function categoriaPadre()
    {
        return $this->subcategoria->hijo->padre;
    }

    protected static function booted()
    {
        static::creating(function ($equipo) {

            // Cargar jerarquía completa
            $subcategoria = Subcategoria::with('hijo.padre')
                ->find($equipo->subcategoria_id);

            if (!$subcategoria) {
                throw new \Exception("Subcategoría no válida.");
            }

            $codigoPadre = $subcategoria->hijo->padre->codigo;
            $codigoHijo  = $subcategoria->hijo->codigo;
            $codigoSub   = $subcategoria->codigo;

            // Concatenar
            $prefijoBase = $codigoPadre . $codigoHijo . $codigoSub;

            // Rellenar con ceros a la derecha hasta 10 dígitos
            $prefijo = str_pad($prefijoBase, 10, '0', STR_PAD_RIGHT);

            // Buscar último consecutivo por prefijo
            $ultimoEquipo = self::where('codigo_inventario', 'like', $prefijo . '-%')
                ->orderByDesc('codigo_inventario')
                ->first();

            if ($ultimoEquipo) {
                $ultimoConsecutivo = (int) substr($ultimoEquipo->codigo_inventario, -4);
                $nuevoConsecutivo = $ultimoConsecutivo + 1;
            } else {
                $nuevoConsecutivo = 1;
            }

            // Formatear consecutivo a 4 dígitos
            $consecutivoFormateado = str_pad($nuevoConsecutivo, 4, '0', STR_PAD_LEFT);

            // Asignar código final
            $equipo->codigo_inventario = $prefijo . '-' . $consecutivoFormateado;
        });
    }
}
