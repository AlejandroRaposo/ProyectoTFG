<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista_has_novela extends Model
{
    use HasFactory;

    
    protected $fillable = ['novela_idnovela','lista_idlista',];


    public function novelas()
{
    return $this->belongsTo(novela::class, 'novela_idnovela', 'idnovela');
}

public function listas()
{
    return $this->belongsTo(Lista::class, 'lista_idlista', 'idlista');
}

public $timestamps = false;
}
