<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductosController;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::get('user-profile', 'App\Http\Controllers\AuthController@userProfile');
    
    Route::post('login', 'App\Http\Controllers\AuthController@login'); 

});

Route::apiResource('user-permisos',       App\Http\Controllers\UsersRolesController::class);
Route::apiResource('modulos',             App\Http\Controllers\ModuloController::class);
Route::apiResource('roles',               App\Http\Controllers\RolesController::class);
Route::apiResource('roles-permisos',      App\Http\Controllers\RolesPermisosModulosController::class);
Route::apiResource('acciones-permisos',   App\Http\Controllers\AccionesPermisosController::class);

Route::apiResource('tipo-examenes',       App\Http\Controllers\TipoExamenController::class);
Route::apiResource('tipo-doc',            App\Http\Controllers\TipoDocumentoController::class);

Route::apiResource('personas',            App\Http\Controllers\PersonasController::class);
Route::apiResource('clinicas',            App\Http\Controllers\ClinicaController::class);

Route::apiResource('examenes',            App\Http\Controllers\ExamenesController::class);  
Route::apiResource('examenes-adjuntos',   App\Http\Controllers\ExamenEstadoAdjuntosController::class); 
Route::apiResource('examenes-estado',     App\Http\Controllers\EstadoExamenController::class);  

Route::apiResource('departamentos',       App\Http\Controllers\DepartamentosController::class); 
Route::apiResource('ciudades',            App\Http\Controllers\CiudadesController::class); 


 

