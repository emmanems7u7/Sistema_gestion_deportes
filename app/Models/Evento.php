<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{

    protected $fillable = [
        'nombre',
        'competencia_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'ubicacion',
        'geolocalizacion',
        'accion_usuario',
    ];

    public function competencia()
    {
        return $this->belongsTo(Competencia::class);
    }
}
