<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategoriaServicioSeeder::class);
        $this->call(UmaSeeder::class);
        $this->call(DependenciaSeeder::class);

        /* $this->call(TramiteSeeder::class); */
        $this->call(NotariaSeeder::class);
        $this->call(ServiciosTableSeeder::class);
    }
}
