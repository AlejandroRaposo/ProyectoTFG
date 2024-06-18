<tr>
    <td class="justify-content-around">
<div class="card mb-3" style="max-width: 1500px;">
  <div class="row g-0">
    <div class="col-md-12">
      <div class="card-body row">
      <div class="col-md-12">
        @if ((isset($comentario->idrespuesta)) && ($comentario->idrespuesta != 0))
        <div class="border rounded bg-light" style="padding: 10px;">
        <h5 class="card-title">Respuesta a: </h5>
        <h5 class="card-text"><x-nav-link :href="route('profile.show', $comentarios->where('idcomentario',$comentario->idrespuesta)->pluck('comentarioUsuario')[0]->id)">
        {{ $comentarios->where('idcomentario',$comentario->idrespuesta)->pluck('comentarioUsuario')[0]->name }}
                    </x-nav-link></h5>
        <p class="card-text" id="{{$comentarios->where('idcomentario',$comentario->idrespuesta)->pluck('comentarioUsuario')[0]->idcomentario}}">{{ $comentarios->where('idcomentario',$comentario->idrespuesta)->first()->comentario }}</p>
        </div>
        @endif
        <h5 class="card-title"><x-nav-link :href="route('profile.show', $comentario->comentarioUsuario->id)">
        {{ $comentario->comentarioUsuario->name }}
                    </x-nav-link></h5>
        <p class="card-text" id="{{$comentario->idcomentario}}">{{ $comentario->comentario }}</p>
        

      






        @auth
        <div class="card-footer row align-items">
        <div class="col">
        
          @if (Auth::user()->id != $comentario->usuario_idusuario)
          <a type='button' class='btn btn-primary' href='#comentario' onclick="responderComentario(<?php echo $comentario->idcomentario?>)">Responder</a>


        @elseif (Auth::user()->id === $comentario->usuario_idusuario)
        <a type='button' class='btn btn-primary' href=#comentario onclick="editarComentario(<?php echo $comentario->idcomentario?>)">Editar</a>
       



        </div>

        <div class="col">
        <form action="{{ route('comentario.borrar' , ['id'=>$novela->idnovela, 'id2'=>$capitulo->idcapitulo ])}}" name="borrar" method="post">
          @csrf
          @method('delete')
          <input type="hidden" name="idcapitulo" value="{{$capitulo->idcapitulo}}">
          <input type="hidden" name="usuario_idusuario" value="{{Auth::user()->id}}">
          <input type="hidden" name="idnovela" value="{{$novela->idnovela}}">
          <input type="hidden" name="idcomentario" value="{{ $comentario->idcomentario }}">
          <input type="hidden" id="responderId" name="idrespuesta" value=0>
        <button class="btn btn-danger"  type="submit">Borrar</button>
        

        @endif
        </form>
        </div>




      </div>


        @endauth
        
      </div>
    </div>
  </div>
</div>
    </td>
</tr>