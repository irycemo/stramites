<?php

namespace App\Models;

use App\Traits\ModelosTrait;
use App\Models\CategoriaServicio;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servicio extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'nombre',
        'estado',
        'tipo',
        'umas',
        'ordinario',
        'urgente',
        'extra_urgente',
        'categoria_servicio_id',
        'actualizado_por',
        'creado_por',
        'operacion_principal',
        'operacion_parcial',
        'material',
        'clave_ingreso',
        'costo_sap'
    ];

    public function categoria(){
        return $this->belongsTo(CategoriaServicio::class, 'categoria_servicio_id');
    }

    public function setUmasAttribute($value){
        $this->attributes['umas'] = (empty($value) ? 0 : $value);
    }

    public function setUrgenteAttribute($value){
        $this->attributes['urgente'] = (empty($value) ? 0 : $value);
    }

    public function setExtraUrgenteAttribute($value){
        $this->attributes['extra_urgente'] = (empty($value) ? 0 : $value);
    }

}
