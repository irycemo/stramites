<?php

namespace App\Exports;

use App\Models\Tramite;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class TramiteExport implements WithProperties, WithDrawings, ShouldAutoSize, WithEvents, WithCustomStartCell, WithColumnWidths, WithHeadings, WithMapping, FromQuery
{

    public $servicio_id;
    public $ubicacion;
    public $usuario_id;
    public $tipo_servicio;
    public $solicitante;
    public $estado;
    public $fecha1;
    public $fecha2;


    public function __construct($estado, $ubicacion, $servicio, $usuario, $tipo_servicio, $solicitante, $fecha1, $fecha2)
    {
        $this->servicio_id = $servicio;
        $this->ubicacion = $ubicacion;
        $this->usuario_id = $usuario;
        $this->tipo_servicio = $tipo_servicio;
        $this->solicitante = $solicitante;
        $this->estado = $estado;
        $this->fecha1 = $fecha1;
        $this->fecha2 = $fecha2;

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Tramite::with('servicio:id,nombre', 'creadoPor:id,name,ubicacion', 'actualizadoPor:id,name')
                        ->when(isset($this->servicio_id) && $this->servicio_id != "", function($q){
                            return $q->where('id_servicio', $this->servicio_id);
                        })
                        ->when(isset($this->usuario_id) && $this->usuario_id != "", function($q){
                            return $q->where('creado_por', $this->usuario_id);
                        })
                        ->when(isset($this->estado) && $this->estado != "", function($q){
                            return $q->where('estado', $this->estado);
                        })
                        ->when(isset($this->tipo_servicio) && $this->tipo_servicio != "", function($q){
                            return $q->where('tipo_servicio', $this->tipo_servicio);
                        })
                        ->when(isset($this->solicitante) && $this->solicitante != "", function($q){
                            return $q->where('solicitante', $this->solicitante);
                        })
                        ->when(isset($this->ubicacion) && $this->ubicacion != "", function($q){
                            return $q->whereHas('creadoPor', function($q){
                                $q->where('ubicacion', $this->ubicacion);
                            });
                        })
                        ->whereBetween('created_at', [$this->fecha1 . ' 00:00:00', $this->fecha2 . ' 23:59:59']);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(storage_path('app/public/img/logo2.png'));
        $drawing->setHeight(90);
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function headings(): array
    {
        return [
            'Número de control',
            'Estado',
            'Servicio',
            'Solicitante',
            'Folio real',
            'Tomo',
            'Registro',
            'Monto',
            'Tipo de servicio',
            'Número de oficio',
            'Tomo gravamen',
            'Registro gravamen',
            'Distrito',
            'Sección',
            'Cantidad',
            'Número de inmuebles',
            'Número de escritura',
            'Notaria',
            'Valor de la propiedad',
            'Fecha de entrega',
            'Fecha de pago',
            'Documento de pago',
            'Linea de captura',
            'Movimiento registral',
            'Observaciones',
            'Ubicación',
            'Registrado por',
            'Registrado en',
            'Actualizado por',
            'Actualizado en',
        ];
    }

    public function map($tramite): array
    {
        return [
            $tramite->año. '-' .$tramite->numero_control. '-' .$tramite->usuario,
            $tramite->estado,
            $tramite->servicio->nombre,
            $tramite->solicitante . ' / ' . $tramite->nombre_solicitante,
            $tramite->folio_real ? $tramite->folio_real : 'N/A',
            $tramite->tomo ? $tramite->tomo : 'N/A',
            $tramite->registro ? $tramite->registro  : 'N/A',
            $tramite->monto,
            $tramite->tipo_servicio,
            $tramite->numero_oficio ? $tramite->numero_oficio : 'N/A',
            $tramite->tomo_gravamen ? $tramite->tomo_gravamen : 'N/A',
            $tramite->registro_gravamen ? $tramite->registro_gravamen : 'N/A',
            $tramite->distrito,
            $tramite->seccion,
            $tramite->cantidad ?? 'N/A',
            $tramite->numero_inmuebles ? $tramite->numero_inmuebles : 'N/A',
            $tramite->numero_escritura ? $tramite->numero_escritura : 'N/A',
            $tramite->numero_notaria ? $tramite->numero_notaria .  ' - ' . $tramite->nombre_notario : 'N/A',
            $tramite->valor_propiedad ? $tramite->valor_propiedad : 'N/A',
            $tramite->fecha_entrega ?? 'N/A',
            $tramite->fecha_pago ?? 'N/A',
            $tramite->documento_de_pago ? $tramite->documento_de_pago : 'N/A',
            $tramite->linea_de_captura,
            $tramite->movimiento_registral ?? 'N/A',
            $tramite->observaciones ? $tramite->observaciones : 'N/A',
            $tramite->creadoPor->ubicacion,
            $tramite->creadoPor->name,
            $tramite->created_at,
            $tramite->actualizadoPor->name ?? 'N/A',
            $tramite->updated_at,
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => auth()->user()->name,
            'title'          => 'Reporte de Faltas (Sistema de Gestión Personal)',
            'company'        => 'Instituto Registral Y Catastral Del Estado De Michoacán De Ocampo',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->mergeCells('A1:Z1');
                $event->sheet->setCellValue('A1', "Instituto Registral Y Catastral Del Estado De Michoacán De Ocampo\nReporte de trámites (Sistema Trámites)\n" . now()->format('d-m-Y'));
                $event->sheet->getStyle('A1')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('A1:Z1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 13
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);
                $event->sheet->getRowDimension('1')->setRowHeight(90);
                $event->sheet->getStyle('A2:Z2')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ]
                    ]
                );
            },
        ];
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function columnWidths(): array
    {
        return [
            'E' => 20,
            'F' => 20,

        ];
    }

}
