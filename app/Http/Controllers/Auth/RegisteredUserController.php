<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\models\Link_red;
use App\models\Lista;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo' => 1,
            'link_imagen' => 'user-solid.svg',
            'fecha_nacimiento' => $request->fecha,
        ]);

        $usuario = User::select('id')->where('email',$request->email)->get()[0];

        link_red::insert(['usuarioid' => $usuario['id']]);

        lista::insert(['usuario_idusuario' => $usuario['id']]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('novelas.index', absolute: false));
    }
}
