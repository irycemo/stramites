<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Enrique',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'area' => 'Departamento De Operación Y Desarrollo De Sistemas',
            'email' => 'enrique_j_@hotmail.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Jesús Manriquez Vargas',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'area' => 'Subdirección De Tecnologías De La Información',
            'email' => 'subdirti.irycem@correo.michoacan.gob.mx',
            'password' => Hash::make('12345678'),
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Uruapan precalificación',
            'ubicacion' => 'Regional 4',
            'status' => 'activo',
            'area' => 'Coordinación Regional 4 Purhépecha (Uruapan)',
            'email' => 'uruapan1@correo.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('Precalificación');

    }
}
