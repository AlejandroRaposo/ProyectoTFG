<?php

namespace App\Http\Controllers;

use App\Models\Novela;
use App\Models\Review;
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

        if (gate::allows('comprobar-edad', Auth::user())) {
            $lista_novelas = novela::paginate(10);
            $lista_ultimas_novelas = novela::latest('created_at')->limit(5)->get();
            $novelas_cap = novela::join('capitulos', 'novelas.idnovela', '=', 'capitulos.novela_idnovela')->join('novela_has_generos', 'novelas.idnovela', '=', 'novela_has_generos.novela_idnovela')->join('generos', 'novela_has_generos.genero_idgenero', '=', 'generos.idgenero')->select('nombre_novela', 'idnovela', 'edad_min', 'created_at', 'fecha_creacion', 'estado', 'idcapitulo', 'nombre_capitulo', 'link_capitulo', 'nombre_genero')->orderBy('fecha_creacion', 'desc')->get();
            $idnovelaAzar = novela::pluck('idnovela');
        }

        if (!gate::allows('comprobar-edad', Auth::user())) {
            $lista_novelas = novela::whereNull('edad_min')->paginate(10);
            $lista_ultimas_novelas = novela::latest('created_at')->whereNull('edad_min')->limit(5)->get();
            $novelas_cap = novela::join('capitulos', 'novelas.idnovela', '=', 'capitulos.novela_idnovela')->join('novela_has_generos', 'novelas.idnovela', '=', 'novela_has_generos.novela_idnovela')->join('generos', 'novela_has_generos.genero_idgenero', '=', 'generos.idgenero')->select('nombre_novela', 'idnovela', 'edad_min', 'created_at', 'fecha_creacion', 'estado', 'idcapitulo', 'nombre_capitulo', 'link_capitulo', 'nombre_genero')->orderBy('fecha_creacion', 'desc')->whereNull('edad_min')->get();
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');
        }


        $listas = Lista::join('lista_has_novelas', 'listas.idlista', 'lista_has_novelas.lista_idlista')->get();



        // Cargamos el resto de datos necesarios

        $usuarios = User::with('autores')->pluck('name', 'id');
        $generosNov = novela_has_genero::join('generos','novela_has_generos.genero_idgenero','generos.idgenero')->get();
        $capitulos = capitulo::select('*');



        // Vuelve a la vista y envia los datos compactados
        return view('novelas.index', compact('lista_novelas', 'lista_ultimas_novelas', 'usuarios', 'novelas_cap', 'generosNov', 'capitulos', 'novelas_cap', 'idnovelaAzar', 'listas'));
    }

    public function create()
    {
        if (!gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');
        }
        if (gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::pluck('idnovela');
        }
        $generos = Genero::all();

        

        return view('novelas.create', compact('generos', 'idnovelaAzar'));
    }

    /**
     * Esta es la funcion que vamos a llamar cuando queramos añadir una novela que
     * recibe los datos de un formulario y despues redirecciona al indice.
     */
    public function store(Request $request)
    {

        // Validamos los datos
        $request->validate([
            'nombre_novela' => 'required|max:200|min:5|unique:novelas,nombre_novela',
            'descripcion' => 'required|max:245|min:2',
            'genero' => 'required'
        ]);



        novela::create([
            'nombre_novela' => $request->nombre_novela,
            'descripcion' => $request->descripcion,
            'edad_min' => $request->edad_min,
            'usuario_idusuario' => $request->usuario_idusuario,

        ]);

        $idNovela = novela::where('nombre_novela', $request->nombre_novela)->get();


        Storage::makeDirectory('public/novelas/' . $idNovela[0]->idnovela);
        Storage::makeDirectory(('public/novelas/' . $idNovela[0]->idnovela . '/portada'));

        if (isset($request->portada)) {


            novela::where('idnovela', $idNovela[0]->idnovela)->update(['portada' => $idNovela[0]->idnovela . '.' . $request->file('portada')->extension()]);

            $path =  Storage::putFileAs(('public/novelas/' . $idNovela[0]->idnovela . '/portada'), $request->file('portada'), $idNovela[0]->idnovela . '.' . $request->file('portada')->extension());
        }

        Storage::makeDirectory('public/novelas/' . $idNovela[0]->idnovela . '/capitulos');

        $generos = $request->genero;


        foreach ($generos as $genero) {
            novela_has_genero::insert([
                'novela_idnovela' => $idNovela[0]->idnovela,
                'genero_idgenero' => $genero,
            ]);
        }




        return redirect()->route('novelas.index')->with('success', 'novela añadida');
    }

    /**
     * 
     * */
    public function show(string $id)
    {


        $novela =  novela::where('idnovela', $id)->get()[0];

        $usuarios = [
            'name' => User::where('id', $novela->usuario_idusuario)->pluck('name')[0],
            'link_imagen' => User::where('id', $novela->usuario_idusuario)->pluck('link_imagen')[0],
            'created_at' => User::where('id', $novela->usuario_idusuario)->pluck('created_at')[0],
        ];

        $listas = Lista::join('lista_has_novelas', 'listas.idlista', 'lista_has_novelas.lista_idlista')->get();

        $puntuacion = Valoracion::where('novela_idnovela', $novela->idnovela)->get();
        $capitulos = capitulo::where('novela_idnovela', $novela->idnovela)->orderBy('fecha_creacion', 'desc')->paginate(10);
        $generosNov = novela_has_genero::join('generos','novela_has_generos.genero_idgenero','generos.idgenero')->get();
        $reviews = review::where('novela_idnovela', $id)->paginate(10);


        return view('novelas.show', compact('novela', 'usuarios', 'puntuacion', 'capitulos', 'generosNov', 'listas', 'reviews'));
    }

    public function lector(string $idnovela, string $idcapitulo)
    {
        if (!gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');
        }
        if (gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::pluck('idnovela');
        }

        $novela =  novela::where('idnovela', $idnovela)->get()[0];

        $usuarios = [
            'name' => User::where('id', $novela->usuario_idusuario)->pluck('name')[0],
            'link_imagen' => User::where('id', $novela->usuario_idusuario)->pluck('link_imagen')[0],
            'created_at' => User::where('id', $novela->usuario_idusuario)->pluck('created_at')[0],
        ];

        $comentarios = comentario::with('comentarioUsuario')->where('capitulo_idnovela', $idcapitulo)->paginate(10);

        $capitulo = capitulo::where('idcapitulo', $idcapitulo)->get()[0];

        $idcapitulos = $novela->capitulos->pluck('idcapitulo')->toArray();


        $contenidoCapitulo = Storage::get('public/novelas/' . $novela->idnovela . '/' . 'capitulos/' . $idcapitulo . '/' . $idcapitulo);
        

        return view('capitulos.show', compact('novela', 'usuarios', 'capitulo', 'contenidoCapitulo', 'comentarios', 'idcapitulos', 'idnovelaAzar'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function updatePage(string $id)
    {
        if (!gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');
        }
        if (gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::pluck('idnovela');
        }
        $generos = Genero::all();

        // Cargamos la novela a modificar
        $novelaEditar =  novela::where('idnovela', $id)->get()[0];

        return view('novelas.updatePage',compact('novelaEditar','generos'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre_novela' => 'required|max:200|min:5',
            'descripcion' => 'required|max:200|min:2',
            'genero' => 'required',
            
        ]);

        // Cargamos la novela a modificar
        $novela =  novela::select('*')->where('idnovela', $_POST['idnovelaEditar']);

        // Modifica la novela en bd
        $novela->update([
            'nombre_novela' => $_POST['nombre_novela'],
            'descripcion' =>  $_POST['descripcion'],
            'usuario_idusuario' =>  $_POST['usuario_idusuario'],
            
        ]);

        if (isset($_POST['edad_min'])){
            $novela->update(['edad_min' => $_POST['edad_min']]);
        }
        novela_has_genero::where('novela_idnovela', $_POST['idnovelaEditar'])->delete();
        

        foreach ($_POST['genero'] as $genero) {

           

            novela_has_genero::insert([
                'novela_idnovela' => $_POST['idnovelaEditar'],
                'genero_idgenero' => $genero,
            ]);
        }

        if (isset($_POST['portada'])) {


            novela::where('idnovela', $_POST['idnovela'])->update(['portada' => $_POST['idnovela'] . '.' . $request->file('portada')->extension()]);

            $path =  Storage::putFileAs(('/public/novelas/' . $_POST['idnovela'] . '/portada'), $request->file('portada'), $_POST['idnovela'] . '.' . $request->file('portada')->extension());
        }




        return redirect()->route('novelas.index')->with('success', 'novela modificada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $novela =  novela::where('idnovela', $id)->get()[0];

        Storage::deleteDirectory('public/novelas/' . $novela->idnovela);

        $novela->delete();

        return redirect()->route('novelas.index')->with('success', 'novela eliminada');
    }



    public function edit($id)
    {
        if (!gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');
        }
        if (gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::pluck('idnovela');
        }
        $novela =  novela::where('idnovela', $id)->get()[0];
        return view('novelas.edit', compact('novela'));
    }


    public function showNovelasAutor(string $id)
    {
        if (!gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');
        }
        if (gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::pluck('idnovela');
        }
        $listas = Lista::join('lista_has_novelas', 'listas.idlista', 'lista_has_novelas.lista_idlista')->all();

        $lista_novelas =  novela::where("autores_autor_id", $id)->paginate(10);
        $usuarios = User::with('autores')->get();
        return view('novelas.index', compact('lista_novelas', 'usuarios', 'listas'));
    }

    public function createCapitulo(string $id)
    {
        if (!gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');
        }
        if (gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::pluck('idnovela');
        }
        $novela =  novela::where('idnovela', $id)->get()[0];

        return view('capitulos.create', compact('novela', 'idnovelaAzar'));
    }

    public function storeCapitulo(Request $request, string $id)
    {



        capitulo::create([
            'nombre_capitulo' =>  $_POST['name'],
            'novela_idnovela' => $id,
            'estado' => 1

        ]);

        $capitulo = capitulo::where('novela_idnovela', $id)->where('nombre_capitulo', $_POST['name'])->orderBy('fecha_creacion', 'desc')->limit(1)->get()[0];


        if (isset($_POST['ocultar'])) {
            capitulo::where('idcapitulo', $capitulo->idcapitulo)->update(['estado', $_POST['ocultar']]);
        }

        $contenido = $_POST['contenido'];


        Storage::makeDirectory('public/novelas/' . $id . '/capitulos' . '/' . $capitulo->idcapitulo);


        Storage::put('public/novelas/' . $id . '/capitulos' . '/' . $capitulo->idcapitulo . '/' . $capitulo->idcapitulo, $contenido);

        capitulo::where('idcapitulo', $capitulo->idcapitulo)->update(['link_capitulo' => $capitulo->idcapitulo]);



        return redirect()->route('novela.show', $id);
    }


    public function destroyCapitulo(string $id,string $id2)
    {
        $capitulo =  capitulo::where('idcapitulo', $id2)->delete();

        Storage::deleteDirectory('public/novelas/' . $id .'/capitulos'.'/'.$id2);

        

        return redirect()->route('novela.show',$id)->with('success', 'capitulo eliminada');
    }


    public function updateCapitulo(Request $request, string $id, string $id2)
    {
        $request->validate([
            'name' => 'required',
            
        ]);


       $capituloEdit = capitulo::where('idcapitulo',$id2)->upsert([
            'nombre_capitulo' =>  $_POST['name'],
            'novela_idnovela' => $id,
            'estado' => 1,
            'idcapitulo' => $id2

        ],['idcapitulo'],['nombre_capitulo']);

        


        if (isset($_POST['ocultar'])) {
            capitulo::where('idcapitulo', $$id2)->update(['estado', $_POST['ocultar']]);
        }

        $contenido = $_POST['contenido'];

        Storage::delete('public/novelas/' . $id . '/capitulos' . '/' . $id2 . '/' . $id2);

        Storage::put('public/novelas/' . $id . '/capitulos' . '/' . $id2 . '/' . $id2, $contenido);

        capitulo::where('idcapitulo', $id2)->update(['link_capitulo' => $id2]);



        return redirect()->route('novela.show', $id);
    }


    public function updPagCapitulo(string $id, string $id2)
    {
        if (!gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::whereNull('edad_min')->pluck('idnovela');
        }
        if (gate::allows('comprobar-edad', Auth::user())) {
            $idnovelaAzar = novela::pluck('idnovela');
        }


        $novelaEditar = novela::where('idnovela', $id)->get()[0];

        // Cargamos la novela a modificar
        $capituloEditar =  capitulo::where('idcapitulo', $id2)->get()[0];

        $contenidoCapitulo = Storage::get('public/novelas/' . $id . '/' . 'capitulos/' . $id2 . '/' . $capituloEditar->link_capitulo);

        


        return view('capitulos.updPagCapitulo',compact('novelaEditar','capituloEditar','contenidoCapitulo'));
    }


    public function enviarComentario()
    {

        comentario::create([
            'capitulo_idnovela' => $_POST['idcapitulo'],
            'usuario_idusuario' => $_POST['usuario_idusuario'],
            'comentario' => $_POST['comentario'],

            'idrespuesta' => $_POST['idrespuesta']
        ]);

        return novelaController::lector($_POST['idnovela'], $_POST['idcapitulo']);
    }

    public function borrarComentario(Request $request)
    {

        $idcomentario = $request->idcomentario;
        $comentarioBorrar = comentario::where('idcomentario', $idcomentario)->delete();


        return novelaController::lector($_POST['idnovela'], $_POST['idcapitulo']);
    }

    public function editarComentario(Request $request)
    {

        $idComentario = $request->idcomentario;

        $comentarioEditar = comentario::where('idcomentario', $idComentario)->update(['comentario' => $_POST['comentario']]);



        return novelaController::lector($_POST['idnovela'], $_POST['idcapitulo']);
    }

    public function valorar()
    {



        valoracion::insert([
            'novela_idnovela' => $_POST['idnovela'],
            'usuario_idusuario' => $_POST['idusuario'],
            'puntuacion' => $_POST['Val'],


        ]);

        return redirect()->route('novela.show', $_POST['idnovela']);
    }

    public function cambiarValoracion()
    {

        $valoracion = valoracion::where('usuario_idusuario', '=', $_POST['idusuario'])->where('novela_idnovela', '=', $_POST['idnovela'])->update(['puntuacion' => $_POST['Val']]);


        return redirect()->route('novela.show', $_POST['idnovela']);
    }

    public function addfavorito()
    {

        $idlistaFav = lista::where('usuario_idusuario',Auth::user()->id)->get();
        
        lista_has_novela::insert([
            'novela_idnovela' => $_POST['idnovelaFav'],
            'lista_idlista' => $idlistaFav->pluck('idlista')[0],


        ]);
        switch ($_POST['pagina']) {
            case 'showNov':
                return redirect()->route('novela.show', $_POST['idnovelaFav']);
                break;
            case 'fav':
                return redirect()->route('profile.favoritos', Auth::user()->id);
                break;
            case 'showPer':
                return redirect()->route('profile.show', Auth::user()->id);
                break;
            case 'misNov':
                return redirect()->route('profile.novelas', Auth::user()->id);
                break;
            case 'index':
                return redirect()->route('novelas.index');
                break;

            default:
                # code...
                break;
        }

    }

    public function borrarFavorito()
    {

        $favorito = lista::where('usuario_idusuario',$_POST['idlistaFav'])->delete();

        switch ($_POST['pagina']) {
            case 'showNov':
                return redirect()->route('novela.show', $_POST['idnovelaFav']);
                break;
            case 'fav':
                return redirect()->route('profile.favoritos', Auth::user()->id);
                break;
            case 'showPer':
                return redirect()->route('profile.show', Auth::user()->id);
                break;
            case 'misNov':
                return redirect()->route('profile.novelas', Auth::user()->id);
                break;
            case 'index':
                return redirect()->route('novelas.index');
                break;

            default:
                # code...
                break;
        }


    }


    public function enviarReview(REQUEST $request )
    {
        $request->validate([
            'ValEnv' => 'required'
        ]);
     
        if (isset($_POST['ValEnv'])) {
            valoracion::insert([
                'novela_idnovela' => $_POST['idnovela'],
                'usuario_idusuario' => $_POST['idusuario'],
                'puntuacion' => $_POST['ValEnv'],
            ]);
        }

        $Punt = valoracion::where('usuario_idusuario', '=', $_POST['idusuario'])->where('novela_idnovela', '=', $_POST['idnovela'])->get()[0];

        review::insert([
            'usuario_nombre_usuario' => $_POST['usuario_nombre_usuario'],
            'novela_idnovela' => $_POST['idnovela'],
            'review' => $_POST['review'],
            'idvaloracion' => $Punt->idvaloracion,
            'fecha' => date("Y-m-d"),
        ]);



        return redirect()->route('novela.show', $_POST['idnovela']);
    }

    public function editarReview()
    {

        review::where('usuario_nombre_usuario', $_POST['usuario_nombre_usuario'])->where('novela_idnovela', $_POST['idnovela'])->update(['review' => $_POST['review'], 'fecha' => date("Y-m-d")]);


        return redirect()->route('novela.show', $_POST['idnovela']);
    }


    public function borrarReview()
    {

        $favorito = review::where('usuario_nombre_usuario', $_POST['usuario_nombre_usuario'])->where('novela_idnovela', $_POST['idnovela'])->delete();



        return redirect()->route('novela.show', $_POST['idnovela']);
    }

    public function showFavoritos()
    {


        if (gate::allows('comprobar-edad', Auth::user())) {
            $novelas_cap = novela::join('capitulos', 'novelas.idnovela', '=', 'capitulos.novela_idnovela')->join('novela_has_generos', 'novelas.idnovela', '=', 'novela_has_generos.novela_idnovela')->join('generos', 'novela_has_generos.genero_idgenero', '=', 'generos.idgenero')->select('nombre_novela', 'idnovela', 'edad_min', 'created_at', 'fecha_creacion', 'estado', 'idcapitulo', 'nombre_capitulo', 'link_capitulo', 'nombre_genero')->orderBy('fecha_creacion', 'desc')->get();
        }

        if (!gate::allows('comprobar-edad', Auth::user())) {
            $novelas_cap = novela::join('capitulos', 'novelas.idnovela', '=', 'capitulos.novela_idnovela')->join('novela_has_generos', 'novelas.idnovela', '=', 'novela_has_generos.novela_idnovela')->join('generos', 'novela_has_generos.genero_idgenero', '=', 'generos.idgenero')->select('nombre_novela', 'idnovela', 'edad_min', 'created_at', 'fecha_creacion', 'estado', 'idcapitulo', 'nombre_capitulo', 'link_capitulo', 'nombre_genero')->orderBy('fecha_creacion', 'desc')->whereNull('edad_min')->get();
        }

        $generosNov = novela_has_genero::join('generos','novela_has_generos.genero_idgenero','generos.idgenero')->get();

        $capitulos = capitulo::select('*');
        $listas = Lista::join('lista_has_novelas', 'listas.idlista', 'lista_has_novelas.lista_idlista')->where('usuario_idusuario', Auth::user()->id)->get();


        $favoritos = Lista::join('lista_has_novelas','listas.idlista','lista_has_novelas.lista_idlista')->where('usuario_idusuario', Auth::user()->id)->get();

        return view('profile.favoritos', compact('novelas_cap', 'favoritos', 'capitulos', 'listas','generosNov'));
    }

    public function misNovelas()
    {


        if (gate::allows('comprobar-edad', Auth::user())) {
            $novelas_cap = novela::join('capitulos', 'novelas.idnovela', '=', 'capitulos.novela_idnovela')->join('novela_has_generos', 'novelas.idnovela', '=', 'novela_has_generos.novela_idnovela')->join('generos', 'novela_has_generos.genero_idgenero', '=', 'generos.idgenero')->select('nombre_novela', 'idnovela', 'edad_min', 'created_at', 'fecha_creacion', 'estado', 'idcapitulo', 'nombre_capitulo', 'link_capitulo', 'nombre_genero')->orderBy('fecha_creacion', 'desc')->get();
        }

        if (!gate::allows('comprobar-edad', Auth::user())) {
            $novelas_cap = novela::join('capitulos', 'novelas.idnovela', '=', 'capitulos.novela_idnovela')->join('novela_has_generos', 'novelas.idnovela', '=', 'novela_has_generos.novela_idnovela')->join('generos', 'novela_has_generos.genero_idgenero', '=', 'generos.idgenero')->select('nombre_novela', 'idnovela', 'edad_min', 'created_at', 'fecha_creacion', 'estado', 'idcapitulo', 'nombre_capitulo', 'link_capitulo', 'nombre_genero')->orderBy('fecha_creacion', 'desc')->whereNull('edad_min')->get();
        }

        $generosNov = novela_has_genero::join('generos','novela_has_generos.genero_idgenero','generos.idgenero')->get();

        $listas = Lista::join('lista_has_novelas', 'listas.idlista', 'lista_has_novelas.lista_idlista');

        $capitulos = capitulo::select('*');

        $novelasAutor = novela::where('usuario_idusuario', Auth::user()->id)->paginate(10);

        return view('profile.novelas', compact('novelas_cap', 'novelasAutor', 'capitulos', 'listas','generosNov'));
    }
}
