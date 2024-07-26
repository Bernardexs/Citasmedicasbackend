<?php

namespace Database\Seeders;

use App\Models\Especialidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EspecialidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Especialidad::create([
            'nombre' => 'Cardiología',
            'descripcion' => 'Especialidad en el corazón y sistema circulatorio',
        ]);

        Especialidad::create([
            'nombre' => 'Dermatología',
            'descripcion' => 'Especialidad en enfermedades de la piel',
        ]);

        Especialidad::create([
            'nombre' => 'Neurología',
            'descripcion' => 'Especialidad en el sistema nervioso',
        ]);

        Especialidad::create([
            'nombre' => 'Pediatría',
            'descripcion' => 'Especialidad en la salud de los niños',
        ]);

    }
}
