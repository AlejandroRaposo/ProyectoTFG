<?php

use App\Http\Controllers\novelaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\mensajeController;
use App\Http\Controllers\comentarioController;

use Illuminate\Support\Facades\Route;

// Index

Route::get('/', novelaController::class . '@index')->name('novelas.index');

Route::get('/dashboard', novelaController::class . '@index')->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/novelas', novelaController::class . '@index')->name('novelas.index');

// Novelas

Route::get('/novela/create', novelaController::class . '@create')->middleware(['auth', 'verified'])->name('novela.create');

Route::post('/novelas', novelaController::class . '@store')->middleware(['auth', 'verified'])->name('novela.store');

Route::get('/novela/{id}', novelaController::class . '@show')->name('novela.show');

Route::delete('/novelas/{id}', novelaController::class . '@destroy')->middleware(['auth', 'verified'])->name('novela.destroy');

Route::post('/novelas/{id}/valorar', novelaController::class . '@valorar')->middleware(['auth', 'verified'])->name('novela.valorar');

Route::patch('/novelas/{id}/valorar', novelaController::class . '@cambiarValoracion')->middleware(['auth', 'verified'])->name('novela.cambiar-valoracion');

Route::post('/novelas/{id}/favorito', novelaController::class . '@addfavorito')->middleware(['auth', 'verified'])->name('novela.add-favorito');

Route::patch('/novelas/{id}/favorito', novelaController::class . '@cambiarValoracion')->middleware(['auth', 'verified'])->name('novela.cambiar-favorito');



// Capitulos

Route::get('/novela/{id}/capitulo/{id2}', novelaController::class . '@lector')->name('capitulo.show');

Route::get('/novela/{id}/create', novelaController::class . '@createCapitulo')->middleware(['auth', 'verified'])->name('capitulo.create');

Route::post('/novela/{id}/create', novelaController::class . '@storeCapitulo')->middleware(['auth', 'verified'])->name('capitulo.store');

// Comentarios

Route::post('/novela/{id}/capitulo/{id2}', novelaController::class . '@enviarComentario')->middleware(['auth', 'verified'])->name('comentario.enviar');

Route::delete('/novela/{id}/capitulo/{id2}', novelaController::class . '@borrarComentario')->middleware(['auth', 'verified'])->name('comentario.borrar');

Route::patch('/novela/{id}/capitulo/{id2}', novelaController::class . '@editarComentario')->middleware(['auth', 'verified'])->name('comentario.editar');

// Mensajes

Route::post('/usuario/{id}', mensajeController::class . '@enviarMensaje')->middleware(['auth', 'verified'])->name('mensaje.enviar');

Route::delete('/usuario/{id}', mensajeController::class . '@borrarMensaje')->middleware(['auth', 'verified'])->name('mensaje.borrar');

Route::patch('/usuario/{id}', mensajeController::class . '@editarMensaje')->middleware(['auth', 'verified'])->name('mensaje.editar');

// Usuarios

Route::get('/usuario/{id}', ProfileController::class . '@mostrarUsuario')->name('profile.show') ;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
