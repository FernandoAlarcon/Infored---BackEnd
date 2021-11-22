<?php

namespace App\Http\Controllers;

use App\Models\Ciudades;
use Illuminate\Http\Request;

class CiudadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id_departamento = $request->input('departamento'); 
        $ciudades        = Ciudades::where('id_departamento','=', $id_departamento)->get();
        
        return [
            'ciudades' => $ciudades
        ];
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
     * @param  \App\Models\Ciudades  $ciudades
     * @return \Illuminate\Http\Response
     */
    public function show(Ciudades $ciudades)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ciudades  $ciudades
     * @return \Illuminate\Http\Response
     */
    public function edit(Ciudades $ciudades)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ciudades  $ciudades
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ciudades $ciudades)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ciudades  $ciudades
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ciudades $ciudades)
    {
        //
    }
}
