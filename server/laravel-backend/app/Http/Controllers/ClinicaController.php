<?php

namespace App\Http\Controllers;

use App\Models\clinica;
use Illuminate\Http\Request;

class ClinicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {       

       
        try {
              

            $clinicas = clinica::join( 'ciudades AS C', 'clinicas.ciudad_id', '=', 'C.id' )
                                    ->select('*')
                                    ->select(
                                        'clinicas.id     AS id_clinica',
                                        'clinicas.nombre AS NombreClinica',
                                        'C.nombre        AS Ciudad'
                                    )
                                    ->get();

            return [ 'clinicas' => $clinicas ];
            
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
     * @param  \App\Models\clinica  $clinica
     * @return \Illuminate\Http\Response
     */
    public function show(clinica $clinica)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\clinica  $clinica
     * @return \Illuminate\Http\Response
     */
    public function edit(clinica $clinica)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\clinica  $clinica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, clinica $clinica)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\clinica  $clinica
     * @return \Illuminate\Http\Response
     */
    public function destroy(clinica $clinica)
    {
        //
    }
}
