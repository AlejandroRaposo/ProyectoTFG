<tr>
  <td class="justify-content-around">
    <div class="card mb-3" style="max-width: 1500px;">
      <div class="row g-0">
        <div class="col-md-2">

          <x-nav-link :href="route('profile.show', $novela->autor->id)">
            {{ $novela->autor->name }}
          </x-nav-link></small>
          </p>
        </div>
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
                          <a type="button"  href="{{route('capitulo.updatePage', ['id'=>$novela->idnovela, 'id2'=>$capitulo->idcapitulo])}}">Editar</a>
                        </button>
                      </div>
                      <div class="col">
                        <form action="{{ route('capitulo.destroy' , $capitulo->idcapitulo)}}" name="borrar" method="post">
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
    </div>
    </div>
    </div>
  </td>
</tr>