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
            'nombre' => 'Dell',
        ]);

        Subcategoria::create([
            'categoria_hijo_id' => 1,
            'nombre' => 'HP',
        ]);

        Subcategoria::create([
            'categoria_hijo_id' => 2,
            'nombre' => 'Gamer',
        ]);

        Subcategoria::create([
            'categoria_hijo_id' => 3,
            'nombre' => 'Cisco',
        ]);
        Subcategoria::create([
            'categoria_hijo_id' => 4,
            'nombre' => 'Huawei',
        ]);
    }
}
