<tr>
  <td class="justify-content-around">
    <div class="card mb-3" style="max-width: 1500px;">
      <div class="row g-0">
        <div class="col-md-12">
          <div class="card-body row">
            <div class="col-md-12">
              @if ((isset($mensaje->idrespuesta)) && ($mensaje->idrespuesta != 0))
              <div class="border rounded bg-light" style="padding: 10px;">
                <h5 class="card-title">Respuesta a: </h5>
                <h5 class="card-text"><x-nav-link :href="route('profile.show', $mensajes->where('idmensajes',$mensaje->idrespuesta)->pluck('mensajeEnviado')[0]->id)">
                    {{ $mensajes->where('idmensajes',$mensaje->idrespuesta)->pluck('mensajeEnviado')[0]->name }}
                  </x-nav-link></h5>
                <p class="card-text" id="{{$mensajes->where('idmensajes',$mensaje->idrespuesta)}}">{{ $mensajes->where('idmensajes',$mensaje->idrespuesta)->first()->mensaje }}</p>
              </div>
              @endif
              <h5 class="card-title"><x-nav-link :href="route('profile.show', $mensaje->mensajeEnviado->id)">
                  {{ $mensaje->mensajeEnviado->name }}
                </x-nav-link></h5>
              <p class="card-text" id="{{$mensaje->idmensajes}}">{{ $mensaje->mensaje }}</p>





              @auth
              <div class="card-footer row align-items">
                <p class="d-inline-flex gap-1">

                  <button class="btn btn-primary"  type="button" data-bs-toggle="collapse" href="#collapseExample{{$mensaje->idmensajes}}" data-bs-target="#collapseExample{{$mensaje->idmensajes}}" aria-expanded="false" aria-controls="collapseExample">
                    Acciones
                  </button>
                </p>
                <div class="collapse" id="collapseExample{{$mensaje->idmensajes}}">
                  <div class="card card-body">
                    <div class="row container" >
                      @if (($mensaje->idusuario_origen === Auth::user()->id) || (Auth::user()->tipo === 2) || (Auth::user()->tipo === 3))
                      <div class="col">
                        <a style="max-width:fit-content;" type='button' class='btn btn-primary' href=#mensaje onclick="editarmensaje(<?php echo $mensaje->idmensajes ?>)">Editar</a>
                      </div>
                      <div class="col">
                        <form action="{{ route('mensaje.borrar' , Auth::user()->id)}}" name="borrar" method="post">
                          @csrf
                          @method('delete')
                          <input type="hidden" name="idusuario_destino" value="{{$mensaje->idusuario_objetivo}}">
                          <input type="hidden" name="usuario_idusuario" value="{{Auth::user()->id}}">
                          <input type="hidden" name="idmensaje" value="{{ $mensaje->idmensajes }}">
                          <input type="hidden" id="responderId" name="idrespuesta" value=0>
                          <button class="btn btn-danger" type="submit">Borrar</button>
                        </form>
                      </div>
                      @endif

                      <div class="col">

                        @if (($mensaje->idusuario_origen != Auth::user()->id) || (Auth::user()->tipo === 2) || (Auth::user()->tipo === 3))

                        <a type='button' style="max-width:fit-content;" class='btn btn-primary' href='#mensaje' onclick="respondermensaje(<?php echo $mensaje->idmensajes ?>)">Responder</a>
                      </div>
                      @endif


                      @if ((Auth::user()->tipo === 3))

                      @endif


                      @if (($mensaje->idusuario_origen === Auth::user()->id) || (Auth::user()->tipo === 3))

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
  </td>
</tr>