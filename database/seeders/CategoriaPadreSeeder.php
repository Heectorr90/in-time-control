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
            'nombre' => 'Equipos de Computo',
        ]);

        CategoriaPadre::create([
            'nombre' => 'Mobiliario',
        ]);

        CategoriaPadre::create([
            'nombre' => 'Redes',
        ]);
    }
}
