<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategoria;

class SubcategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subcategoria::create([
            'categoria_hijo_id' => 1,
            'codigo' => 1,
            'nombre' => 'Dell',
        ]);

        Subcategoria::create([
            'categoria_hijo_id' => 1,
            'codigo' => 2,
            'nombre' => 'HP',
        ]);

        Subcategoria::create([
            'categoria_hijo_id' => 2,
            'codigo' => 1,
            'nombre' => 'Gamer',
        ]);

        Subcategoria::create([
            'categoria_hijo_id' => 4,
            'codigo' => 1,
            'nombre' => 'Cisco',
        ]);
    }
}
