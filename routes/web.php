<?php
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
    if (session()->exists('userLogin')) {
        return redirect("home")->withSuccess('You have signed-in');
    }else{
        return view('auth.login');	
    }
});

Route::get('/home', 'AuthController@home')->middleware('checkUserRole');
Route::post('/login', 'AuthController@login')->name('login.login');
Route::get('/signout', 'AuthController@signOut')->name('signout')->middleware('checkUserRole');


Route::post('/menu/createPostre', 'MenuController@createPostre')->middleware('checkUserRole');
Route::post('/menu/desactivarPostre', 'MenuController@desactivarPostre')->middleware('checkUserRole');
Route::post('/menu/updateMenu', 'MenuController@updateMenu')->middleware('checkUserRole');
Route::post('/menu/createMenu', 'MenuController@createMenu')->middleware('checkUserRole');
Route::get('/menu/getMenu/{fecha}', 'MenuController@getMenu')->middleware('checkUserRole');
Route::get('/menu/getPostre', 'MenuController@getPostre')->middleware('checkUserRole');
Route::get('/menu/showPedido', 'MenuController@showPedido')->middleware('checkUserRole');
Route::get('/menu/showPedidosDia', 'MenuController@showPedidosDia')->middleware('checkUserRole');
Route::get('/menu/showUserMenu', 'MenuController@showUserMenu')->middleware('checkUserRole');
Route::get('/menu/showMenu', 'MenuController@showMenu')->middleware('checkUserRole');
Route::get('/menu/getPedidos/{fecha}', 'MenuController@getPedidos')->middleware('checkUserRole');
Route::get('/menu/getPedidosDia/{fecha}/{hora}', 'MenuController@getPedidosDia')->middleware('checkUserRole');
Route::get('/menu/getPedidoInvitado/{legajo}/{fecha}', 'MenuController@getPedidoInvitado')->middleware('checkUserRole');
Route::resource('/menu', 'MenuController')->middleware('checkUserRole');


Route::get('/colaborador', 'InvitadoController@index')->name('colaborador');;
Route::get('/colaborador/puntuacion', 'InvitadoController@puntuacion');
Route::get('/colaborador/getValorarMes/{id}', 'InvitadoController@getValorarMes');
Route::get('/colaborador/getMenu/{legajo}', 'InvitadoController@getMenu');
Route::post('/colaborador/postEnvio', 'InvitadoController@postEnvio');
Route::post('/colaborador/postMensual', 'InvitadoController@postMensual');
Route::post('/colaborador/loginInvitado', 'InvitadoController@loginInvitado');
Route::post('/colaborador/postPuntuacion', 'InvitadoController@postPuntuacion');
Route::resource('/colaborador', 'InvitadoController');

Route::get('/rrhh/getDatosMesColaboradores/{mes}', 'RrhhController@getDatosMesColaboradores')->middleware('checkUserRole');
Route::get('/rrhh/generarQr/{qr}/{nombreCompleto}', 'RrhhController@generarQr')->middleware('checkUserRole');
Route::get('/rrhh/crearColaborador', 'RrhhController@crearColaborador')->middleware('checkUserRole');
Route::get('/rrhh/mesColaborador', 'RrhhController@mesColaborador')->middleware('checkUserRole'); 
Route::get('/rrhh/editarColaborador', 'RrhhController@editarColaborador')->middleware('checkUserRole');
Route::get('/rrhh/showPuntuacion', 'RrhhController@showPuntuacion')->middleware('checkUserRole');
Route::get('/rrhh/showPuntuacionMensual', 'RrhhController@showPuntuacionMensual')->middleware('checkUserRole');
Route::get('/rrhh/getPuntuacion/{fecha}', 'RrhhController@getPuntuacion')->middleware('checkUserRole');
Route::get('/rrhh/getPuntuacionMensual/{mes}', 'RrhhController@getPuntuacionMensual')->middleware('checkUserRole');
Route::get('/rrhh/getColaborador/{legajo}', 'RrhhController@getColaborador')->middleware('checkUserRole');
Route::post('/rrhh/postCrearColaborador', 'RrhhController@postCrearColaborador')->middleware('checkUserRole');
Route::post('/rrhh/postUpdatedColaborador', 'RrhhController@postUpdatedColaborador')->middleware('checkUserRole');
Route::resource('/rrhh', 'RrhhController')->middleware('checkUserRole');