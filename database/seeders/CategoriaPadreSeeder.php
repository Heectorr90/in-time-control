<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaPadre;

class CategoriaPadreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoriaPadre::create([
            'codigo' => 1,
            'nombre' => 'Equipos de Computo',
        ]);

        CategoriaPadre::create([
            'codigo' => 2,
            'nombre' => 'Mobiliario',
        ]);

        CategoriaPadre::create([
            'codigo' => 3,
            'nombre' => 'Redes',
        ]);
    }
}
