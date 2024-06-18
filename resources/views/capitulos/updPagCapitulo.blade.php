<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <title>{{$novelaEditar->nombre_novela}}</title>
</head>

<!-- Include stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<!-- Include the Quill library -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<body>
<x-app-layout>




<div class="container mt-5">
  <form action="{{ route('capitulo.update', ['id'=>$novelaEditar->idnovela,'id2'=>$capituloEditar->idcapitulo]) }}" method="post">
  @csrf
    @method('patch')
    <div class="form-group" style="margin: 10px;">
   
      <label for="name">Nombre del capitulo:</label>
      <input id="name" name="name" type="text" value="{{ $capituloEditar->nombre_capitulo }}" placeholder='Capítulo {{ $capituloEditar->nombre_capitulo }}'>
    </div>
    <div class="form-check form-switch" style="margin: 10px;">
     <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="ocultar">
     <label class="form-check-label" for="flexSwitchCheckDefault">Ocultar capítulo</label>
    </div>

    <div class="form-group" style="margin: 10px;">
      <label>Contenido del capitulo</label>
      <div id="editor"></div>
    </div>
    <input type="hidden" name="contenido" id="contenido">
    <button style="margin: 10px;" class="btn btn-primary" type="submit">Guardar capítulo</button>
  </form>
</div>

<script>

const quill = new Quill('#editor', {
  modules: {
    toolbar: [
      [{ 'font': [] }],
      [{ 'size': ['small', false, 'large', 'huge'] }], 
      ['bold', 'italic'],
      ['link', 'blockquote', 'image'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      [{ 'color': [] }, { 'background': [] }],       
      [{ 'align': [] }],
      ['clean']               
    ],
  },
  theme: 'snow',
});
quill.setContents(
  <?php echo $contenidoCapitulo?>
)
const initialData = {
  name: 'Capitulo',
  // `about` is a Delta object
  // Learn more at: https://quilljs.com/docs/delta
  about: [
    {
      insert:
        'A robot who has developed sentience, and is the only robot of his kind shown to be still functioning on Earth.\n',
    },
  ],
};

let inputElement = document.getElementById('contenido')
quill.on('text-change', function() {
  // sets the value of the hidden input to
  // the editor content in Delta format
  inputElement.value = JSON.stringify(quill.getContents())

});

const form = document.querySelector('form');
form.addEventListener('formdata', (event) => {
  // Append Quill content before submitting
  event.formData.append('about', JSON.stringify(quill.getContents().ops));
});


</script>


</x-app-layout>