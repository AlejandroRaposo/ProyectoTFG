<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('storage/assets/css/extra.css') }}">

  <title>{{$novela->nombre_novela}}</title>

  <style>
    #valoracion {
    animation: cambiarColor linear 2s forwards;
}

@keyframes cambiarColor {
    from { background-color:hsl(0,100%,75%)}
    to{ background-color:hsl(var(--colorVal),100%,75%)}
}
body { --colorVal: {{round($puntuacion->pluck('puntuacion')->avg(),2)*10+10}}}
  </style>
</head>
<?php $pag = 'showNov';?>
<body>


  <x-app-layout>
    <div class="container mt-5">
      <tr>
        <td class="justify-content-around">
          <div class="card mb-3" style="max-width: 1400px; padding:20px;">
            <div class="row g-0">
              <div class="col-md-2" style="margin: auto;">

                @if ($novela->portada === "novela portada default.jpg")

                <img src="{{ asset('storage/assets/'.$novela->portada) }}" class="img-fluid rounded-start" alt="...">

                @else
                <img src="{{ asset('storage/novelas/'.$novela->idnovela.'/portada/'.$novela->portada) }}" class="img-fluid rounded-start" alt="...">

                @endif
              </div>
              <div class="col-md-7">

                <div class="row g-0">
                  <h2 class="card-title text-center" style="margin-top: 20px; margin-bottom:20px;"><a href="{{route('novela.show', $novela->idnovela)}}"><strong>{{ $novela->nombre_novela }}</strong></a></h2>

                  <div class="row g-0">
                    <div class="card  mb-3" id="valoracion">
                      <div class="card-body">
                        @if (($puntuacion->count())!=0)

                        <h3>Puntuación: <?php echo round($puntuacion->pluck('puntuacion')->avg(), 2) ?> /10
                          @if (($puntuacion->count())>1)

                          (De {{ $puntuacion->count() }} valoraciones).
                          @elseif (($puntuacion->count() )===1)
                          (De {{ $puntuacion->count() }} valoracion).
                          @endif
                        </h3>
                        @else
                        <h3>No hay valoraciones</h3>
                        @endif

                      </div>
                    </div>
                  </div>
                  </div>
                 

                    @auth

                    <div class="row g-0">
                    @if (in_array(Auth::user()->id, $puntuacion->pluck('usuario_idusuario')->all()))

                    <form action="{{ route('novela.cambiar-valoracion' , $novela->idnovela )}}" id="valor" name="valor" method="post">
                    @csrf
                    @method('patch')
                    <select class="form-select form-select-sm" id="Val" name="Val" onchange="this.form.submit()">
                      <option selected disabled id="val-form">Cambia tu voto</option>
                      <option id="Val1" name="Val1" value="1">1</option>
                      <option id="Val2" name="Val2" value="2">2</option>
                      <option id="Val3" name="Val3" value="3">3</option>
                      <option id="Val4" name="Val4" value="4">4</option>
                      <option id="Val5" name="Val5" value="5">5</option>
                      <option id="Val6" name="Val6" value="6">6</option>
                      <option id="Val7" name="Val7" value="7">7</option>
                      <option id="Val8" name="Val8" value="8">8</option>
                      <option id="Val9" name="Val9" value="9">9</option>
                      <option id="Val10" name="Val10" value="10">10</option>

                    </select>

                    
                    <input type="hidden" name="idusuario" value="{{ Auth::user()->id }}">
                      <input type="hidden" name="idnovela" value="{{ $novela->idnovela }}">


                  </form>

                  @else

                  <form action="{{ route('novela.valorar' , $novela->idnovela )}}" id="valor2" name="valor" method="post">
                    @csrf
                    @method('post')
                    <select class="form-select form-select-sm" id="Val" name="Val" onchange="this.form.submit()">
                      <option selected disabled id="val-form">No has votado</option>
                      <option id="Val1" name="Val1" value="1">1</option>
                      <option id="Val2" name="Val2" value="2">2</option>
                      <option id="Val3" name="Val3" value="3">3</option>
                      <option id="Val4" name="Val4" value="4">4</option>
                      <option id="Val5" name="Val5" value="5">5</option>
                      <option id="Val6" name="Val6" value="6">6</option>
                      <option id="Val7" name="Val7" value="7">7</option>
                      <option id="Val8" name="Val8" value="8">8</option>
                      <option id="Val9" name="Val9" value="9">9</option>
                      <option id="Val10" name="Val10" value="10">10</option>

                      <input type="hidden" name="idusuario" value="{{ Auth::user()->id }}">
                      <input type="hidden" name="idnovela" value="{{ $novela->idnovela }}">


                    </select>
                  </form>


                    @endif

                   
                     
<div class="row" style="text-justify: auto;text-align:center;margin: 10px;">
  <div class="col">
                    @if (in_array(Auth::user()->id, $listas->where('usuario_idusuario', Auth::user()->id)->pluck('usuario_idusuario')->all()))

                    <form action="{{ route('novela.borrar-favorito' , $novela->idnovela )}}" name="favorito" method="post">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="pagina" value='showNov'>
                    <input type="hidden" name="idnovelaFav" value="{{ $novela->idnovela }}">
                    <input type="hidden" name="idlistaFav" value="{{ Auth::user()->id }}">
                    <button name="borrarFavo" id="fav-borrar"  type="submit"><img src="{{ asset('storage/assets/heart-solid.svg')}}" class="img-fluid rounded-circle" style="min-width:50px;min-height:50px;max-height: 90px;max-width: 90px; padding:10px" alt="..."></button>
                  </form>

                  @else
      
                  
            
                  <form action="{{ route('novela.add-favorito' , $novela->idnovela )}}" name="favorito" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="pagina" value='showNov'>
                    
                    <input type="hidden" name="idnovelaFav" value="{{ $novela->idnovela }}">
                    <input type="hidden" name="idlistaFav" value="{{ Auth::user()->id }}">
                    <button type="submit"><img id="fav-add" src="{{ asset('storage/assets/heart-regular.svg')}}" class="img-fluid rounded-start" style="min-width:50px;min-height:50px;max-height: 90px;max-width: 90px; padding:10px" alt="..."></button>
                   
                   

                  </form>
                  @endif
                  </div>
<div class="col">
                  @if (Auth::user()->id === $novela->usuario_idusuario)
                  <a type="button" class="btn btn-primary"  href="{{route('capitulo.create',$novela->idnovela)}}">Crear capitulo</a>
                  @endif
                  </div>
                  </div>
                  </div>
                  @endauth
              </div>
                
              

             


              <div class="col-2 border rounded" style="padding:10px; height:fit-content; margin:auto; width:auto;">
                <div class="row text-center" style="justify-content: center; margin-bottom:10px;">
                  <h2><strong>Autor</strong></h2>
                </div>
                <div class="row" style="justify-content: center; margin-bottom:10px;"><img style="max-width: 150px; max-height:150px;" src="{{ asset('storage/assets/'.$usuarios['link_imagen']) }}" class="img-fluid rounded-start" alt="..."></div>
                <div class="row" style="justify-content: center; margin-bottom:10px;">{{$usuarios['name']}}</div>


              </div>
            </div>
          </div>

    </div>
    </div>
    </div>
    </div>
    </td>
    </tr>
    <div class="row " style="justify-content: center; margin-bottom:10px;">
      <div class="col-2 border rounded" style="padding:20px; height:fit-content; width:auto; margin:20px;">
        <div class="row text-center" style="justify-content: center; margin-bottom:10px;">
          <h2><strong>Género:</strong></h2>
        </div>
        <div class="row" style="justify-content: center; margin-bottom:10px;">
          @foreach ($generosNov->where('novela_idnovela',$novela->idnovela)->pluck('nombre_genero') as $genero)
          <div class="border rounded-pill bg-primary-subtle" style="width: fit-content;width:auto;">
            <p>{{$generosNov->where('novela_idnovela',$novela->idnovela)->pluck('nombre_genero')[0]}}</p>
          </div>

          @endforeach
        </div>


      </div>
    </div>

    <div class="container">
      <div class="row text-center" style="margin-bottom: 20px;">
        <h1><strong>Sinopsis</strong></h1>
      </div>
      <div class="row text-center" style="margin-bottom: 20px;">
        <h2>{{ $novela->descripcion }}</h2>
      </div>
    </div>

    <div class="row container-sm border border-dark rounded justify-content-around" style="margin: auto; margin-top: 20px; margin-bottom: 20px; padding:20px">
      <table class="table table-striped table-bordered">
        @foreach ($capitulos as $capitulo)
        <tr>

          <td class="justify-content-around">

            <div class="row" style="padding: 20px;">
              <x-nav-link :href="route('capitulo.show', ['id'=>$novela->idnovela, 'id2'=>$capitulo->idcapitulo ])">
                {{ $capitulo->nombre_capitulo }}
              </x-nav-link>
              <p>Creado el {{ $capitulo->fecha_creacion }}</p>
              @auth
              <div class="card-footer row align-items bg-white">
                <p class="d-inline-flex gap-1">

                  <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample{{$capitulo->idcapitulo}}" aria-expanded="false" aria-controls="collapseExample">
                    Acciones
                  </button>
                </p>
                <div class="collapse" id="collapseExample{{$capitulo->idcapitulo}}">
                  <div class="card card-body">
                    <div class="row container">
                      @if (($novela->usuario_idusuario === Auth::user()->id) || (Auth::user()->tipo === 2) || (Auth::user()->tipo === 3))
                      <div class="col">
                        
                      <button class="btn btn-primary" style="height: 35px; margin:auto;  margin-bottom:20px;">
                          <a type="button"  href="{{route('capitulos.updPagCapitulo', ['id'=>$novela->idnovela, 'id2'=>$capitulo->idcapitulo])}}">Editar</a>
                        </button>
                      </div>
                      <div class="col">
                        <form action="{{ route('capitulo.destroy' , ['id'=>$novela->idnovela, 'id2'=>$capitulo->idcapitulo])}}" name="borrar" method="post">
                          @csrf
                          @method('delete')
                          <input type="hidden" name="idusuarioForm" value="{{$novela->usuario_idusuario}}">
                          <input type="hidden" name="usuario_idusuarioForm" value="{{Auth::user()->id}}">
                          <input type="hidden" name="idnovelaForm" value="{{ $novela->idnovela }}">
                          <input type="hidden" name="idcapituloForm" value="{{ $capitulo->idcapitulo }}">
                          <button class="btn btn-danger" type="submit">Borrar</button>
                        </form>
                      </div>
                      @endif

@endauth
            </div>

          </td>
         



          
        </tr>
        @endforeach

      </table>


      {{ $capitulos->links() }}
    </div>


    <div class="container mt-5 border round" style="padding: 10px;">
      
      @if (isset($reviews))
      <table class="table">
      @foreach ($reviews as $review)
              @include('layouts.review')
            @endforeach
            
           
      </table>
      {{ $reviews->links() }}
      @endif
    
      @auth

      @if (! in_array(Auth::user()->name, $reviews->pluck('usuario_nombre_usuario')->all()))
      <div class="border rounded" style="padding: 10px;">
        <h2><strong>Enviar review:</strong></h2>
      <form action="{{ route('review.enviar'  , $novela->idnovela)}}" method="post" name="enviar" id='reviewEnviar'>
      @csrf
      @method('post')

      <div class="border rounded" style="padding: 10px;"><div id="Autor">{{Auth::user()->name}}</div>
          <div class="row container" style="margin: 10px;">
          @if (! in_array(Auth::user()->id, $puntuacion->pluck('usuario_idusuario')->all()))

          <select class="form-select form-select-sm" id="ValEnv" name="ValEnv">
                      <option selected disabled id="val-form">Debes votar</option>
                      <option id="Val1" name="Val1" value="1">1</option>
                      <option id="Val2" name="Val2" value="2">2</option>
                      <option id="Val3" name="Val3" value="3">3</option>
                      <option id="Val4" name="Val4" value="4">4</option>
                      <option id="Val5" name="Val5" value="5">5</option>
                      <option id="Val6" name="Val6" value="6">6</option>
                      <option id="Val7" name="Val7" value="7">7</option>
                      <option id="Val8" name="Val8" value="8">8</option>
                      <option id="Val9" name="Val9" value="9">9</option>
                      <option id="Val10" name="Val10" value="10">10</option>
          </select>
          @endif
          <input type="hidden" name="idusuario" value="{{Auth::user()->id}}">
          <input type="hidden" name="usuario_nombre_usuario" value="{{Auth::user()->name}}">
          <input type="hidden" name="idnovela" value="{{$novela->idnovela}}">
            <textarea name="review" id="area_mensaje" rows="10"></textarea>
            </div>
            <div  style="margin: 20px;">
            <button class="btn btn-primary " id="botonEnviar"  type="submit">Enviar review</button>
           </form>
    
      </div>
      </div>
             @else
      <div class="border rounded" style="padding: 10px;">
        <h2><strong>Editar review:</strong></h2>
      <form action="{{ route('review.editar'  , $novela->idnovela)}}" method="post" name="editar" id='reviewEditar'>
           @csrf
           @method('patch')
      <div class="border rounded" style="padding: 10px;"><div id="Autor">{{Auth::user()->name}}</div>
          <div class="row container" style="margin: 10px;">
          <input type="hidden" name="idvaloracion" value="{{$puntuacion->where('usuario_idusuario','=',Auth::user()->id)->pluck('idvaloracion')}}">
          <input type="hidden" name="usuario_nombre_usuario" value="{{Auth::user()->name}}">
          <input type="hidden" name="idnovela" value="{{$novela->idnovela}}">
        
          <input type="hidden" name="idusuario" value="{{Auth::user()->id}}">
            <textarea name="review" id="area_mensaje2" rows="10">{{$reviews->where('usuario_nombre_usuario', Auth::user()->name)->pluck('review')[0]}}</textarea>
            </div>
            <div  style="margin: 20px;">
            <button class="btn btn-primary " id="botonEnviar"  type="submit">Editar review</button>
           </form>
    
        </div>
        </div>
        @endif

      @endauth
        </div>
     
       

  </x-app-layout>
 
</body>


</html>