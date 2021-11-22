<?php

namespace App\Http\Controllers;

use App\Models\acciones_permisos;
use App\Models\roles_permisos_modulos;
use App\Models\acciones;


use Illuminate\Http\Request;

class AccionesPermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            
            $IdRoll   = $request->input('IdRoll'); 
            $IdModulo = $request->input('IdModulo'); 
            $acciones = acciones::all();
            $accionesArray = array();
            $c = 0;

            if( isset($IdRoll) && isset($IdModulo) ){
                
                $roles_Permisos = roles_permisos_modulos::
                          where('rol_id'   , '=', $IdRoll)    
                        ->where('modulo_id', '=', $IdModulo )->get();

                if( count($roles_Permisos) != 0){ 

                    foreach ( $acciones as $accions ) {
                        
                        $acionesPermisos = acciones_permisos::where('permiso', '=', $roles_Permisos[0]->id)
                                                            ->where('accion',  '=', $accions->id)->get();

                        if( count($acionesPermisos) != 0){
                            $checked = "checked";
                            $status  = true;
                        }else{
                            $checked = "";
                            $status  = false;
                        }

                        $accionesArray[$c]['id']       = $accions->id;
                        $accionesArray[$c]['accion']   = $accions->accion;
                        $accionesArray[$c]['checked']  = $checked; 
                        $accionesArray[$c]['disable']  = false; 
                        $accionesArray[$c]['status']   = $status; 

                        $c++;
                    
                    }/// FINAL FOREACH


                }else{
                    foreach ($acciones as $accions ) {
                        
                        $accionesArray[$c]['id']       = $accions->id;
                        $accionesArray[$c]['accion']   = $accions->accion;
                        $accionesArray[$c]['checked']  = ""; 
                        $accionesArray[$c]['disable']  = true; 
                        $accionesArray[$c]['status']   = false; 

                        $c++;
                    
                    }/// FINAL FOREACH
                }

                return [
                    'status'    => true,
                    'acciones'  => $accionesArray
                ];

            }


            $accionespermisos = acciones_permisos::all();


        }catch (\Exception  $e) {
            return $this->capturar($e, 'Error al consultar');            
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            
            $IdRoll   = $request->input('IdRoll'); 
            $IdModulo = $request->input('IdModulo'); 
            $IdAccion = $request->input('IdAccion'); 

            $roles_Permisos = roles_permisos_modulos::where('rol_id'   , '=', $IdRoll)    
                                                    ->where('modulo_id', '=', $IdModulo )->get();

            if( count($roles_Permisos) != 0 ){
                
                $id_permiso  = $roles_Permisos[0]->id;
                $getPermisos = acciones_permisos::where('permiso', '=', $id_permiso)    
                                                ->where('accion' , '=', $IdAccion )->get();

                if( count($getPermisos) == 0 ){

                    $acciones_permisos = new acciones_permisos();
                    $acciones_permisos->permiso = $id_permiso;
                    $acciones_permisos->accion  = $IdAccion;
                    $acciones_permisos->estado  = 'Activado';
                    $acciones_permisos->save();

                }else{
                    //return 'delete';
                    //$acciones_permisos = DB::table('acciones_permisos')->where('permiso', '=', $id_permiso)->where('accion', '=', $IdAccion)->delete();
                    $acciones_permisos = acciones_permisos::where('permiso', '=', $id_permiso)->where('accion', '=', $IdAccion)->delete();

                }

                if ($acciones_permisos) {
                    return[
                        'status' => true
                    ];    
                }else{
                    return[
                        'status' => false,
                        'cause'  => 'Hubo problema para crear'
                    ];
                }

                

            }else{
                return[
                    'status' => false,
                    'cause'  => 'Debes primero crear el permiso'
                ];
            }
                                                    
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\acciones_permisos  $acciones_permisos
     * @return \Illuminate\Http\Response
     */
    public function show(acciones_permisos $acciones_permisos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\acciones_permisos  $acciones_permisos
     * @return \Illuminate\Http\Response
     */
    public function edit(acciones_permisos $acciones_permisos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\acciones_permisos  $acciones_permisos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, acciones_permisos $acciones_permisos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\acciones_permisos  $acciones_permisos
     * @return \Illuminate\Http\Response
     */
    public function destroy(acciones_permisos $acciones_permisos)
    {
        //
    }
}
