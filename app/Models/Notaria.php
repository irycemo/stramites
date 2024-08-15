<?php

namespace App\Models;

use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notaria extends Model
{

    use HasFactory;
    use ModelosTrait;

    protected $fillable = ['numero', 'notario', 'email', 'rfc', 'creado_por', 'actualizado_por'];

}
