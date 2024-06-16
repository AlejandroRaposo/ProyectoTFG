<?php

namespace App\Http\Controllers;

use App\Models\Novela;
use App\models\User;
use App\models\Genero;
use App\models\Capitulo;
use App\models\Valoracion;
use App\models\Comentario;
use App\models\lista;
use App\models\Lista_has_novela;
use Illuminate\Support\Facades\Gate;
use App\models\novela_has_genero;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class novelaController extends Controller
{
    public function index()
    {

        // Cargamos las novelas dependiendo de si el usuario está conectado y es mayor de edad
        
        if (gate::allows('comprobar-edad', Auth::user())){
            $lista_novelas = novela::paginate(10);
            $lista_ultimas_novelas = novela::latest('created_at')->limit(5)->get();
            $novelas_cap = novela::join('capitulos','novelas.idnovela', '=' ,'capitulos.novela_idnovela')->join('novela_has_generos','novelas.idnovela','=','novela_has_generos.novela_idnovela')->join('generos','novela_has_generos.genero_idgenero','=','generos.idgenero')->select('nombre_novela','idnovela','edad_min','created_at','fecha_creacion','estado','idcapitulo','nombre_capitulo','link_capitulo','nombre_genero')->orderBy('fecha_creacion', 'desc')->get();
            $idnovelaAzar = novela::pluck('idnovela');

        }

        if (! gate::allows('comprobar-edad',Auth::user())){
            $lista_novelas = novela::whereNull('edad_min')->paginate(10);
            $lista_ultimas_novelas = novela::latest('created_at')->whereNull('edad_min')->limit(5)->get();
            $novelas_cap = novela::join('capitulos','novelas.idnovela', '=' ,'capitulos.novela_idnovela')->join('novela_has_generos','novelas.idnovela','=','novela_has_generos.novela_idnovela')->join('generos','novela_has_generos.genero_idgenero','=','generos.idgenero')->select('nombre_novela','idnovela','edad_min','created_at','fecha_creacion','estado','idcapitulo','nombre_capitulo','link_capitulo','nombre_genero')->orderBy('fecha_creacion', 'desc')->whereNull('edad_min')->get();
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');

        }


      
        

        // Cargamos el resto de datos necesarios
       
        $usuarios = User::with('autores')->pluck('name','id');
         $generos = novela_has_genero::with('generos')->whereIn('novela_idnovela', (Novela::whereIn('usuario_idusuario',$usuarios->pluck('id'))->pluck('idnovela')))->get();
        $capitulos = capitulo::select('*');

       
        
        // Vuelve a la vista y envia los datos compactados
        return view('novelas.index',compact('lista_novelas','lista_ultimas_novelas','usuarios','novelas_cap','generos','capitulos','novelas_cap','idnovelaAzar'));
    }

    public function create(){
        if (! gate::allows('comprobar-edad',Auth::user())){
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');

       }
       if (gate::allows('comprobar-edad', Auth::user())){
          $idnovelaAzar = novela::pluck('idnovela');

       }
        $generos = Genero::all();
        return view('novelas.create',compact('generos','idnovelaAzar'));
    }

    /**
     * Esta es la funcion que vamos a llamar cuando queramos añadir una novela que
     * recibe los datos de un formulario y despues redirecciona al indice.
     */
    public function store(Request $request)
    {

        // Validamos los datos
        $request->validate([
            'nombre_novela'=> 'required|max:200|min:5|unique:novelas,nombre_novela',
            'descripcion'=> 'required|max:245|min:2',
        ]);

        

       novela::create([
            'nombre_novela' => $request->nombre_novela,
            'descripcion' => $request->descripcion,
            'edad_min' => $request->edad_min,
            'usuario_idusuario' => $request->usuario_idusuario,

        ]);

        $idNovela = novela::where('nombre_novela', $request->nombre_novela)->get();


        Storage::disk('public')->makeDirectory('novelas/'.$idNovela[0]->idnovela);
        Storage::disk('public')->makeDirectory(('novelas/'.$idNovela[0]->idnovela.'/portada'));

        if (isset($request->portada)) {

        
            novela::where('idnovela', $idNovela[0]->idnovela)->update(['portada' => $idNovela[0]->idnovela.'.'.$request->file('portada')->extension()]);
            
            $path =  Storage::disk('public')->putFileAs(('novelas/'.$idNovela[0]->idnovela.'/portada'), $request->file('portada'), $idNovela[0]->idnovela.'.'.$request->file('portada')->extension());
        }

        Storage::disk('public')->makeDirectory('novelas/'.$idNovela[0]->idnovela.'/capitulos');

        $generos = $request->genero;

        
        foreach ($generos as $genero) {
            novela_has_genero::insert([
                'novela_idnovela' => $idNovela[0]->idnovela,
                'genero_idgenero' => $genero,
            ]);
        }



    
        return redirect()->route('novelas.index')->with('success','novela añadida');
    }

    /**
     * 
     * */
    public function show(string $id)
    {
       

        $novela =  novela::where('idnovela', $id)->get()[0];

        $usuarios = [ 'name' => User::where('id',$novela->usuario_idusuario)->pluck('name')[0],
        'link_imagen' => User::where('id',$novela->usuario_idusuario)->pluck('link_imagen')[0],
        'created_at' => User::where('id',$novela->usuario_idusuario)->pluck('created_at')[0],];

        $listas = Lista::join('lista_has_novelas','listas.idlista','lista_has_novelas.lista_idlista')->where('novela_idnovela',$id)->get();

        $puntuacion = Valoracion::where('novela_idnovela',$novela->idnovela)->get();
        $capitulos = capitulo::where('novela_idnovela',$novela->idnovela)->orderBy('fecha_creacion','desc')->paginate(10);
        $generos = genero::whereIn('idgenero',novela_has_genero::where('novela_idnovela',$novela->idnovela)->pluck('genero_idgenero'))->get();

        
        
       return view('novelas.show', compact('novela','usuarios','puntuacion','capitulos','generos','listas'));
    }

    public function lector(string $idnovela, string $idcapitulo) {
        if (! gate::allows('comprobar-edad',Auth::user())){
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');

       }
       if (gate::allows('comprobar-edad', Auth::user())){
          $idnovelaAzar = novela::pluck('idnovela');

       }

        $novela =  novela::where('idnovela', $idnovela)->get()[0];

        $usuarios = [ 'name' => User::where('id',$novela->usuario_idusuario)->pluck('name')[0],
        'link_imagen' => User::where('id',$novela->usuario_idusuario)->pluck('link_imagen')[0],
        'created_at' => User::where('id',$novela->usuario_idusuario)->pluck('created_at')[0],];

        $comentarios = comentario::with('comentarioUsuario')->where('capitulo_idnovela', $idcapitulo)->paginate(10);

        $capitulo = capitulo::where('idcapitulo', $idcapitulo)->get()[0];

        $idcapitulos = $novela->capitulos->pluck('idcapitulo')->toArray();
        

        $contenidoCapitulo = Storage::disk('public')->get('novelas/'.$novela->idnovela.'/'.'capitulos/'.$capitulo->idcapitulo.'/'.$capitulo->link_capitulo);;
       
        return view('capitulos.show', compact('novela','usuarios','capitulo','contenidoCapitulo','comentarios','idcapitulos','idnovelaAzar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'nombre'=> 'required|max:200|min:5',
            'descripcion'=> 'required|max:200|min:2',
            'categoria' => 'required|max:200|min:2',
            'edad_minima' => 'required|min:1|max:120',
        ]);
        
        // Cargamos la novela a modificar
        $novela =  novela::where('idnovela', $id)->get()[0];

        // Modifica la novela en bd
        $novela->update($request->all());
        

        return redirect()->route('novelas.index')->with('success','novela modificada');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $novela =  novela::where('idnovela', $id)->get()[0];

        Storage::disk('public')->deleteDirectory('novelas/'.$novela->idnovela);

        $novela->delete();

        return redirect()->route('novelas.index')->with('success','novela eliminada');
    }

    

      public function edit($id){
        if (! gate::allows('comprobar-edad',Auth::user())){
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');

       }
       if (gate::allows('comprobar-edad', Auth::user())){
          $idnovelaAzar = novela::pluck('idnovela');

       }
        $novela =  novela::where('idnovela', $id)->get()[0];
        return view('novelas.edit',compact('novela'));
    }


    public function showNovelasAutor(string $id)
    {
        if (! gate::allows('comprobar-edad',Auth::user())){
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');

       }
       if (gate::allows('comprobar-edad', Auth::user())){
          $idnovelaAzar = novela::pluck('idnovela');

       }
        $lista_novelas =  novela::where("autores_autor_id",$id)->paginate(10);
        $usuarios = User::with('autores')->get();
        return view('novelas.index',compact('lista_novelas','usuarios'));
    }

    public function createCapitulo(string $id) {
        if (! gate::allows('comprobar-edad',Auth::user())){
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');

       }
       if (gate::allows('comprobar-edad', Auth::user())){
          $idnovelaAzar = novela::pluck('idnovela');

       }
        $novela =  novela::where('idnovela', $id)->get()[0];

        return view('capitulos.create', compact('novela','idnovelaAzar'));

       
    }

    public function storeCapitulo(Request $request,string $id) {


      
        capitulo::create([
            'nombre_capitulo' =>  $_POST['name']  ,
            'novela_idnovela' => $id ,
            'estado' => 1

        ]);

        $capitulo = capitulo::where('novela_idnovela', $id)->where('nombre_capitulo', $_POST['name'])->orderBy('fecha_creacion', 'desc')->limit(1)->get()[0];


        if (isset($_POST['ocultar'])) {
            capitulo::where('idcapitulo', $capitulo->idcapitulo)->update(['estado', $_POST['ocultar']]);
        }

        $contenido = $_POST['contenido'];
       

        Storage::disk('public')->makeDirectory('novelas/'.$id.'/capitulos'.'/'.$capitulo->idcapitulo);


        Storage::disk('public')->put('novelas/'.$id.'/capitulos'.'/'.$capitulo->idcapitulo.'/'.$capitulo->idcapitulo, $contenido);
        
        capitulo::where('idcapitulo', $capitulo->idcapitulo)->update(['link_capitulo' => $capitulo->idcapitulo]);

        

        return redirect()->route('novela.show', $id);
    }

    public function enviarComentario() {

        comentario::create([
            'capitulo_idnovela' => $_POST['idcapitulo']  ,
            'usuario_idusuario' => $_POST['usuario_idusuario'] ,
            'comentario' => $_POST['comentario']  ,

            'idrespuesta' => $_POST['idrespuesta']
        ]);

       return novelaController::lector($_POST['idnovela'],$_POST['idcapitulo']);
    }

    public function borrarComentario(Request $request) {

        $idcomentario = $request->idcomentario;
        $comentarioBorrar = comentario::where('idcomentario',$idcomentario)->delete();


        return novelaController::lector($_POST['idnovela'],$_POST['idcapitulo']);
    }

    public function editarComentario(Request $request){

        $idComentario = $request->idcomentario;

        $comentarioEditar = comentario::where('idcomentario',$idComentario)->update(['comentario' => $_POST['comentario']]);

        

       return novelaController::lector($_POST['idnovela'],$_POST['idcapitulo']);
    }

    public function valorar(){

        valoracion::insert([
            'novela_idnovela' => $_POST['idnovela']  ,
            'usuario_idusuario' => $_POST['idusuario'] ,
            'puntuacion' => $_POST['Val']  ,

           
        ]);

        return redirect()->route('novela.show', $_POST('idnovela'));

    }

    public function cambiarValoracion(){

        valoracion::upsert([
            'novela_idnovela' => $_POST['idnovela']  ,
            'usuario_idusuario' => $_POST['idusuario'] ,
            'puntuacion' => $_POST['Val']  ,

           
        ]);

        return redirect()->route('novela.show', $_POST('idnovela'));

    }

    public function addfavorito(){

        lista_has_novela::insert([
            'novela_idnovela' => $_POST['idnovela']  ,
            'lista_idlista' => $_POST['idlista'] ,
            
           
        ]);

        return redirect()->route('novela.show', $_POST['idnovela']);

    }

}