<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{
    use HasFactory;

    protected $fillable = ['usuario_idusuario','novela_idnovela','puntuacion',];

    public $timestamps = false;
}
