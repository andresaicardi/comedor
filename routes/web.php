<?php
// use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
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
    if (session()->exists('userLogin') && session('rol')=='Colaborador') {
        return view('colaborador');
    }else if (session()->exists('userLogin')) {
        return view('home');
    }else{
        return view('auth.login');	
    }
});

Route::get('/dashboard', 'App\Http\Controllers\AuthController@dashboard');
Route::post('/login', 'App\Http\Controllers\AuthController@login')->name('login.login');
Route::get('/signout', 'App\Http\Controllers\AuthController@signOut')->name('signout');


Route::post('/menu/createPostre', 'App\Http\Controllers\MenuController@createPostre')->middleware('checkUserRole');
Route::post('/menu/desactivarPostre', 'App\Http\Controllers\MenuController@desactivarPostre')->middleware('checkUserRole');
Route::post('/menu/updateMenu', 'App\Http\Controllers\MenuController@updateMenu')->middleware('checkUserRole');
Route::post('/menu/createMenu', 'App\Http\Controllers\MenuController@createMenu')->middleware('checkUserRole');
Route::get('/menu/getMenu/{fecha}', 'App\Http\Controllers\MenuController@getMenu')->middleware('checkUserRole');
Route::get('/menu/getPostre', 'App\Http\Controllers\MenuController@getPostre')->middleware('checkUserRole');
Route::get('/menu/showPedido', 'App\Http\Controllers\MenuController@showPedido')->middleware('checkUserRole');
Route::get('/menu/showPedidosDia', 'App\Http\Controllers\MenuController@showPedidosDia')->middleware('checkUserRole');
Route::get('/menu/showUserMenu', 'App\Http\Controllers\MenuController@showUserMenu')->middleware('checkUserRole');
Route::get('/menu/showMenu', 'App\Http\Controllers\MenuController@showMenu')->middleware('checkUserRole');
Route::get('/menu/getPedidos/{fecha}', 'App\Http\Controllers\MenuController@getPedidos')->middleware('checkUserRole');
Route::get('/menu/getPedidosDia/{fecha}/{hora}', 'App\Http\Controllers\MenuController@getPedidosDia')->middleware('checkUserRole');
Route::get('/menu/getPedidoInvitado/{legajo}/{fecha}', 'App\Http\Controllers\MenuController@getPedidoInvitado')->middleware('checkUserRole');
Route::resource('/menu', 'App\Http\Controllers\MenuController')->middleware('checkUserRole');


Route::get('/colaborador', 'App\Http\Controllers\InvitadoController@index');
Route::get('/colaborador/puntuacion', 'App\Http\Controllers\InvitadoController@puntuacion');
Route::get('/colaborador/getValorarMes/{id}', 'App\Http\Controllers\InvitadoController@getValorarMes');
Route::get('/colaborador/getMenu/{legajo}', 'App\Http\Controllers\InvitadoController@getMenu');
Route::post('/colaborador/postEnvio', 'App\Http\Controllers\InvitadoController@postEnvio');
Route::post('/colaborador/postMensual', 'App\Http\Controllers\InvitadoController@postMensual');
Route::post('/colaborador/loginInvitado', 'App\Http\Controllers\InvitadoController@loginInvitado');
Route::post('/colaborador/postPuntuacion', 'App\Http\Controllers\InvitadoController@postPuntuacion');
Route::resource('/colaborador', 'App\Http\Controllers\InvitadoController');

Route::get('/rrhh/getDatosMesColaboradores/{mes}', 'App\Http\Controllers\RrhhController@getDatosMesColaboradores')->middleware('checkUserRole');
Route::get('/rrhh/generarQr/{qr}/{nombreCompleto}', 'App\Http\Controllers\RrhhController@generarQr')->middleware('checkUserRole');
Route::get('/rrhh/crearColaborador', 'App\Http\Controllers\RrhhController@crearColaborador')->middleware('checkUserRole');
Route::get('/rrhh/mesColaborador', 'App\Http\Controllers\RrhhController@mesColaborador')->middleware('checkUserRole'); 
Route::get('/rrhh/editarColaborador', 'App\Http\Controllers\RrhhController@editarColaborador')->middleware('checkUserRole');
Route::get('/rrhh/showPuntuacion', 'App\Http\Controllers\RrhhController@showPuntuacion')->middleware('checkUserRole');
Route::get('/rrhh/showPuntuacionMensual', 'App\Http\Controllers\RrhhController@showPuntuacionMensual')->middleware('checkUserRole');
Route::get('/rrhh/getPuntuacion/{fecha}', 'App\Http\Controllers\RrhhController@getPuntuacion')->middleware('checkUserRole');
Route::get('/rrhh/getPuntuacionMensual/{mes}', 'App\Http\Controllers\RrhhController@getPuntuacionMensual')->middleware('checkUserRole');
Route::get('/rrhh/getColaborador/{legajo}', 'App\Http\Controllers\RrhhController@getColaborador')->middleware('checkUserRole');
Route::post('/rrhh/postCrearColaborador', 'App\Http\Controllers\RrhhController@postCrearColaborador')->middleware('checkUserRole');
Route::post('/rrhh/postUpdatedColaborador', 'App\Http\Controllers\RrhhController@postUpdatedColaborador')->middleware('checkUserRole');
Route::resource('/rrhh', 'RrhhController')->middleware('checkUserRole');