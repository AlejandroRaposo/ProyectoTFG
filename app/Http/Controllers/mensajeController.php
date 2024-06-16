<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mensaje;
use App\Models\Novela;



class mensajeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

public function mensajesUsuario()
{
    $mensajes = User::with('mensajesRecibidos')->paginate(10);
}


public function enviarMensaje(string $id, Request $request){

    mensaje::create([
        'mensaje' => $request->mensaje   ,
        'idusuario_origen' => $request->idusuario_origen,
        'idusuario_objetivo' => $id,
        'idrespuesta' => $_POST['idrespuesta']
        
    ]);

    $usuario =  User::find($id);
        $mensajes = Mensaje::with('mensajeRecibido')->where("idusuario_objetivo",$id)->paginate(10, ['*'],'mensajes');
        $novelasUsuario = Novela::with('autor')->where('usuario_idusuario',$id)->paginate(10 , ['*'],'novelas');

        return view('profile.show', compact('usuario','mensajes','novelasUsuario',));
}


public function borrarMensaje(string $id,Request $request) {

    $idmensaje = $request->idmensaje;
    $mensaje = Mensaje::where('idmensajes',$idmensaje)->delete();
    
    

    $usuario =  User::find($id);
    $mensajes = Mensaje::with('mensajeRecibido')->where("idusuario_objetivo",$id)->paginate(10, ['*'],'mensajes');
    $novelasUsuario = Novela::with('autor')->where('usuario_idusuario',$id)->paginate(10 , ['*'],'novelas');


    return view('profile.show', compact('usuario','mensajes','novelasUsuario'));
}

public function editarMensaje(Request $request){

    $idmensaje = $request->idmensaje;

    $mensajeEditar = mensaje::where('idmensajes',$idmensaje)->update(['mensaje' => $_POST['mensaje']]);

    
    $usuario =  User::find($_POST['idusuario_destino']);
    $mensajes = Mensaje::with('mensajeRecibido')->where("idusuario_objetivo",$_POST['idusuario_destino'])->paginate(10, ['*'],'mensajes');
    $novelasUsuario = Novela::with('autor')->where('usuario_idusuario',$_POST['idusuario_destino'])->paginate(10 , ['*'],'novelas');

    return view('profile.show', compact('usuario','mensajes','novelasUsuario',));
}

}
