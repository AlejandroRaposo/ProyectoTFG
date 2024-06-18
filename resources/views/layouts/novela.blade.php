<?php
$capitulo = $novelas_cap->where('idnovela', $novela->idnovela)->where('idcapitulo', $capitulos->orderBy('fecha_creacion', 'desc')->get()[0]);

?>

<tr>
  <td class="justify-content-around">
    <div class="card mb-3" style="max-width: 1500px;">
      <div class="row g-0">
        <div class="col-md-2 carta-img-novela">

          @if ($novela->portada === "novela portada default.jpg")

          <img src="{{ asset('storage/assets/'.$novela->portada) }}" class="img-fluid rounded-start" alt="...">

          @else
          <img src="{{ asset('storage/novelas/'.$novela->idnovela.'/portada/'.$novela->portada) }}" class="img-fluid rounded-start" alt="...">

          @endif

        </div>
        <div class="col-md-10">
          <div class="card-body row">
            <div class="col-md-8 carta-contenido-novela">
              <h5 class="card-title text-center"><x-nav-link :href="route('novela.show', $novela->idnovela)">{{ $novela->nombre_novela }}</x-nav-link></h5>
              <div class="border rounded bg-light" style="padding: 10px;">
                <p class="card-text text-center">Géneros</p>
                @if (isset($novela->generos))
                <p class="card-text">
                <div class="row  justify-content-evenly">
                  @foreach ($generosNov->where('novela_idnovela',$novela->idnovela)->pluck('nombre_genero') as $genero)

                  <div class="border rounded-pill bg-primary-subtle" style="width: fit-content;">
                    <p>{{$generosNov->where('novela_idnovela',$novela->idnovela)->pluck('nombre_genero')[0]}}</p>
                  </div>

                  @endforeach
                </div>
                </p>
              </div>
              @endif
          

            </div>
            <div class="col-md-2">
              <p class="text-center"><small class="text-body-secondary"> Autor:
                  <x-nav-link :href="route('profile.show', $novela->autor->id)">
                    {{ $novela->autor->name }}
                  </x-nav-link></small>
              </p>
              
              @auth
              <div class="card-footer row align-items bg-white">
                <p class="d-inline-flex gap-1">

                  <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample{{$novela->idnovela}}" aria-expanded="false" aria-controls="collapseExample">
                    Acciones
                  </button>
                </p>
                <div class="collapse" id="collapseExample{{$novela->idnovela}}">
                  <div class="card card-body">
                    <div class="row container">
                      @if (($novela->usuario_idusuario === Auth::user()->id) || (Auth::user()->tipo === 2) || (Auth::user()->tipo === 3))
                      <div class="col">
                        
                      <button class="btn btn-primary" style="height: 35px; margin:auto;  margin-bottom:20px;">
                          <a type="button"  href="{{route('novela.updatePage',$novela->idnovela)}}">Editar</a>
                        </button>
                      </div>
                      <div class="col">
                        <form action="{{ route('novela.destroy' , $novela->idnovela)}}" name="borrar" method="post">
                          @csrf
                          @method('delete')
                          <input type="hidden" name="idusuarioForm" value="{{$novela->usuario_idusuario}}">
                          <input type="hidden" name="usuario_idusuarioForm" value="{{Auth::user()->id}}">
                          <input type="hidden" name="idnovelaForm" value="{{ $novela->idnovela }}">
                          <button class="btn btn-danger" type="submit">Borrar</button>
                        </form>
                      </div>
                      @endif



                      @if (($novela->usuario_idusuario != Auth::user()->id) || (Auth::user()->tipo === 2) || (Auth::user()->tipo === 3))
                      <div class="col">
                        @if (in_array(Auth::user()->id, $listas->where('novela_idnovela',$novela->idnovela)->pluck('idlista')->all()))

                        <form action="{{ route('novela.borrar-favorito' , $novela->idnovela )}}" name="favorito" method="post">
                          @csrf
                          @method('delete')

                          <input type="hidden" name="idnovelaFav" value="{{ $novela->idnovela }}">
                          <input type="hidden" name="idlistaFav" value="{{ Auth::user()->id }}">
                          <button name="borrarFavo" id="fav-borrar" type="submit"><img src="{{ asset('storage/assets/heart-solid.svg')}}" class="img-fluid rounded-circle" style="min-width:50px;min-height:50px;max-height: 90px;max-width: 90px; padding:10px" alt="..."></button>
                        </form>

                        @else


                        @endif
                      </div>
                      @endif




                    </div>
                  </div>
                </div>






              </div>


              @endauth


            </div>


          </div>
        </div>
      </div>
    </div>
  </td>
</tr>