<?php

namespace App\Http\Controllers;

use App\Models\roles_permisos_modulos;
use App\Models\modulo;
use App\Models\roles;
use App\Models\acciones_permisos;
use App\Models\usersRoles; 

use Illuminate\Http\Request;

class RolesPermisosModulosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $id_roll   = $request->input('id_roll');
            $id_modulo = $request->input('id_modulo');
            $data      = $request->input('data');

            if( isset($id_roll) ){

                $modulos        = modulo::all();
                $roll           = roles::find($id_roll);
                $Permisos       = roles_permisos_modulos::where('rol_id', $id_roll)->get();
                $RolessUsuarios = usersRoles::join('users AS U','users_roles.user_id','=','U.id' )
                                            ->join('roles AS R','R.id','=', 'users_roles.rol_id' )
                                            ->select(
                                                'U.name AS Username',
                                                'R.name AS Roll',
                                                'R.id   AS IdRoll',
                                                'R.descripcion'
                                            )
                                            ->where('users_roles.rol_id', $id_roll)->get();
                $rolesPermisos  = array();
                $c                = 0;
                foreach ($modulos as $modulo) {
                   
                    $roles_Permisos = roles_permisos_modulos::
                        where('rol_id'   , '=', $id_roll)    
                        ->where('modulo_id', '=', $modulo->id)->get();

                    if( count($roles_Permisos) != 0){
                        $checked = "checked";
                    }else{
                        $checked = "";
                    }
 
                    $rolesPermisos[$c]['id']       = $modulo->id;
                    $rolesPermisos[$c]['modulo']   = $modulo->nombre;
                    $rolesPermisos[$c]['checked']  = $checked; 

                    $c++;
                }

            }else if($id_modulo){
                $rolesPermisos = roles_permisos_modulos::where('modulo_id',$id_modulo)->get();
            }else if($data){
                $rolesPermisos = roles_permisos_modulos::where('name', 'like', '%'.$data.'%')->
                                orWhere('descripcion', 'like', '%'.$data.'%')->get();
            }else{
                $rolesPermisos = roles_permisos_modulos::all();
            }

            if(isset($rolesPermisos)){
                $status = true;
            }else{
                $status = false;
            }

            return [
                'status'         => $status,
                'permisos'       => $rolesPermisos,
                'rollName'       => $roll->name,
                'usuarios_roll'  => $RolessUsuarios
            ];

         } catch (\Exception  $e) {
            return $this->capturar($e, 'Error al consultar');            
        }
    }
 
    public function store(Request $request)
    {
        try {
            $id_roll   = $request->input('IdRoll');
            $id_modulo = $request->input('IdModulo');

            $roles_Permisos = roles_permisos_modulos::
                          where('rol_id'   , '=', $id_roll)    
                        ->where('modulo_id', '=', $id_modulo )->get();

            if( count($roles_Permisos) == 0) {

                $newPermiso = new roles_permisos_modulos();
                $newPermiso->rol_id    = $id_roll;
                $newPermiso->modulo_id = $id_modulo;
                $newPermiso->save();               

                if($newPermiso){
                    $status = true;
                }else{
                    $status = false;
                }

            }else{
  

                $DeletePermiso = roles_permisos_modulos::find($roles_Permisos[0]->id);
                $DeletePermiso->delete();
                $acciones = DB::table('acciones_permisos')->where('permiso', '=', $roles_Permisos[0]->id)->delete();



                if($DeletePermiso){
                    $status = true;
                }else{
                    $status = false;
                }

            }

            return [ 'status' => $status ];


        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\roles_permisos_modulos  $roles_permisos_modulos
     * @return \Illuminate\Http\Response
     */
    public function show(roles_permisos_modulos $roles_permisos_modulos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\roles_permisos_modulos  $roles_permisos_modulos
     * @return \Illuminate\Http\Response
     */
    public function edit(roles_permisos_modulos $roles_permisos_modulos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\roles_permisos_modulos  $roles_permisos_modulos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, roles_permisos_modulos $roles_permisos_modulos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\roles_permisos_modulos  $roles_permisos_modulos
     * @return \Illuminate\Http\Response
     */
    public function destroy(roles_permisos_modulos $roles_permisos_modulos)
    {
        //
    }
}
