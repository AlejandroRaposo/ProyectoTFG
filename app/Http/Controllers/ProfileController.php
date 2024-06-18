<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Genero;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\models\User;
use App\models\Mensaje;
use App\models\Lista;
use App\Models\Novela;
use App\Models\link_red;
use App\Models\novela_has_genero;
use App\Models\capitulo;
use Illuminate\Support\Facades\Gate;

use function PHPUnit\Framework\isNull;

class ProfileController extends Controller
{

     /**
     * Mostrar los datos del usuario
     */
    public function mostrarUsuario(string $id)
    {
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

        $listas = Lista::join('lista_has_novelas','listas.idlista','lista_has_novelas.lista_idlista')->where('novela_idnovela',$id)->get();
        $usuario =  User::find($id);
        $redes = link_red::where('usuarioid',$id)->select('usuarioid','facebook','twitter','instagram','discord','paypal','patreon','kofi')->get()[0];
        $mensajes = Mensaje::with('mensajeRecibido')->where("idusuario_objetivo",$id)->paginate(10, ['*'],'mensajes');
        $novelasUsuario = Novela::with('capitulos')->where('usuario_idusuario',$id)->paginate(10 , ['*'],'novelas');
        $capitulos = capitulo::select('*');
        $generosNov = novela_has_genero::join('generos','novela_has_generos.genero_idgenero','generos.idgenero')->get();
        
        $novelas_cap = novela::join('capitulos','novelas.idnovela', '=' ,'capitulos.novela_idnovela')->join('novela_has_generos','novelas.idnovela','=','novela_has_generos.novela_idnovela')->join('generos','novela_has_generos.genero_idgenero','=','generos.idgenero')->select('nombre_novela','idnovela','edad_min','created_at','fecha_creacion','estado','idcapitulo','nombre_capitulo','link_capitulo','nombre_genero')->orderBy('fecha_creacion', 'desc')->get();
        

        return view('profile.show', compact('usuario','mensajes','novelasUsuario','redes','generosNov','capitulos','novelas_cap','idnovelaAzar','listas'));
    }




    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $links = Link_red::where('usuarioid',$request->user()->id);

        if (($_POST['facebook']) != ''){
            $links->update(['facebook' => $_POST['facebook']]);
        }

        if (($_POST['twitter']) != ''){
            $links->update(['twitter' => $_POST['twitter']]);
        }

        if (($_POST['instagram']) != ''){
            $links->update(['instagram' => $_POST['instagram']]);
        }

        if (($_POST['discord']) != ''){
            $links->update(['discord' => $_POST['discord']]);
        }

        if (($_POST['patreon']) != ''){
            $links->update(['patreon' => $_POST['patreon']]);
        }

        if (($_POST['kofi']) != ''){
            $links->update(['kofi' => $_POST['kofi']]);
        }


        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
