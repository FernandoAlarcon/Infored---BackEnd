<?php

namespace App\Http\Controllers;

use App\Models\usersRoles;  
use App\Models\roles_permisos_modulos;
use Illuminate\Http\Request;

class UsersRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $id_user  = $request->input('id_user');
            $action   = $request->input('action');
            

            $rol      = usersRoles::where( 'user_id','=', $id_user)->get();
            $permisos = roles_permisos_modulos::where('rol_id','=',$rol[0]->rol_id)
                                                ->join('modulos as M','roles_permisos_modulos.modulo_id','=','M.id')->get();
            
            if($action == 'OwnRollData'){
                $OwnRoll = usersRoles::where( 'user_id','=', $id_user)
                                      ->join( 'roles as R','users_roles.rol_id','=','R.id')
                                      ->select( 
                                        'R.name AS Roll',
                                        'R.id   AS IdRoll', 
                                      )
                                      ->get();
                return[
                    'status'   => true,
                    'rollData' => $OwnRoll
                ];
                                                
            }

            return[
                'status'   => true,
                'permisos' => $permisos
            ];

        } catch (\Exception  $e) {
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\usersRoles  $usersRoles
     * @return \Illuminate\Http\Response
     */
    public function show(usersRoles $usersRoles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\usersRoles  $usersRoles
     * @return \Illuminate\Http\Response
     */
    public function edit(usersRoles $usersRoles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\usersRoles  $usersRoles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_roll)
    {
        $id_user  = $request->input('id_user');

        // return [
        //     'id_user' => $id_user,
        //     'rol_id'  => $id_roll
        // ];
        $user_roll = usersRoles::where('user_id', '=', $id_user)
                            //->get();                      
                            ->limit(1)
                            ->update(['rol_id' => $id_roll]);

        
        //return $user_rolls = usersRoles::where('user_id', '=', $id_user)->get();                 

        if( isset($user_roll) ){
            $status = true;
        }else{
            $status = false;
        }

        return [
            'status' => $status
        ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\usersRoles  $usersRoles
     * @return \Illuminate\Http\Response
     */
    public function destroy(usersRoles $usersRoles)
    {
        //
    }
}
