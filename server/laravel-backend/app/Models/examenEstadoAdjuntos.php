<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class examenEstadoAdjuntos extends Model
{
    protected $table = "examen_estado_adjuntos";
    protected $fillable = ['adjunto','id_examen_estados'];
    use HasFactory;
}
