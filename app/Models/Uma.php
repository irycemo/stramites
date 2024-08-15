<?php

namespace App\Models;

use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Uma extends Model
{

    use HasFactory;
    use ModelosTrait;

    protected $fillable = ['diario', 'mensual', 'anual', 'año', 'creado_por', 'actualizado_por'];

}
