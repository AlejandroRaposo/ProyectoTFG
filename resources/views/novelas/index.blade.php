<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <title>DreamPen</title>
</head>

<body>
  <?php $pag = 'index';?>
  <x-app-layout>

    <div class="contenido container-fluid">

      <div id="MovilCarrusel" class="row container-sm border border-dark rounded justify-content-around container-fluid" style="margin:auto;margin-top:10px;padding:20px">
        <div class="row container-sm" style="text-align: center; margin-bottom: 15px;">
          <h2>Últimas novelas</h2>
        </div>
        <div id="carouselExampleIndicators" class="carousel slide container-fluid" data-bs-slide="ride">
          
          <div class="carousel-inner">
            @foreach ($lista_ultimas_novelas as $novela)
            @if ($novela === $lista_ultimas_novelas->first())
            <div class="carousel-item active">
              @else
              <div class="carousel-item">
                @endif

                @if ($novela->portada === "novela portada default.jpg")

                <img src="{{ asset('storage/assets/'.$novela->portada) }}" class="d-block w-100" alt="...">

                @else
                <img src="{{ asset('storage/novelas/'.$novela->idnovela.'/portada/'.$novela->portada) }}" class="d-block w-100" alt="...">

                @endif

                <div class="carousel-caption d-none w-30 d-sm-block bg-dark rounded">
                  <h5><x-nav-link :href="route('novela.show', $novela->idnovela)" class="text-white">{{ $novela->nombre_novela }}</x-nav-link></h5>
                </div>

              </div>
              @endforeach




            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>

        <div id="Ult-novelas-ord" class="row container-sm border border-dark rounded justify-content-around" style="margin: auto; margin-top: 20px; margin-bottom: 20px; padding:20px">
          <div class="row container-sm" style="text-align: center; margin-bottom: 15px;">
            <h2>Últimas novelas</h2>
          </div>
          @foreach ($lista_ultimas_novelas as $novela)
          <div class="col-sm-2 mb-3 mb-sm-0">
            <div class="card">

              @if ($novela->portada === "novela portada default.jpg")

              <img src="{{ asset('storage/assets/'.$novela->portada) }}" class="img-fluid rounded-start" alt="...">

              @else
              <img src="{{ asset('storage/novelas/'.$novela->idnovela.'/portada/'.$novela->portada) }}" class="img-fluid rounded-start" alt="...">

              @endif

              <div class="card-body">
                <h6 class="card-title">
                  <h5 class="card-title"><x-nav-link :href="route('novela.show', $novela->idnovela)">{{ $novela->nombre_novela }}</x-nav-link></h5>
                </h6>


              </div>
            </div>
          </div>
          @endforeach

        </div>

        <div class="row container-sm border border-dark rounded justify-content-around container-fluid" style="margin:auto;margin-top:10px;padding:20px">


          <table class="table table-striped" style="margin: auto;">



            @foreach ($lista_novelas as $novela)
            @include('layouts.novela')
            @endforeach


          </table>

          
          {{ $lista_novelas->links() }}
        </div>
      </div>
    </div>
    </div>
    </div>
    </div>

    </div>
    </div>
    </div>

  </x-app-layout>



</body>

</html>