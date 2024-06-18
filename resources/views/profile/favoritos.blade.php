<x-app-layout>
<?php $pag='fav';
 echo $novelas_cap?>
<div class="row container-sm border border-dark rounded justify-content-around container-fluid" style="margin:auto;margin-top:10px;padding:20px">
<div class="row"><h2>Mis favoritos</h2></div>



          <table class="table table-striped" style="margin: auto;">



            @foreach ($novelas_cap->whereIn('idnovela', $favoritos->pluck('novela_idnovela')) as $novela)
            @include('layouts.novela')
            @endforeach


          </table>

          
         
        </div>

</x-app-layout>