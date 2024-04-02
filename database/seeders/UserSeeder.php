<?php

namespace Database\Seeders;

use App\Models\Configuracion;
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
            'password' => Hash::make('sistema'),
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Jesus Manriquez Vargas',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'area' => 'Departamento De Operación Y Desarrollo De Sistemas',
            'email' => 'subdirti.irycem@correo.michoacan.gob.mx',
            'password' => Hash::make('sistema'),
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Sistema RPP',
            'ubicacion' => 'RPP',
            'status' => 'activo',
            'area' => 'Dirección del Registro Público de la Propiedad',
            'email' => 'sistemarpp@gmail.com',
            'password' => Hash::make('sistema'),
        ])->assignRole('Sistemas');

        Configuracion::create([
            'entrada' => 1
        ]);

    }
}
