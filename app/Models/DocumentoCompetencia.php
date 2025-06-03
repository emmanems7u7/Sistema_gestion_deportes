<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoCompetencia extends Model
{
    protected $fillable = [
        'competencia_id',
        'documento_nombre',
        'documento_ruta',
        'accion_usuario',
    ];

    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'competencia_id');
    }
}
