<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Map extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'direccion', 'descripcion', 'latitud', 'longitud', 'accion_usuario'];
}
