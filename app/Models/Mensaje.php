<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;
    protected $fillable = ['mensaje','idusuario_origen','idusuario_objetivo','idrespuesta'];



public function mensajeRecibido()
{
    return $this->belongsTo(User::class, 'idusuario_objetivo', 'id');
}

public function mensajeEnviado()
{
    return $this->belongsTo(User::class, 'idusuario_origen', 'id');
}

const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_ult_actualizacion';
}

