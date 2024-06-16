<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capitulo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_capitulo','estado','novela_idnovela','link_capitulo',];


    public function novela()
{
    return $this->belongsTo(Novela::class, 'novela_idnovela', 'idnovela');
}

public function comentarios() {
    return $this->hasMany(comentario::class, 'capitulo_idnovela');
}

const CREATED_AT = 'fecha_creacion';
const UPDATED_AT = 'fecha_edicion';
const id = 'idcapitulo';
}
