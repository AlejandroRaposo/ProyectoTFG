<?php 
$capitulo = $novelas_cap->where('idnovela',$novela->idnovela)->where('idcapitulo',$capitulos->orderBy('fecha_creacion','desc')->get()[0]->idcapitulo);
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
           @foreach ($capitulo->pluck('nombre_genero')->all() as $genero)
         
          <div class="border rounded-pill bg-primary-subtle" style="width: fit-content;"><p>{{$genero}}</p></div>
          
          @endforeach
          </div></p></div>
        @endif
        <p class="card-text text-center"><x-nav-link :href="route('capitulo.show', ['id'=>$novela->idnovela, 'id2'=>$capitulos->orderBy('fecha_creacion','desc')->get()[0]->idcapitulo])">
        {{$capitulo->pluck('nombre_capitulo')->first()}}</x-nav-link></p>


        </div>
        <div class="col-md-2">
            <p class="text-center"><small class="text-body-secondary"> Autor: 
            <x-nav-link :href="route('profile.show', $novela->autor->id)">
            {{ $novela->autor->name }}
                    </x-nav-link></small>
            </p>
        </div>
       
         
  

      </div>
    </div>
  </div>
</div>
    </td>
</tr>