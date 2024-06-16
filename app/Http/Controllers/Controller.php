<?php

namespace App\Http\Controllers;
use App\Models\Novela;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;



abstract class Controller
{
    public function inicio()
    {
         // Cargamos todas los usuarios
         $lista_novelas = Novela::all();
         // Vuelve a la vista y envia los datos compactados
         return view('index',compact(''));
    }}


    if (! gate::allows('comprobar-edad',Auth::user())){
        $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');

   }
   if (gate::allows('comprobar-edad', Auth::user())){
      $idnovelaAzar = novela::pluck('idnovela');


}
