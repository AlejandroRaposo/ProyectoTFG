<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novela extends Model
{
    use HasFactory;

    protected $primaryKey = 'idnovela';

    protected $fillable = ['nombre_novela','descripcion','portada','edad_min','usuario_idusuario',];



public function autor()
{
    return $this->belongsTo(User::class, 'usuario_idusuario', 'id');
}

public function capitulos() {
    return $this->hasMany(capitulo::class);
}

public function generos() {
    return $this->hasMany(novela_has_genero::class);
}

public function fav() {
    return $this->hasMany(Lista_has_novela::class);
}
}