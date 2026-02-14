<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaHijo;

class CategoriaHijoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoriaHijo::create([
            'categoria_padre_id' => 1,
            'codigo' => 1,
            'nombre' => 'Laptops',
        ]);

        CategoriaHijo::create([
            'categoria_padre_id' => 1,
            'codigo' => 2,
            'nombre' => 'PC Escritorio',
        ]);

        CategoriaHijo::create([
            'categoria_padre_id' => 2,
            'codigo' => 1,
            'nombre' => 'Escritorios',
        ]);

        CategoriaHijo::create([
            'categoria_padre_id' => 3,
            'codigo' => 1,
            'nombre' => 'Switches',
        ]);
    }
}
