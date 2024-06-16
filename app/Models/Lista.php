<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_lista','usuario_idusuario','privado'];


    public function listadoFavoritos() {
        return $this->hasMany(Lista_has_novela::class, 'lista_idlista');
    }

    public function lista()
{
    return $this->belongsTo(User::class, 'usuario_idusuario', 'id');
}
}
