<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriaServiciosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categoria_servicios')->delete();
        
        \DB::table('categoria_servicios')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Certificaciones',
                'concepto' => '20501',
                'seccion' => '0',
                'creado_por' => NULL,
                'actualizado_por' => NULL,
                'created_at' => '2024-08-14 14:59:47',
                'updated_at' => '2024-08-14 14:59:47',
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'Comercio Certificaciones',
                'concepto' => '20507',
                'seccion' => '6',
                'creado_por' => NULL,
                'actualizado_por' => NULL,
                'created_at' => '2024-08-14 14:59:47',
                'updated_at' => '2024-08-14 14:59:47',
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'Comercio Inscripciones',
                'concepto' => '20507',
                'seccion' => '6',
                'creado_por' => NULL,
                'actualizado_por' => NULL,
                'created_at' => '2024-08-14 14:59:47',
                'updated_at' => '2024-08-14 14:59:47',
            ),
            3 => 
            array (
                'id' => 4,
                'nombre' => 'Inscripciones - Propiedad',
                'concepto' => '20502',
                'seccion' => '1',
                'creado_por' => NULL,
                'actualizado_por' => NULL,
                'created_at' => '2024-08-14 14:59:47',
                'updated_at' => '2024-08-14 14:59:47',
            ),
            4 => 
            array (
                'id' => 5,
                'nombre' => 'Inscripciones - Gravamenes',
                'concepto' => '20510',
                'seccion' => '2',
                'creado_por' => NULL,
                'actualizado_por' => NULL,
                'created_at' => '2024-08-14 14:59:47',
                'updated_at' => '2024-08-14 14:59:47',
            ),
            5 => 
            array (
                'id' => 6,
                'nombre' => 'Cancelación - Gravamenes',
                'concepto' => '20510',
                'seccion' => '2',
                'creado_por' => NULL,
                'actualizado_por' => NULL,
                'created_at' => '2024-08-14 14:59:47',
                'updated_at' => '2024-08-14 14:59:47',
            ),
            6 => 
            array (
                'id' => 7,
                'nombre' => 'Varios, Sentencias, Arrendamientos, Avisos Preventivos',
                'concepto' => '20505',
                'seccion' => '5',
                'creado_por' => NULL,
                'actualizado_por' => 1,
                'created_at' => '2024-08-14 14:59:47',
                'updated_at' => '2024-08-15 09:19:10',
            ),
            7 => 
            array (
                'id' => 8,
                'nombre' => 'Sentencias',
                'concepto' => '4',
                'seccion' => '4',
                'creado_por' => 1,
                'actualizado_por' => NULL,
                'created_at' => '2024-08-15 09:18:53',
                'updated_at' => '2024-08-15 09:18:53',
            ),
        ));
        
        
    }
}