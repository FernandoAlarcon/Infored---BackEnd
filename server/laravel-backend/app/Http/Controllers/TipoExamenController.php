<?php

namespace App\Http\Controllers;

use App\Models\tipoExamen;
use Illuminate\Http\Request;

class TipoExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {   

            //return "data";
            
            $data = $request->input('data'); 

            if ($data) {
                $examenes = tipoExamen::select('*')->where('nombre','LIKE', '%'.$data.'%')
                                                   ->orWhere('descripcion','LIKE','%'.$data.'%')
                                                   ->get(); 
            }else{
                $examenes = tipoExamen::select('*')->get();
            }

            return [ 'TipoExamenes' => $examenes ];

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
     * @param  \App\Models\tipoExamen  $tipoExamen
     * @return \Illuminate\Http\Response
     */
    public function show(tipoExamen $tipoExamen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipoExamen  $tipoExamen
     * @return \Illuminate\Http\Response
     */
    public function edit(tipoExamen $tipoExamen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tipoExamen  $tipoExamen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tipoExamen $tipoExamen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tipoExamen  $tipoExamen
     * @return \Illuminate\Http\Response
     */
    public function destroy(tipoExamen $tipoExamen)
    {
        //
    }
}
