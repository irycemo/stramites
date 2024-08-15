<?php

namespace Database\Seeders;

use App\Models\Dependencia;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DependenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Dependencia::create([
            'nombre' => 'Secreatria de Gobierno',
        ]);

        Dependencia::create([
            'nombre' => 'Secreatria de Bienestar',
        ]);

        Dependencia::create([
            'nombre' => 'Secreatria de Finanzas',
        ]);

        Dependencia::create([
            'nombre' => 'Secreatria de Migración',
        ]);

        Dependencia::create([
            'nombre' => 'Secreatria de Hacienda',
        ]);

        Dependencia::create([
            'nombre' => 'Secreatria de la Mujer',
        ]);

        Dependencia::create([
            'nombre' => 'Secreatria del trabajo',
        ]);

    }
}
