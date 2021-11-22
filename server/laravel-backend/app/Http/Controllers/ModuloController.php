<?php

namespace App\Http\Controllers;

use App\Models\modulo;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $id_roll = $request->input('id_modulo');
            $data    = $request->input('data');

            if($id_roll){
                $modulo = modulo::where('id', $id_roll)->get();
            }else if($data){
                $modulo = modulo::where('nombre', 'like', '%'.$data.'%')->get();
            }else{
                $modulo = modulo::all();
            }

            if(isset($modulo)){
                $status = true;
            }else{
                $status = false;
            }

            return [
                'status'   => $status,
                'modulos'  => $modulo
            ];

         } catch (\Throwable $th) {
             //throw $th;
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
     * @param  \App\Models\modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function show(modulo $modulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function edit(modulo $modulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, modulo $modulo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(modulo $modulo)
    {
        //
    }
}
