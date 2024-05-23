<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ModelHasRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        \DB::table('model_has_roles')->delete();

        \DB::table('model_has_roles')->insert(array (
            0 =>
            array (
                'role_id' => 1,
                'model_type' => 'App\\Models\\User',
                'model_id' => 1,
            ),
            1 =>
            array (
                'role_id' => 1,
                'model_type' => 'App\\Models\\User',
                'model_id' => 2,
            ),
            2 =>
            array (
                'role_id' => 1,
                'model_type' => 'App\\Models\\User',
                'model_id' => 8,
            ),
            3 =>
            array (
                'role_id' => 1,
                'model_type' => 'App\\Models\\User',
                'model_id' => 29,
            ),
            4 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 4,
            ),
            5 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 9,
            ),
            6 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 11,
            ),
            7 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 12,
            ),
            8 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 13,
            ),
            9 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 19,
            ),
            10 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 20,
            ),
            11 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 21,
            ),
            12 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 23,
            ),
            13 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 37,
            ),
            14 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 38,
            ),
            15 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 53,
            ),
            16 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 54,
            ),
            17 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 55,
            ),
            18 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 58,
            ),
            19 =>
            array (
                'role_id' => 4,
                'model_type' => 'App\\Models\\User',
                'model_id' => 14,
            ),
            20 =>
            array (
                'role_id' => 4,
                'model_type' => 'App\\Models\\User',
                'model_id' => 18,
            ),
            21 =>
            array (
                'role_id' => 4,
                'model_type' => 'App\\Models\\User',
                'model_id' => 62,
            ),
            22 =>
            array (
                'role_id' => 4,
                'model_type' => 'App\\Models\\User',
                'model_id' => 63,
            ),
            23 =>
            array (
                'role_id' => 4,
                'model_type' => 'App\\Models\\User',
                'model_id' => 65,
            ),
            24 =>
            array (
                'role_id' => 4,
                'model_type' => 'App\\Models\\User',
                'model_id' => 66,
            ),
            25 =>
            array (
                'role_id' => 5,
                'model_type' => 'App\\Models\\User',
                'model_id' => 16,
            ),
            26 =>
            array (
                'role_id' => 5,
                'model_type' => 'App\\Models\\User',
                'model_id' => 30,
            ),
            27 =>
            array (
                'role_id' => 5,
                'model_type' => 'App\\Models\\User',
                'model_id' => 31,
            ),
            28 =>
            array (
                'role_id' => 5,
                'model_type' => 'App\\Models\\User',
                'model_id' => 56,
            ),
            29 =>
            array (
                'role_id' => 5,
                'model_type' => 'App\\Models\\User',
                'model_id' => 57,
            ),
            30 =>
            array (
                'role_id' => 6,
                'model_type' => 'App\\Models\\User',
                'model_id' => 6,
            ),
            31 =>
            array (
                'role_id' => 6,
                'model_type' => 'App\\Models\\User',
                'model_id' => 22,
            ),
            32 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 5,
            ),
            33 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 7,
            ),
            34 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 10,
            ),
            35 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 15,
            ),
            36 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 24,
            ),
            37 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 25,
            ),
            38 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 26,
            ),
            39 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 27,
            ),
            40 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 28,
            ),
            41 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 32,
            ),
            42 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 33,
            ),
            43 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 34,
            ),
            44 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 35,
            ),
            45 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 36,
            ),
            46 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 39,
            ),
            47 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 40,
            ),
            48 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 41,
            ),
            49 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 42,
            ),
            50 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 43,
            ),
            51 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 44,
            ),
            52 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 45,
            ),
            53 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 46,
            ),
            54 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 47,
            ),
            55 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 48,
            ),
            56 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 49,
            ),
            57 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 50,
            ),
            58 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 59,
            ),
            59 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 60,
            ),
            60 =>
            array (
                'role_id' => 7,
                'model_type' => 'App\\Models\\User',
                'model_id' => 61,
            ),
            61 =>
            array (
                'role_id' => 8,
                'model_type' => 'App\\Models\\User',
                'model_id' => 64,
            ),
            62 =>
            array (
                'role_id' => 8,
                'model_type' => 'App\\Models\\User',
                'model_id' => 67,
            ),
        ));

        Schema::enableForeignKeyConstraints();
    }
}
