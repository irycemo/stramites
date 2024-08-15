<?php

namespace Database\Seeders;

use App\Models\Uma;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UmaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Uma::create([
            'año' => 2016,
            'diario' => 73.04,
            'mensual' => 73.04 * 30.4,
            'anual' => 73.04 * 30.4 * 12
        ]);

        Uma::create([
            'año' => 2017,
            'diario' => 75.49,
            'mensual' => 75.49 * 30.4,
            'anual' => 75.49 * 30.4 * 12
        ]);

        Uma::create([
            'año' => 2018,
            'diario' => 80.60,
            'mensual' => 80.60 * 30.4,
            'anual' => 80.60 * 30.4 * 12
        ]);

        Uma::create([
            'año' => 2019,
            'diario' => 84.49,
            'mensual' => 84.49 * 30.4,
            'anual' => 84.49 * 30.4 * 12
        ]);

        Uma::create([
            'año' => 2020,
            'diario' => 86.88,
            'mensual' => 86.88 * 30.4,
            'anual' => 86.88 * 30.4 * 12
        ]);

        Uma::create([
            'año' => 2021,
            'diario' => 89.62,
            'mensual' => 89.62 * 30.4,
            'anual' => 89.62 * 30.4 * 12
        ]);

        Uma::create([
            'año' => 2022,
            'diario' => 96.22,
            'mensual' => 96.22 * 30.4,
            'anual' => 96.22 * 30.4 * 12
        ]);

        Uma::create([
            'año' => 2023,
            'diario' => 103.74,
            'mensual' => 103.74 * 30.4,
            'anual' => 103.74 * 30.4 * 12
        ]);

    }
}
