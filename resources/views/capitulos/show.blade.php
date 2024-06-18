<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <title>{{$novela->nombre_novela}}</title>
</head>
<body>
  
  

  @if ($novela->idnovela != $capitulo->novela_idnovela)
  <?php header('Location: /novelas');
  exit();?>
  @endif

<x-app-layout>
    <!-- Include stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<!-- Include the Quill library -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<div class="container mt-5">
  <tr>
    <td class="justify-content-around">
<div class="card mb-3" style="max-width: 1500px; padding:20px;">
  <div class="row g-0">
    
      <h2 class="card-title text-center" style="margin-top: 20px; margin-bottom:20px;"><x-nav-link :href="route('novela.show', $novela->idnovela)"><strong>{{ $novela->nombre_novela }}</strong></x-nav-link></h2>


      </div>

      <div class="row g-0 text-center">
      <p>Por : <x-nav-link :href="route('profile.show', $novela->autor->id)"><strong>{{ $novela->autor->name }}</strong></x-nav-link></p>
      </div>

      <!-- Create the editor container -->


    </div>
</div>

<div class="container mt-5">
  <div class="row d-flex g-0 text-center justify-content-evenly">
    <div class="col botonCap">
    @if (array_search($capitulo->idcapitulo,$idcapitulos)!=0)
  <a class="btn btn-info rounded-pill" type="button" href="{{route('capitulo.show', ['id'=>$novela->idnovela, 'id2'=>$idcapitulos[array_search($capitulo->idcapitulo,$idcapitulos)-1]])}}"><img src="{{ asset('storage/assets/chevron_left_48dp_FILL0_wght400_GRAD0_opsz48.svg') }}" class="img-fluid rounded-circle" alt="..."></a>
    @else
  <a class="btn btn-info rounded-pill disabled" type="button"><img src="{{ asset('storage/assets/chevron_left_48dp_FILL0_wght400_GRAD0_opsz48.svg') }}" class="img-fluid rounded-circle" alt="..."></a>
    @endif
    </div>
    
    <div class="col">
    <select class="form-select form-select-sm" id="cambiarCap" name="cambiarCap" onchange="location.href = this.options[this.selectedIndex].value">
      @foreach ($novela->capitulos as $cap)
      
  

  @if (($cap->idcapitulo) == $capitulo->idcapitulo)
  <option selected id="cap{{$cap->idcapitulo}}" name="cap{{$cap->idcapitulo}}" value="{{route('capitulo.show', ['id'=>$novela->idnovela, 'id2'=>$cap->idcapitulo])}}">{{$cap->nombre_capitulo}}</option>
  @else
  <option id="cap{{$cap->idcapitulo}}" name="cap{{$cap->idcapitulo}}" value="{{route('capitulo.show', ['id'=>$novela->idnovela, 'id2'=>$cap->idcapitulo])}}">{{$cap->nombre_capitulo}}</option>
  @endif

  @endforeach
</select>
    </div>

<div class="col botonCap">
    @if ( array_search($capitulo->idcapitulo,$idcapitulos) >= (count($idcapitulos)-1))
    <a class="btn btn-info rounded-pill disabled" type="button"><img src="{{ asset('storage/assets/chevron_right_48dp_FILL0_wght400_GRAD0_opsz48.svg') }}" class="img-fluid rounded-circle" alt="..."></a>
    @else
  <a class="btn btn-info rounded-pill" type="button" href="{{route('capitulo.show', ['id'=>$novela->idnovela, 'id2'=>$idcapitulos[array_search($capitulo->idcapitulo,$idcapitulos)+1]])}}"><img src="{{ asset('storage/assets/chevron_right_48dp_FILL0_wght400_GRAD0_opsz48.svg') }}" class="img-fluid rounded-circle" alt="..."></a>
    @endif
    </div>
</div>
</div>


    <div class="container mt-5 border round" style="padding: 20px;">
    <h1 class="text-center" style="margin-bottom: 20px;"><strong>{{ $capitulo->nombre_capitulo }}</strong></h1>
    <div id="editor">
  
</div>
<!-- Initialize Quill editor -->
<script>
  var respuesta = 0;
 const options = {
  readOnly: true,
  modules: {
    toolbar: null
  },
  theme: 'snow'
};
const quill = new Quill('#editor', options);
const Delta = Quill.import('delta');
quill.setContents(
  <?php echo $contenidoCapitulo?>
);
</script>
<script>





            function editarComentario(respuesta) {
      
              document.getElementById('area_mensaje').innerHTML = document.getElementById(respuesta).innerHTML;
              document.getElementById('comentario').name = 'editar';
              document.getElementById('comentario').setAttribute('action', "{{ route('comentario.editar'  , ['id'=>$novela->idnovela, 'id2'=>$capitulo->idcapitulo ])}}");
              document.getElementById('botonEnviar').innerText = 'Editar comentario';
              document.getElementById('cancelar').innerText = 'Cancelar edición';
              document.getElementById('cancelar').setAttribute('class', 'btn btn-danger');
              document.getElementById('cancelar').type = 'button';
              document.getElementById('idcomentario').value = respuesta;
              document.getElementsByName('_method')[document.getElementsByName('_method').length-1].value = "patch";
             
            }

            function responderComentario(respuesta) {

             
              
              document.getElementById('responder').innerText = 'Respuesta a :';
              document.getElementById('comResp').innerText = document.getElementById(respuesta).innerText;
              document.getElementById('botonEnviar').innerText = 'Responder comentario';
              document.getElementById('cancelar').innerText = 'Cancelar respuesta';
              document.getElementById('cancelar').setAttribute('class', 'btn btn-danger');
              document.getElementById('cancelar').type = 'button';
              document.getElementsByName('_method')[document.getElementsByName('_method').length-1].value = "post";
              document.getElementById('id').value = respuesta;

              
            }

            function cancelar() {

              document.getElementById('area_mensaje').innerHTML = '';
              document.getElementById('comentario').name = 'enviar';
              document.getElementById('responder').innerText = '';
              document.getElementById('responderId').value = null;
              document.getElementById('botonEnviar').innerText = 'Enviar comentario';
              document.getElementById('cancelar').innerText = '';
              document.getElementById('cancelar').setAttribute('class', 'hidden');
              document.getElementById('cancelar').type = 'hidden';
              document.getElementsByName('_method')[document.getElementsByName('_method').length-1].value = "post";
            }
            </script>
       
    </div>
   

    <div class="container mt-5 border round" style="padding: 10px;">
      
  @if (isset($comentarios))
  <table class="table">
  @foreach ($comentarios as $comentario)
          @include('layouts.comentario')
        @endforeach
        
       
  </table>
  {{ $comentarios->links() }}
  @endif

  @auth
  <div class="border rounded" style="padding: 10px;">
    <h2><strong>Enviar comentario:</strong></h2>
  <form action="{{ route('comentario.enviar'  , ['id'=>$novela->idnovela, 'id2'=>$capitulo->idcapitulo ])}}" method="post" name="enviar" id='comentario'>
  @csrf
  @method('post')
    
  <div class="border rounded" style="padding: 10px;"><div id="responder"></div>
  <div id="comResp"></div></div>
  
 
      <div class="row container" style="margin: 10px;">
      <input type="hidden" name="idcapitulo" value="{{$capitulo->idcapitulo}}">
      <input type="hidden" name="idcomentario" id="idcomentario" value="0">
      <input type="hidden" name="usuario_idusuario" value="{{Auth::user()->id}}">
      <input type="hidden" name="idnovela" value="{{$novela->idnovela}}">
      <input type="hidden" id="id" name="idrespuesta" value=0>
      
      

      

        <textarea name="comentario" id="area_mensaje" rows="10"></textarea>
        </div>
        
        
        <div  style="margin: 20px;">
        <button class="btn btn-primary " id="botonEnviar"  type="submit">Enviar comentario</button>
        <a class="disabled" type="hidden" id="cancelar" onclick="cancelar()"></a>
        </div>
       </form>

  </div>
  </div>
  @endauth
    </div>
 
 

</x-app-layout>