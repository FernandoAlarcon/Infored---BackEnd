<?php

namespace App\Http\Controllers;

use App\Models\tipoExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    
        //return $month = date("m");       
        try {   

            //return "data";
            
            $data = $request->input('data'); 
            $mood = $request->input('mood'); 

            if ($data) {
                $examenes = tipoExamen::select('*')->where('nombre','LIKE', '%'.$data.'%')
                                                   ->orWhere('descripcion','LIKE','%'.$data.'%')
                                                   ->get(); 
            }elseif ($mood == '1') {

                $month = date("F"); 
                $year  = date("Y");
        
                $date1 = $year.'-'.$month.'-01 00-00-00';
                $date2 = $year.'-'.$month.'-31 00-00-00';
                $examenesType = tipoExamen::all();
                $examenesData = DB::table('tipo_examen')
                                        ->join('examenes  AS E', 'E.id_tipo_examen',  '=', 'tipo_examen.id')
                                        ->select(
                                            'tipo_examen.nombre AS examen',
                                            'tipo_examen.id     AS IdExamen',
                                            'E.costo_examen     AS costo',
                                            //DB::raw('SUM(E.costo_examen) AS total'),
                                            DB::raw("DATE_FORMAT(E.fecha_inicio,'%Y') AS fecha_year"),
                                            DB::raw("DATE_FORMAT(E.fecha_inicio,'%M') AS mes_year")
                                        )                                         
                                        ->get()
                                        ->where( 'fecha_year', '=', $year  )
                                        ->where( 'mes_year',   '=', $month )
                                        ->groupBy('examen'); 
                                
                $examenes = [];                        
                foreach ($examenesType as $key => $examen) {
                   
                    $alias = strval($examen->nombre);
                    $suma  = 0;
        
                    
                    foreach ($examenesData[$alias] as $key => $data) {
                        $suma   = $suma + $data->costo;
                        $examen = $data->examen;
                    }
                    
                    $Data = [
                        'examen' => $examen,
                        'total'  => $suma
                    ]; 
        
                    array_push($examenes, $Data);
                }

                //return $examenes;
                        
                
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
