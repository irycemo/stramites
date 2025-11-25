<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{

    use HasFactory;

    protected $fillable = ['fileable_id', 'fileable_type', 'url', 'descripcion'];

    public function fileable(){
        return $this->morphTo();
    }

    public function getUrl(){

        if(app()->isProduction()){

            return Storage::disk('s3')->temporaryUrl(config('services.ses.ruta_caratulas') . $this->url, now()->addMinutes(10));

        }else{

            return Storage::disk('s3')->url('tramites/' . $this->url);

        }

    }

}
