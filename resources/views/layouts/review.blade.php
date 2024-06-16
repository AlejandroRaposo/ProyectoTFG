<tr>
    <td class="justify-content-around">
        <div class="card mb-3" style="max-width: 1500px;">
            <div class="row g-0">
                <div class="col-md-12">
                    <div class="card-body row">
                        <div class="col-md-12">

                            <h5 class="card-title">
                                {{ $review->usuario_nombre_usuario }}
                            </h5>
                            <p class="card-text" id="{{$review->idreview}}">{{ $review->review }}</p>




                            @auth
                            <div class="card-footer row align-items">
                                <div class="col">

                                    

                                </div>
                                @if (Auth::user()->name === $review->usuario_nombre_usuario)

                                <div class="col">
                                    <form action="{{ route('review.borrar' , Auth::user()->id)}}" name="borrar" method="post">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="usuario_idusuario" value="{{Auth::user()->id}}">
                                        <input type="hidden" name="idreview" value="{{ $review->idreview }}">
                                        <input type="hidden" name="idnovela" value="{{$novela->idnovela}}">
                                        <input type="hidden" id="nombre_usuario" name="usuario_nombre_usuario" value="{{$review->usuario_nombre_usuario}}">
                                        <button class="btn btn-danger" type="submit">Borrar</button>
                                    </form>
                                    @endif
                                   

                                </div>




                            </div>

                           
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
    </td>
</tr>