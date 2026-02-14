<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipo;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Equipo::create([
            'subcategoria_id' => 1,
            'nombre' => 'Laptop Dell Latitude',
            'numero_serie' => 'DL12345',
            'estatus' => 'Activo',
            'notas' => 'Equipo asignado a sistemas'
        ]);

        Equipo::create([
            'subcategoria_id' => 2,
            'nombre' => 'Laptop HP Elitebook',
            'numero_serie' => 'HP98765',
            'estatus' => 'Activo',
            'notas' => 'Equipo nuevo'
        ]);

        Equipo::create([
            'subcategoria_id' => 3,
            'nombre' => 'Monitor Samsung 24',
            'numero_serie' => 'SM24001',
            'estatus' => 'Activo',
            'notas' => 'Monitor asignado a área administrativa'
        ]);

        Equipo::create([
            'subcategoria_id' => 1,
            'nombre' => 'Monitor LG 27',
            'numero_serie' => 'LG27002',
            'estatus' => 'Activo',
            'notas' => 'Monitor para diseño gráfico'
        ]);

        Equipo::create([
            'subcategoria_id' => 1,
            'nombre' => 'Impresora HP LaserJet Pro',
            'numero_serie' => 'HPLJ3344',
            'estatus' => 'Activo',
            'notas' => 'Impresora compartida en oficina'
        ]);

        Equipo::create([
            'subcategoria_id' => 2,
            'nombre' => 'Router Cisco RV340',
            'numero_serie' => 'CSC34055',
            'estatus' => 'Mantenimiento',
            'notas' => 'En revisión por fallas de conexión'
        ]);

        Equipo::create([
            'subcategoria_id' => 3,
            'nombre' => 'Teclado Logitech K380',
            'numero_serie' => 'LOGK38066',
            'estatus' => 'Activo',
            'notas' => 'Periférico inalámbrico'
        ]);

        Equipo::create([
            'subcategoria_id' => 3,
            'nombre' => 'Mouse Logitech MX Master 3',
            'numero_serie' => 'LOGMX3707',
            'estatus' => 'Baja',
            'notas' => 'Equipo dado de baja por desgaste'
        ]);
    }
}
