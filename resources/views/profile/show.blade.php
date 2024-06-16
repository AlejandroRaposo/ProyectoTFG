<x-app-layout>
  <script>
    function editarmensaje(respuesta) {

      document.getElementById('area_mensaje').innerHTML = document.getElementById(respuesta).innerHTML;
      document.getElementById('mensaje').name = 'editar';

      document.getElementById('botonEnviar').innerText = 'Editar mensaje';
      document.getElementById('cancelar').innerText = 'Cancelar edición';
      document.getElementById('cancelar').setAttribute('class', 'btn btn-danger');
      document.getElementById('cancelar').type = 'button';
      document.getElementById('idmensaje').value = respuesta;
      document.getElementsByName('_method')[document.getElementsByName('_method').length - 1].value = "patch";

    }

    function respondermensaje(respuesta) {



      document.getElementById('responder').innerText = 'Respuesta a :';
      document.getElementById('comResp').innerText = document.getElementById(respuesta).innerText;
      document.getElementById('botonEnviar').innerText = 'Responder mensaje';
      document.getElementById('cancelar').innerText = 'Cancelar respuesta';
      document.getElementById('cancelar').setAttribute('class', 'btn btn-danger');
      document.getElementById('cancelar').type = 'button';
      document.getElementsByName('_method')[document.getElementsByName('_method').length - 1].value = "post";
      document.getElementById('idrespuesta').value = respuesta;

    }

    function cancelar() {

      document.getElementById('area_mensaje').innerHTML = '';
      document.getElementById('comResp').innerText = '';
      document.getElementById('mensaje').name = 'enviar';
      document.getElementById('responder').innerText = '';
      document.getElementById('responderId').value = null;
      document.getElementById('idrespuesta').value = null;
      document.getElementById('botonEnviar').innerText = 'Enviar mensaje';
      document.getElementById('cancelar').innerText = '';
      document.getElementById('cancelar').setAttribute('class', 'hidden');
      document.getElementById('cancelar').type = 'hidden';
      document.getElementsByName('_method')[document.getElementsByName('_method').length - 1].value = "post";
    }
  </script>

  <div class="container" id="contenedor-usuario">
    <div class="row" id="panel-usuario" style="margin:10px;">

      <div class="col-2 border rounded" style="padding:10px; height:fit-content;">
        <div>
          <div class="row con-com"><img style="max-width: 150px; max-height:150px;" src="{{ asset('storage/assets/'.$usuario->link_imagen) }}" class="img-fluid rounded-start" alt="..."></div>
          <div class="row con-com">{{$usuario->name}}</div>
          <div class="row con-com">Miembro desde: {{$usuario->created_at}}</div>
        </div>
        <div class="border rounded con-com">
          @if ((isset($redes->facebook)) || (isset($redes->twitter)) || (isset($redes->instagram)) || (isset($redes->discord)))
          <div class="row con-com">Redes Sociales</div>
          @endif
          <div class="row con-com">

            @if (isset($redes->facebook))
            <div class="col">
              <a class="rounded-circle" href="http://facebook.com/{{$redes->facebook}}"><img src="{{asset('storage/assets/facebook.svg') }}" class="redes mx-auto" alt="icono facebook"></a>
            </div>
            @endif

            @if (isset($redes->twitter))
            <div class="col">
              <a class="rounded-circle" href="http://twitter.com/{{$redes->twitter}}"><img src="{{asset('storage/assets/x-twitter.svg') }}" class="redes mx-auto" alt="icono twitter"></a>
            </div>
            @endif

            @if (isset($redes->instagram))
            <div class="col">
              <a class="rounded-circle" href="http://instagram.com/{{$redes->instagram}}"><img src="{{asset('storage/assets/instagram.svg') }}" class="redes mx-auto" alt="icono instagram"></a>
            </div>
            @endif

            @if (isset($redes->discord))
            <div class="col">
              <a class="rounded-circle" href="http://discord.gg/{{$redes->discord}}"><img src="{{ asset('storage/assets/discord.svg') }}" class="redes mx-auto" alt="icono discord"></a>
            </div>
            @endif

          </div>
        </div>
        <div class=" con-com border rounded">


          @if ((isset($redes->kofi)) || (isset($redes->patreon)) || (isset($redes->paypal)))
          <div class="row con-com">Donar</div>
          @endif

          <div class="row con-com align-items-center">
            @if (isset($redes->patreon))
            <div class="col ">
              <a class="rounded-circle" href="http://patreon.com/{{$redes->patreon}}"><img src="{{ asset('storage/assets/patreon.svg') }}" class="redes mx-auto" alt="icono patreon"></a>
            </div>
            @endif

            @if (isset($redes->paypal))
            <div class="col">
              <a class="rounded-circle" href="http://paypal.com/{{$redes->paypal}}"><img src="{{ asset('storage/assets/paypal.svg') }}" class="redes mx-auto" alt="icono paypal"></a>
            </div>
            @endif

            @if (isset($redes->kofi))
            <div class="col">
              <a class="rounded-circle" href="http://ko-fi.com/{{$redes->kofi}}"><img src="{{ asset('storage/assets/kofi.svg') }}" class="redes mx-auto" alt="icono kofi"></a>
            </div>
            @endif

          </div>
        </div>
      </div>
    </div>


    <div id="contenedor-tabs" class="col-9 border rounded" style="padding: 10px; margin-left: 10px;">

      <nav>

        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          @if ((isset($_GET['novelas']) || (isset($_GET['reviews']))))
          <button class="nav-link border-start-0" id="nav-mensajes-tab" data-bs-toggle="tab" data-bs-target="#nav-mensajes" type="button" role="tab" aria-controls="nav-mensajes" aria-selected="true">Tablón de mensajes</button>
          @else
          <button class="nav-link border-start-0 active" id="nav-mensajes-tab" data-bs-toggle="tab" data-bs-target="#nav-mensajes" type="button" role="tab" aria-controls="nav-mensajes" aria-selected="true">Tablón de mensajes</button>
          @endif

          @if (isset($_GET['novelas']))
          <button class="nav-link active" id="nav-novelas-tab" data-bs-toggle="tab" data-bs-target="#nav-novelas" type="button" role="tab" aria-controls="nav-novelas" aria-selected="true">Novelas</button>
          @else
          <button class="nav-link" id="nav-novelas-tab" data-bs-toggle="tab" data-bs-target="#nav-novelas" type="button" role="tab" aria-controls="nav-novelas" aria-selected="true">Novelas</button>
          @endif

          @if (isset($_GET['reviews']))
          <button class="nav-link active" id="nav-reviews-tab" data-bs-toggle="tab" data-bs-target="#nav-reviews" type="button" role="tab" aria-controls="nav-reviews" aria-selected="false">Reviews</button>
          @else
          <button class="nav-link" id="nav-reviews-tab" data-bs-toggle="tab" data-bs-target="#nav-reviews" type="button" role="tab" aria-controls="nav-reviews" aria-selected="false">Reviews</button>
          @endif

        </div>
      </nav>
      <div class="tab-content" id="nav-tabContent">

        @if ((isset($_GET['novelas']) || (isset($_GET['reviews']))))
        <div class="tab-pane fade" id="nav-mensajes" role="tabpanel" aria-labelledby="nav-mensajes-tab" tabindex="0" style="padding-top: 20px;">
          @else
          <div class="tab-pane fade show active" id="nav-mensajes" role="tabpanel" aria-labelledby="nav-mensajes-tab" tabindex="0" style="padding-top: 20px;">
            @endif

            @if (isset($mensajes))
            <table class="table table-striped">
              @foreach ($mensajes as $mensaje)
              @include('layouts.mensaje')
              @endforeach
              {{ $mensajes->links() }}
              @endif
            </table>


            @auth
            <div class="border rounded" style="padding: 10px;">
              <h2><strong>Enviar mensaje:</strong></h2>
              <form action="{{ route('mensaje.enviar' , $usuario->id )}}" method="post" name="enviar" id="mensaje">
                @csrf
                @method('post')

                <div class="border rounded" style="padding: 10px;">
                  <div id="responder"></div>
                  <div id="comResp"></div>
                </div>


                <div class="row container" style="margin: 10px;">
                  <input type="hidden" name="idusuario_origen" value="{{ Auth::user()->id }}">
                  <input type="hidden" name="idmensaje" id="idmensaje" value="0">
                  <input type="hidden" name="idusuario_destino" value="{{$usuario->id}}">
                  <input type="hidden" id="idrespuesta" name="idrespuesta" value=0>
                  <textarea name="mensaje" id="area_mensaje" rows="10"></textarea>
                </div>


                <div style="margin: 20px;">
                  <button class="btn btn-primary " id="botonEnviar" type="submit">Enviar comentario</button>
                  <a class="disabled" type="hidden" id="cancelar" onclick="cancelar()"></a>
                </div>
              </form>

            </div>
            @endauth


          </div>

          @if (isset($_GET['novelas']))
          <div class="tab-pane fade show active" id="nav-novelas" role="tabpanel" aria-labelledby="nav-novelas-tab" tabindex="0" style="padding-top: 20px;">

            @else
            <div class="tab-pane fade" id="nav-novelas" role="tabpanel" aria-labelledby="nav-novelas-tab" tabindex="0" style="padding-top: 20px;">
              @endif

              @if (isset($novelasUsuario))
              @foreach ($novelasUsuario as $novela)
              @include('layouts.novela')
              @endforeach
              {{ $novelasUsuario->links() }}
              @endif



              @auth
              @if (Auth::user()->id == $usuario->id)
              <button class="btn btn-primary" style="height: 35px; margin:auto; margin-left:20px; margin-bottom:20px;">
                <x-nav-link :href="route('novela.create')" :active="request()->routeIs('novela.create')">
                  <h3 class="text-white">{{ __('Nueva novela') }}</h3>
                </x-nav-link>
              </button>

              @endif
              @endauth
            </div>
          </div>

          <div class="tab-pane fade" id="nav-reviews" role="tabpanel" aria-labelledby="nav-reviews-tab" tabindex="0" style="padding-top: 20px;">

          </div>

        </div>
      </div>


    </div>
  </div>


</x-app-layout>