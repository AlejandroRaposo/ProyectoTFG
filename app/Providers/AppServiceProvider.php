<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Novela;
use App\Models\Capitulo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrapFive();

        Gate::define('update-novela', function (User $user, Novela $novela) {
            return $user->id === $novela->usuario_idusuario;
        });

        Gate::define('update-capitulo', function (User $user, Capitulo $capitulo) {
            return $user->id === $capitulo->usuario_idusuario;
        });

        Gate::define('comprobar-edad', function (User $user) {
            $date1=date_create($user->fecha_nacimiento);
            $date2=date_create(date("Y-m-d"));
            $diff=date_diff($date1,$date2);
           if ($diff->format("%y") >= 18) 
            return true ;
            else return false;
        });

       
    if (! gate::allows('comprobar-edad',Auth::user())){
        View::share('idnovAzar', novela::whereNull('edad_min')->pluck('idnovela'));
        

   }
   if (gate::allows('comprobar-edad', Auth::user())){
    View::share('idnovAzar', novela::pluck('idnovela'));
      


        
    }

}
}
