<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competencia extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'codigo_categoria',
        'accion_usuario',
    ];

    public function documentos()
    {
        return $this->hasMany(DocumentoCompetencia::class, 'competencia_id');
    }
    public function eventos()
    {
        return $this->hasMany(Evento::class, 'competencia_id');
    }
}
