<?php

namespace App\Http\Controllers;

use App\Models\estadoExamen;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class EstadoExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        try {
 
            $roll    = $request->input('roll'); 
            $id_user = $request->input('id_user');
            $mood    = $request->input('mood');
            $data    = $request->input('data');
            $limit   = 100;
            /// mood = 1 es para formato de calendario
            /// mood = 2 es para formato de listar basico

           if ( $mood == '1' ) {
                if($roll == 'Tecnico'){

                    $getCitas = estadoExamen::join('examenes     AS E',  'E.id_estado_examen',             '=',  'estado_examen.id')
                                            ->join('tipo_examen  AS TE', 'E.id_tipo_examen',   '=',  'TE.id')
                                            ->join('personas     AS P1',   'E.medico_id',                    '=', 'P1.id')
                                            ->join('personas     AS P2',   'E.tecnico_id',                   '=', 'P2.id')
                                            ->join('personas     AS P3',   'E.paciente_id',                  '=', 'P3.id')
                                            
                                            ->join('users        AS US',   'US.id',                          '=', 'P2.user_id') 
                                            ->where('US.id','=',$id_user) 
                                            
                                            ->select(
                                                'TE.nombre AS title',                                        
                                                // DB::raw("DATE_FORMAT(E.fecha_inicio, '%a %b %c %Y %H-%i-%s') AS start" ),
                                                // DB::raw("DATE_FORMAT(E.fecha_fin,    '%D %M %n %Y %H-%i-%s') AS end"   ),
                                                DB::raw("DATE_FORMAT(E.fecha_inicio, '%Y-%m-%d %H:%i:%s') AS start" ),
                                                DB::raw("DATE_FORMAT(E.fecha_fin,    '%Y-%m-%d %H:%i:%s') AS end"   ),
                                                'estado_examen.color AS color',
                                        
                                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"), 
                                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                            
                                            )->orderBy('E.id','DESC')
                                            ->get();

                } else if($roll == 'Medico'){

                    $getCitas = estadoExamen::join('examenes     AS E',  'E.id_estado_examen',             '=',  'estado_examen.id')
                                            ->join('tipo_examen  AS TE', 'E.id_tipo_examen',   '=',  'TE.id')
                                            ->join('personas     AS P1',   'E.medico_id',                    '=', 'P1.id')
                                            ->join('personas     AS P2',   'E.tecnico_id',                   '=', 'P2.id')
                                            ->join('personas     AS P3',   'E.paciente_id',                  '=', 'P3.id')

                                            ->join('users        AS US',   'US.id',                          '=', 'P1.user_id') 
                                            ->where('US.id','=',$id_user) 
                                            
                                            ->select(
                                                'TE.nombre AS title',                                        
                                                // DB::raw("DATE_FORMAT(E.fecha_inicio, '%a %b %c %Y %H-%i-%s') AS start" ),
                                                // DB::raw("DATE_FORMAT(E.fecha_fin,    '%D %M %n %Y %H-%i-%s') AS end"   ),
                                                DB::raw("DATE_FORMAT(E.fecha_inicio, '%Y-%m-%d %H:%i:%s') AS start" ),
                                                DB::raw("DATE_FORMAT(E.fecha_fin,    '%Y-%m-%d %H:%i:%s') AS end"   ),
                                                'estado_examen.color AS color',
                                     
                                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"), 
                                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                            
                                            )->orderBy('E.id','DESC')
                                            ->get();
                }else {

                    //$roll == 'Secretaria'
                    $getCitas = estadoExamen::join('examenes     AS E',  'E.id_estado_examen',             '=',  'estado_examen.id')
                                            ->join('tipo_examen  AS TE', 'E.id_tipo_examen',   '=',  'TE.id')
                                            ->join('personas     AS P1',   'E.medico_id',                    '=', 'P1.id')
                                            ->join('personas     AS P2',   'E.tecnico_id',                   '=', 'P2.id')
                                            ->join('personas     AS P3',   'E.paciente_id',                  '=', 'P3.id')
                                            ->join('users        AS US',   'US.id',                          '=', 'P2.user_id') 
                                            
                                            ->select(
                                                
                                            'TE.nombre AS title',                                        
                                            // DB::raw("DATE_FORMAT(E.fecha_inicio, '%a %b %c %Y %H-%i-%s') AS start" ),
                                            // DB::raw("DATE_FORMAT(E.fecha_fin,    '%D %M %n %Y %H-%i-%s') AS end"   ),
                                            DB::raw("DATE_FORMAT(E.fecha_inicio, '%Y-%m-%d %H:%i:%s') AS start" ),
                                            DB::raw("DATE_FORMAT(E.fecha_fin,    '%Y-%m-%d %H:%i:%s') AS end"   ),
                                            'estado_examen.color AS color',
 
                                            DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"), 
                                            DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                            DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                            
                                            )->orderBy('E.id','DESC')
                                            ->get();
                }
           }elseif ($mood == '2') {
                
                    if($roll == 'Tecnico'){

                        if ( isset($data) ) {
                            
                            $getCitas = estadoExamen::join('examenes     AS E',    'E.id_estado_examen',  '=',  'estado_examen.id')
                            ->join('tipo_examen  AS TE',   'E.id_tipo_examen',    '=',  'TE.id')
                            ->join('clinicas     AS CL',   'E.id_clinica',        '=',  'CL.id')
                            ->join('personas     AS P1',   'E.medico_id',         '=',  'P1.id')
                            ->join('personas     AS P2',   'E.tecnico_id',        '=',  'P2.id')
                            ->join('personas     AS P3',   'E.paciente_id',       '=',  'P3.id')
                            
                            ->join('users        AS US',   'US.id',               '=', 'P2.user_id') 

                            ->where(static function ($query) use ($data) {
                                $query->where('P3.apellidos'   , 'LIKE', "%{$data}%")
                                      ->orWhere('P3.nombres'   , 'LIKE', "%{$data}%")
                                      ->orWhere('P2.apellidos' , 'LIKE', "%{$data}%")
                                      ->orWhere('P2.nombres'   , 'LIKE', "%{$data}%")
                                      ->orWhere('P1.apellidos' , 'LIKE', "%{$data}%")
                                      ->orWhere('P1.nombres'   , 'LIKE', "%{$data}%")
                                      ->orWhere('E.descripcion'  , 'LIKE', "%{$data}%")
                                      ->orWhere('CL.nombre'    , 'LIKE', "%{$data}%")
                                      ->orWhere('TE.nombre'    , 'LIKE', "%{$data}%")
                                      ->orWhere('P3.dni'       , 'LIKE', "%{$data}%")
                                      ->orWhere('P3.correo'    , 'LIKE', "%{$data}%");
                            })

                            ->where('US.id','=',$id_user) 
                            ->select(
                            
                                'E.id AS IdExamen',
                                'E.fecha_inicio AS date',
                                'TE.nombre AS examen',                                        
                                
                                //DB::raw("DATE_FORMAT(E.fecha_inicio, '%a %b %c %Y %H-%i-%s') AS start" ), 

                                'estado_examen.id     AS id_estado',
                                'estado_examen.color  AS color',

                                'E.fecha_fin',
                                'estado_examen.nombre AS estado',
                                'CL.nombre AS clinica',       
                                
                                'P3.dni    AS dni_paciente',
                                'P3.correo AS correo',
                                
                                
                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"), 
                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                            
                            )->orderBy('E.id','DESC')
                            ->limit($limit)
                            ->get();

                        }else{

                            $getCitas = estadoExamen::join('examenes     AS E',    'E.id_estado_examen',  '=',  'estado_examen.id')
                            ->join('tipo_examen  AS TE',   'E.id_tipo_examen',    '=',  'TE.id')
                            ->join('clinicas     AS CL',   'E.id_clinica',        '=',  'CL.id')
                            ->join('personas     AS P1',   'E.medico_id',         '=',  'P1.id')
                            ->join('personas     AS P2',   'E.tecnico_id',        '=',  'P2.id')
                            ->join('personas     AS P3',   'E.paciente_id',       '=',  'P3.id')
                            
                            ->join('users        AS US',   'US.id',               '=', 'P2.user_id') 

                            ->where('US.id','=',$id_user) 
                            ->select(
                            
                                'E.id AS IdExamen',
                                'E.fecha_inicio AS date',
                                'TE.nombre AS examen',                                        
                                
                                //DB::raw("DATE_FORMAT(E.fecha_inicio, '%a %b %c %Y %H-%i-%s') AS start" ),
                                'estado_examen.color AS color',

                                'E.fecha_fin',
                                'estado_examen.nombre AS estado',
                                'CL.nombre AS clinica',                                        
                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"), 
                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                            
                            )->orderBy('E.id','DESC')
                            ->limit($limit)
                            ->get();

                        } //// END IF $data
                       
                    } else if($roll == 'Medico'){

                        if ( isset($data) ) {

                            $getCitas = estadoExamen::join('examenes     AS E',    'E.id_estado_examen',  '=',  'estado_examen.id')
                                                ->join('tipo_examen  AS TE',   'E.id_tipo_examen',    '=',  'TE.id')
                                                ->join('clinicas     AS CL',   'E.id_clinica',        '=',  'CL.id')
                                                ->join('personas     AS P1',   'E.medico_id',         '=',  'P1.id')
                                                ->join('personas     AS P2',   'E.tecnico_id',        '=',  'P2.id')
                                                ->join('personas     AS P3',   'E.paciente_id',       '=',  'P3.id')

                                                ->join('users        AS US',   'US.id',                '=', 'P1.user_id') 
                                                ->where('US.id','=',$id_user) 
                                                ->where(static function ($query) use ($data) {
                                                    $query->where('P3.apellidos'   , 'LIKE', "%{$data}%")
                                                          ->orWhere('P3.nombres'   , 'LIKE', "%{$data}%")
                                                          ->orWhere('P2.apellidos' , 'LIKE', "%{$data}%")
                                                          ->orWhere('P2.nombres'   , 'LIKE', "%{$data}%")
                                                          ->orWhere('P1.apellidos' , 'LIKE', "%{$data}%")
                                                          ->orWhere('P1.nombres'   , 'LIKE', "%{$data}%")
                                                          ->orWhere('E.descripcion'  , 'LIKE', "%{$data}%")
                                                          ->orWhere('CL.nombre'    , 'LIKE', "%{$data}%")
                                                          ->orWhere('TE.nombre'    , 'LIKE', "%{$data}%")
                                                          ->orWhere('P3.dni'       , 'LIKE', "%{$data}%")
                                                          ->orWhere('P3.correo'    , 'LIKE', "%{$data}%");
                                                })

                                                ->select(
                                                
                                                    'E.id AS IdExamen',
                                                    'E.fecha_inicio AS date',
                                                    'TE.nombre AS examen',                                        
                                                    
                                                    //DB::raw("DATE_FORMAT(E.fecha_inicio, '%a %b %c %Y %H-%i-%s') AS start" ),
                                                    'estado_examen.color AS color',

                                                    'estado_examen.id     AS id_estado',
                                                    'estado_examen.color  AS color',

                                                    'E.fecha_fin',
                                                    'estado_examen.nombre AS estado',
                                                    'CL.nombre AS clinica',                                        
                                                    DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"), 
                                                    DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                    DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                                
                                                )->orderBy('E.id','DESC')
                                                ->limit($limit)
                                                ->get();

                        }else{

                            $getCitas = estadoExamen::join('examenes     AS E',    'E.id_estado_examen',  '=',  'estado_examen.id')
                                                ->join('tipo_examen  AS TE',   'E.id_tipo_examen',    '=',  'TE.id')
                                                ->join('clinicas     AS CL',   'E.id_clinica',        '=',  'CL.id')
                                                ->join('personas     AS P1',   'E.medico_id',         '=',  'P1.id')
                                                ->join('personas     AS P2',   'E.tecnico_id',        '=',  'P2.id')
                                                ->join('personas     AS P3',   'E.paciente_id',       '=',  'P3.id')

                                                ->join('users        AS US',   'US.id',                          '=', 'P1.user_id') 
                                                ->where('US.id','=',$id_user) 
                                                
                                                ->select(
                                                
                                                    'E.id AS IdExamen',
                                                    'E.fecha_inicio AS date',
                                                    'TE.nombre AS examen',                                        
                                                    
                                                    //DB::raw("DATE_FORMAT(E.fecha_inicio, '%a %b %c %Y %H-%i-%s') AS start" ),
                                                    'estado_examen.color AS color',

                                                    'estado_examen.id     AS id_estado',
                                                    'estado_examen.color  AS color',

                                                    'E.fecha_fin',
                                                    'estado_examen.nombre AS estado',
                                                    'CL.nombre AS clinica',                                        
                                                    DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"), 
                                                    DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                    DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                                
                                                )->orderBy('E.id','DESC')
                                                ->limit($limit)
                                                ->get();

                        }//// END IF

                        
                    }else {
                        //return $data; // $roll == 'Secretaria';
                        if ( isset($data) ) { 

                            $getCitas = estadoExamen::join('examenes     AS E',    'E.id_estado_examen',  '=',  'estado_examen.id')
                                                ->join('tipo_examen  AS TE',   'E.id_tipo_examen',    '=',  'TE.id')
                                                ->join('clinicas     AS CL',   'E.id_clinica',        '=',  'CL.id')
                                                ->join('personas     AS P1',   'E.medico_id',         '=',  'P1.id')
                                                ->join('personas     AS P2',   'E.tecnico_id',        '=',  'P2.id')
                                                ->join('personas     AS P3',   'E.paciente_id',       '=',  'P3.id')
                                                
                                                ->where(static function ($query) use ($data) {
                                                    $query->where('P3.apellidos'    , 'LIKE', "%{$data}%")
                                                          ->orWhere('P3.nombres'    , 'LIKE', "%{$data}%")
                                                          ->orWhere('P2.apellidos'  , 'LIKE', "%{$data}%")
                                                          ->orWhere('P2.nombres'    , 'LIKE', "%{$data}%")
                                                          ->orWhere('P1.apellidos'  , 'LIKE', "%{$data}%")
                                                          ->orWhere('P1.nombres'    , 'LIKE', "%{$data}%")
                                                          ->orWhere('E.descripcion' , 'LIKE', "%{$data}%")
                                                          ->orWhere('CL.nombre'     , 'LIKE', "%{$data}%")
                                                          ->orWhere('TE.nombre'     , 'LIKE', "%{$data}%")
                                                          ->orWhere('P3.dni'        , 'LIKE', "%{$data}%")
                                                          ->orWhere('P3.correo'     , 'LIKE', "%{$data}%");
                                                })

                                                ->select(
                                                
                                                    'E.id AS IdExamen',
                                                    'E.fecha_inicio AS date',
                                                    'TE.nombre AS examen',                                        
                                                    
                                                    //DB::raw("DATE_FORMAT(E.fecha_inicio, '%a %b %c %Y %H-%i-%s') AS start" ),
                                                    
                                                    'E.fecha_fin',
                                                    'estado_examen.color  AS color',
                                                    'estado_examen.nombre AS estado',
                                                    'estado_examen.id     AS id_estado',

                                                    'estado_examen.color  AS color',

                                                    'CL.nombre AS clinica',                                        
                                                    DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"), 
                                                    DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                    DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                                
                                                )->orderBy('E.id','DESC') 
                                                ->limit($limit)
                                                ->get();

                        }else {
                            
                            $getCitas = estadoExamen::join('examenes     AS E',    'E.id_estado_examen',  '=',  'estado_examen.id')
                                                ->join('tipo_examen  AS TE',   'E.id_tipo_examen',    '=',  'TE.id')
                                                ->join('clinicas     AS CL',   'E.id_clinica',        '=',  'CL.id')
                                                ->join('personas     AS P1',   'E.medico_id',         '=',  'P1.id')
                                                ->join('personas     AS P2',   'E.tecnico_id',        '=',  'P2.id')
                                                ->join('personas     AS P3',   'E.paciente_id',       '=',  'P3.id')
                                                
                                                ->select(
                                                
                                                    'E.id AS IdExamen',
                                                    'E.fecha_inicio AS date',
                                                    'TE.nombre AS examen',                                        
                                                     
                                                    
                                                    'E.fecha_fin',

                                                    'estado_examen.nombre AS estado',
                                                    'estado_examen.color AS color',
                                                    
                                                    'estado_examen.id     AS id_estado',

                                                    'CL.nombre AS clinica',                                        
                                                    DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"), 
                                                    DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                    DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                                
                                                )->orderBy('E.id','DESC')
                                                ->limit($limit)
                                                ->get();
                            
                        }/// END IF ISSET

                    }
           }/// END IF 

            return [ 'citas' => $getCitas ];

            

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
     * @param  \App\Models\estadoExamen  $estadoExamen
     * @return \Illuminate\Http\Response
     */
    public function show(estadoExamen $estadoExamen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\estadoExamen  $estadoExamen
     * @return \Illuminate\Http\Response
     */
    public function edit(estadoExamen $estadoExamen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\estadoExamen  $estadoExamen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, estadoExamen $estadoExamen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\estadoExamen  $estadoExamen
     * @return \Illuminate\Http\Response
     */
    public function destroy($idExamen)
    {
        $examen = estadoExamen::find($idExamen);
        $examen->delete();

        return;
    }
}
