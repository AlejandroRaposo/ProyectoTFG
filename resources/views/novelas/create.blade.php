<x-app-layout>
<div class="container h-100 mt-5">
  <div class="row h-100 justify-content-center align-items-center">
    <div class="col-10 col-md-8 col-lg-6">
      <form action="{{ route('novela.store') }}" method="post" enctype='multipart/form-data'>
        @csrf
        <div class="form-group">
          <label for="nombre_novela">Nombre</label>
          <input type="text" class="form-control" id="nombre_novela" name="nombre_novela" required>
          
        </div>

        <div class="form-group">
          <label for="descripcion">Descripcion</label>
          <input type="text"  class="form-control" id="descripcion" name="descripcion" required>
        </div>

    
        <div class="form-group">
          <label for="genero" style="margin-top: 20px; margin-bottom:20px;">Género:</label>
          <div class="btn-group" role="group" name='genero[]' aria-label="Basic checkbox toggle button group">
            @foreach ($generos as $genero)
  <input type="checkbox" class="btn-check" name='genero[]' id="{{'btncheck'.$genero->idgenero}}" value={{$genero->idgenero}} autocomplete="off">
  <label class="btn btn-outline-primary" for="{{'btncheck'.$genero->idgenero}}">{{$genero->nombre_genero}}</label></div>


  @endforeach
    
        </div>
            

        <input type="hidden" name="usuario_idusuario" value='{{Auth::user()->id}}' required>
        
        <div class="form-group">
        <input type="checkbox" class="btn-check" id="edad_minima" autocomplete="off">
        <label class="btn btn-outline-primary" name="edad_min" for="edad_minima">Contenido no apto para menores</label>

        </div>

        <div class="input-group mb-3">
        <label class="input-group-text" for="inputGroupFile02">Portada:</label>
  <input type="file" class="form-control" id="inputGroupFile02" name="portada">
  
</div>


        
        
        <br>
        <button type="submit" class="btn btn-primary">Crear novela</button>
      </form>
    </div>
  </div>
</div>

</x-app-layout>