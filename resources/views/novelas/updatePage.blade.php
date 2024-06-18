<x-app-layout>
  <div class="container h-100 mt-5">
    <div class="row h-100 justify-content-center align-items-center">
      <div class="col-10 col-md-8 col-lg-6">

        <form action="{{ route('novela.update','$novelaEditar->idnovela') }}" method="post" enctype='multipart/form-data'>
          @csrf
          @method('patch')

          <div class="form-group">
            <label for="nombre_novela">Nombre</label>
            <input type="text" class="form-control" id="nombre_novela" name="nombre_novela" value="{{$novelaEditar->nombre_novela}}" required>

          </div>

          <div class="form-group">
            <label for="descripcion">Descripcion</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{$novelaEditar->descripcion}}" required>
          </div>


          <div class="form-group">
            <label for="genero" style="margin-top: 20px; margin-bottom:20px;">Género:</label>
            <div class="btn-group" role="group" name='genero[]' aria-label="Basic checkbox toggle button group">
              @foreach ($generos as $genero)
          @if (isset($novelaEditar->generos))
              @if (in_array($genero->idgenero, $novelaEditar->generos->toArray()))
              <input type="checkbox" class="btn-check" name='genero[]' id="{{'btncheck'.$genero->idgenero}}" value="{{$genero->idgenero}}" autocomplete="off" checked>
              @else
              <input type="checkbox" class="btn-check" name='genero[]' id="{{'btncheck'.$genero->idgenero}}" value="{{$genero->idgenero}}" autocomplete="off">
              @endif
          @endif
              <label class="btn btn-outline-primary" for="{{'btncheck'.$genero->idgenero}}">{{$genero->nombre_genero}}</label>
            </div>


            @endforeach

          </div>


          <input type="hidden" name="usuario_idusuario" value='{{$novelaEditar->usuario_idusuario}}' required>
          <input type="hidden" name="idnovelaEditar" value="{{$novelaEditar->idnovela}}">

          <div class="form-group" style="margin: auto; padding:15px;">
            <label class="btn btn-outline-primary" name="edad_min" for="edad_minima">Contenido no apto para menores</label>
            <input type="checkbox" class="btn-check" id="edad_minima" value="{{$novelaEditar->edad_min}}" autocomplete="off">
          </div>

          <div class="input-group" style="margin: auto; padding:15px;">
            <label class="input-group-text" for="inputGroupFile01">Portada:</label>
            <input type="file" class="form-control" id="inputGroupFile01" name="portada">

          </div>




          <br>
          <button type="submit" class="btn btn-primary">Editar novela</button>
        </form>
      </div>
    </div>
  </div>

</x-app-layout>