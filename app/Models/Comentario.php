<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = ['capitulo_idnovela','usuario_idusuario','comentario','idrespuesta',];


public function comentarioNovela()
{
    return $this->belongsTo(capitulo::class, 'capitulo_idnovela', 'idcapitulo');
}

public function comentarioUsuario()
{
    return $this->belongsTo(User::class, 'usuario_idusuario', 'id');
}



    const CREATED_AT = 'creado';
    const UPDATED_AT = 'actualizado';
}

