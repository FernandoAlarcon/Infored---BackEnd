<?php

namespace App\Http\Controllers;

use App\Models\roles; 
use App\Models\roles_permisos_modulos;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        try {

            $id_roll = $request->input('id_roll');
            $IdUser  = $request->input('IdUser');
            $data    = $request->input('data');

            if($id_roll){
                $roles = roles::where('id', $id_roll)->get();
            }else if($data){
                $roles = roles::where('name', 'like', '%'.$data.'%')->
                                orWhere('descripcion', 'like', '%'.$data.'%')->get();
            }else{
                $roles = roles::all();
            }

            if(isset($roles)){
                $status = true;
            }else{
                $status = false;
            }

            return [
                'status' => $status,
                'roles'  => $roles
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
     * @param  \App\Models\roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show(roles $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function edit(roles $roles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, roles $roles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy(roles $roles)
    {
        //
    }
}
