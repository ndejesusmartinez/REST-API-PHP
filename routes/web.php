<?php

use App\Http\Controllers\correosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\apiNasa;
use App\Http\Controllers\apiMArvel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('inicio');
});
//Route::post('/login',[RutasController::class,'sesion'])->name('iniciarSesion');
Route::get('/api/correos',[correosController::class,'leerCorreos']);


Route::post('/api/asignadorReserva',[aisgnadorReservas::class,'asignarReservas']);

Route::get('/api/nasa/astronomyPictureOfTheDay',[apiNasa::class,'astronomyPictureOfTheDay']);

Route::get('/api/marvel/heroes',[apiMArvel::class,'marvel']);

Route::get('/api/marvel/event',[apiMArvel::class,'eventos']);

Route::get('/api/dian',[apiMArvel::class,'dian']);