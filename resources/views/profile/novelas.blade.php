<x-app-layout>
  <?php $pag='misNov'; 
  ?>


<div class="row container-sm border border-dark rounded justify-content-around container-fluid" style="margin:auto;margin-top:10px;padding:20px">
<div class="row text-center" style="padding: 20px;"><h2>Mis novelas</h2></div>

          <table class="table table-striped" style="margin: auto;">



            @foreach ($novelasAutor as $novela)
            @include('layouts.novela')
            @endforeach


          </table>

          
          {{ $novelasAutor->links() }}
        </div>

</x-app-layout>