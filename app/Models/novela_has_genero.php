<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class novela_has_genero extends Model
{
    use HasFactory;

    protected $fillable = ['novela_idnovela','genero_idgenero',];
    

    public function crear() {
        
    }


public function novelas()
    {
        return $this->belongsTo(novela::class,'novela_idnovela','idnovela');
    }


    public function generos()
    {
        return $this->belongsTo(genero::class,'genero_idgenero','idgenero');
    }
}