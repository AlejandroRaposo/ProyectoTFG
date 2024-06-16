<x-app-layout>

<div class="row container-sm border border-dark rounded justify-content-around container-fluid" style="margin:auto;margin-top:10px;padding:20px">
<div class="row"><h2>Mis favoritos</h2></div>

          <table class="table table-striped" style="margin: auto;">



            @foreach ($novelasCap->where('idnovela',$favoritos->novela_idnovela) as $novela)
            @include('layouts.novela')
            @endforeach


          </table>

          
          {{ $favoritos->links() }}
        </div>

</x-app-layout>