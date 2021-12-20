<?php

namespace App\Http\Controllers;

use App\Models\examenes;  
use App\Models\examenEstados; 
use App\Models\examenEstadoAdjuntos; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExamenesController extends Controller
{ 
    public function index(Request $request)
    {    
       
        try {
            
            $id_examen = $request->input('id_examen');
            $data      = $request->input('data');
            $mood      = $request->input('mood');
            $roll      = $request->input('roll'); 
            $id_user   = $request->input('id_user');


            $cantidad = $request->input('cantidad');
            if(!isset($cantidad)){
                $cantidad = 15;
            }

            if( isset($id_examen) && !isset($mood) ){ 

                if (isset($data)) { 

                    $examen = examenes::join('personas   AS P1',     'examenes.medico_id',      '=', 'P1.id')
                                        ->join('personas AS P2',     'examenes.tecnico_id',     '=', 'P2.id')
                                        ->join('personas AS P3',     'examenes.paciente_id',    '=', 'P3.id')
                                        ->join('tipo_examen AS TE',  'examenes.id_tipo_examen', '=', 'TE.id')
                                        ->join('clinicas    AS C' ,  'examenes.id_clinica' ,    '=', 'C.id')
                                        ->join('ciudades    AS CD1', 'P3.ciudad_id'        ,    '=', 'CD1.id')

                    ->where(static function ($query) use ($data) {
                        $query->where('P3.apellidos'   , 'LIKE', "%{$data}%")
                              ->orWhere('P3.nombres'   , 'LIKE', "%{$data}%")
                              ->orWhere('P2.apellidos' , 'LIKE', "%{$data}%")
                              ->orWhere('P2.nombres'   , 'LIKE', "%{$data}%")
                              ->orWhere('P1.apellidos' , 'LIKE', "%{$data}%")
                              ->orWhere('P1.nombres'   , 'LIKE', "%{$data}%")
                              ->orWhere('examenes.descripcion'  , 'LIKE', "%{$data}%")
                              ->orWhere('C.nombre'     , 'LIKE', "%{$data}%")
                              ->orWhere('TE.nombre'    , 'LIKE', "%{$data}%")
                              ->orWhere('P3.dni'       , 'LIKE', "%{$data}%")
                              ->orWhere('P3.correo'    , 'LIKE', "%{$data}%");
                    })
                    ->where('examenes.id_tipo_examen'  ,  '='  , $id_examen)
                    ->where('examenes.id_estado_examen',  '<>' , '1' )
                    ->select(
                        'examenes.id AS IdExamen',
                        'examenes.fecha_inicio',
                        'examenes.fecha_fin',
                        'TE.nombre AS examen',
                        'C.nombre  AS clinica',
                        'examenes.id_estado_examen',
                        'examenes.descripcion',
                        'examenes.costo_examen',
                        DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                        'P3.correo AS correo_paciente',
                        'P3.dni AS dni_paciente',
                        'CD1.nombre AS ciudad_paciente',
                        DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                        DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                    )
                    ->orderBy('examenes.id', 'ASC')
                    ->paginate($cantidad);
                    
                }else{

                    $examen = examenes::join('personas AS P1',    'examenes.medico_id',      '=', 'P1.id')
                                      ->join('personas AS P2',    'examenes.tecnico_id',     '=', 'P2.id')
                                      ->join('personas AS P3',    'examenes.paciente_id',    '=', 'P3.id')
                                      ->join('tipo_examen AS TE', 'examenes.id_tipo_examen', '=', 'TE.id')
                                      ->join('clinicas    AS C' , 'examenes.id_clinica' ,    '=', 'C.id')
                                      ->join('ciudades    AS CD1' ,'P3.ciudad_id'       ,    '=', 'CD1.id')

                    ->where('id_tipo_examen','=',$id_examen)
                    ->where('examenes.id_estado_examen',  '<>' , '1' )
                    ->select(

                        'examenes.id AS IdExamen',
                        'examenes.fecha_inicio',
                        'examenes.fecha_fin',
                        'TE.nombre AS examen',
                        'C.nombre  AS clinica',
                        'examenes.id_estado_examen',
                        'examenes.descripcion',
                        'examenes.costo_examen',
                        'CD1.nombre AS ciudad_paciente',
                        DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                        DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                        DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente"),
                        'P3.correo AS correo_paciente',
                        'P3.dni AS dni_paciente'

                    )
                    ->orderBy('examenes.id', 'ASC')
                    ->paginate($cantidad); 

                }/// END IF

                return [ 
                        'pagination' => [
                            'total'         => $examen->total(),
                            'current_page'  => $examen->currentPage(),
                            'per_page'      => $examen->perPage(),
                            'last_page'     => $examen->lastPage(),
                            'from'          => $examen->firstItem(),
                            'to'            => $examen->lastItem(),
                        ],
                        'examenes'          => $examen 
                    ];

            }elseif ( $mood == '1' ) {

                
                if ( isset($data) ) { 

                    if($roll == 'Tecnico'){

                        $examen = examenes::join('personas AS P1',    'examenes.medico_id',           '=', 'P1.id') 
                                            ->join('personas      AS P2',  'examenes.tecnico_id',     '=', 'P2.id')
                                            ->join('estado_examen AS EE',  'EE.id',                   '=', 'examenes.id_estado_examen')
                                            ->join('personas      AS P3',  'examenes.paciente_id',    '=', 'P3.id')
                                            ->join('tipo_examen   AS TE',  'examenes.id_tipo_examen', '=', 'TE.id')
                                            ->join('clinicas      AS C' ,  'examenes.id_clinica' ,    '=', 'C.id')
                                            ->join('ciudades      AS CD1', 'P3.ciudad_id'       ,     '=', 'CD1.id')
                                            ->join('users         AS US',  'US.id',                   '=', 'P2.user_id') 

    
                        ->where(static function ($query) use ($data) {
                            $query->where('P3.apellidos'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P2.apellidos' , 'LIKE', "%{$data}%")
                                  ->orWhere('P2.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P1.apellidos' , 'LIKE', "%{$data}%")
                                  ->orWhere('P1.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('examenes.descripcion'  , 'LIKE', "%{$data}%")
                                  ->orWhere('C.nombre'     , 'LIKE', "%{$data}%")
                                  ->orWhere('TE.nombre'    , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.dni'       , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.correo'    , 'LIKE', "%{$data}%");
                        })
                        ->where('examenes.id_tipo_examen'  , '=' , $id_examen)
                        ->where('US.id','=',$id_user) 
                        
                        ->where('EE.nombre', '<>', 'Programado' )
                        ->where('EE.nombre', '<>', 'Cancelado' )
    
                        ->select(
                            'examenes.id AS IdExamen',
                            'examenes.fecha_inicio',
                            'examenes.fecha_fin',
                            'TE.nombre AS examen',
                            'TE.id     AS tipo_examen',
    
                            'C.id  AS id_clinica',
                            'C.nombre  AS clinica',
                            'examenes.id_estado_examen',
                            'examenes.descripcion',
                            'examenes.costo_examen',
                            
                            'EE.color  AS color',
                            'EE.nombre AS estado',
                            'EE.id     AS id_estado',
    
                            'P3.correo AS correo_paciente',
                            'P3.dni AS dni_paciente',
                            'CD1.nombre AS ciudad_paciente',
                            'examenes.medico_id AS medico_id',
                            'examenes.tecnico_id AS tecnico_id',
                            'examenes.paciente_id AS paciente_id',
                            'examenes.descripcion AS descripcion',
    
                            DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                            DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                            DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                        )
                        ->orderBy('examenes.id', 'ASC')
                        ->take(100)
                        ->get();
                    
                    }else if($roll == 'Medico') {

                        $examen = examenes::join('personas AS P1',    'examenes.medico_id',          '=', 'P1.id') 
                                            ->join('personas      AS P2',    'examenes.tecnico_id',  '=', 'P2.id')
                                            ->join('estado_examen AS EE',    'EE.id',                '=', 'examenes.id_estado_examen')
                                            ->join('personas      AS P3',    'examenes.paciente_id', '=', 'P3.id')
                                            ->join('tipo_examen   AS TE', 'examenes.id_tipo_examen', '=', 'TE.id')
                                            ->join('clinicas      AS C' , 'examenes.id_clinica' ,    '=', 'C.id')
                                            ->join('ciudades      AS CD1' ,'P3.ciudad_id'       ,    '=', 'CD1.id')
                                            ->join('users         AS US',  'US.id',                  '=', 'P1.user_id') 

    
                        ->where(static function ($query) use ($data) {
                            $query->where('P3.apellidos'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P2.apellidos' , 'LIKE', "%{$data}%")
                                  ->orWhere('P2.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P1.apellidos' , 'LIKE', "%{$data}%")
                                  ->orWhere('P1.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('examenes.descripcion'  , 'LIKE', "%{$data}%")
                                  ->orWhere('C.nombre'     , 'LIKE', "%{$data}%")
                                  ->orWhere('TE.nombre'    , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.dni'       , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.correo'    , 'LIKE', "%{$data}%");
                        })
                        ->where('examenes.id_tipo_examen'  , '=' , $id_examen)
                        ->where('US.id','=',$id_user) 
                        
                        ->where('EE.nombre', '<>', 'Programado' )
                        ->where('EE.nombre', '<>', 'Cancelado' )
    
                        ->select(
                            'examenes.id AS IdExamen',
                            'examenes.fecha_inicio',
                            'examenes.fecha_fin',
                            'TE.nombre AS examen',
                            'TE.id     AS tipo_examen',
    
                            'C.id  AS id_clinica',
                            'C.nombre  AS clinica',
                            'examenes.id_estado_examen',
                            'examenes.descripcion',
                            'examenes.costo_examen',
                            
                            'EE.color  AS color',
                            'EE.nombre AS estado',
                            'EE.id     AS id_estado',
    
                            'P3.correo AS correo_paciente',
                            'P3.dni AS dni_paciente',
                            'CD1.nombre AS ciudad_paciente',
                            'examenes.medico_id AS medico_id',
                            'examenes.tecnico_id AS tecnico_id',
                            'examenes.paciente_id AS paciente_id',
                            'examenes.descripcion AS descripcion',
    
                            DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                            DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                            DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                        )
                        ->orderBy('examenes.id', 'ASC')
                        ->take(100)
                        ->get();

                    }else {

                        $examen = examenes::join('personas AS P1',    'examenes.medico_id',          '=', 'P1.id') 
                                            ->join('personas      AS P2',    'examenes.tecnico_id',  '=', 'P2.id')
                                            ->join('estado_examen AS EE',    'EE.id',                '=', 'examenes.id_estado_examen')
                                            ->join('personas      AS P3',    'examenes.paciente_id', '=', 'P3.id')
                                            ->join('tipo_examen   AS TE', 'examenes.id_tipo_examen', '=', 'TE.id')
                                            ->join('clinicas      AS C' , 'examenes.id_clinica' ,    '=', 'C.id')
                                            ->join('ciudades      AS CD1' ,'P3.ciudad_id'       ,    '=', 'CD1.id') 

    
                        ->where(static function ($query) use ($data) {
                            $query->where('P3.apellidos'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P2.apellidos' , 'LIKE', "%{$data}%")
                                  ->orWhere('P2.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P1.apellidos' , 'LIKE', "%{$data}%")
                                  ->orWhere('P1.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('examenes.descripcion'  , 'LIKE', "%{$data}%")
                                  ->orWhere('C.nombre'     , 'LIKE', "%{$data}%")
                                  ->orWhere('TE.nombre'    , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.dni'       , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.correo'    , 'LIKE', "%{$data}%");
                        })
                        ->where('examenes.id_tipo_examen'  , '=' , $id_examen)
                         
                        ->where('EE.nombre', '<>', 'Programado' )
                        ->where('EE.nombre', '<>', 'Cancelado' )
    
                        ->select(
                            'examenes.id AS IdExamen',
                            'examenes.fecha_inicio',
                            'examenes.fecha_fin',
                            'TE.nombre AS examen',
                            'TE.id     AS tipo_examen',
    
                            'C.id  AS id_clinica',
                            'C.nombre  AS clinica',
                            'examenes.id_estado_examen',
                            'examenes.descripcion',
                            'examenes.costo_examen',
                            
                            'EE.color  AS color',
                            'EE.nombre AS estado',
                            'EE.id     AS id_estado',
    
                            'P3.correo AS correo_paciente',
                            'P3.dni AS dni_paciente',
                            'CD1.nombre AS ciudad_paciente',
                            'examenes.medico_id AS medico_id',
                            'examenes.tecnico_id AS tecnico_id',
                            'examenes.paciente_id AS paciente_id',
                            'examenes.descripcion AS descripcion',
    
                            DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                            DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                            DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                        )
                        ->orderBy('examenes.id', 'ASC')
                        ->take(100)
                        ->get();$examen = examenes::join('personas AS P1',    'examenes.medico_id',          '=', 'P1.id') 
                                            ->join('personas      AS P2',    'examenes.tecnico_id',  '=', 'P2.id')
                                            ->join('estado_examen AS EE',    'EE.id',                '=', 'examenes.id_estado_examen')
                                            ->join('personas      AS P3',    'examenes.paciente_id', '=', 'P3.id')
                                            ->join('tipo_examen   AS TE', 'examenes.id_tipo_examen', '=', 'TE.id')
                                            ->join('clinicas      AS C' , 'examenes.id_clinica' ,    '=', 'C.id')
                                            ->join('ciudades      AS CD1' ,'P3.ciudad_id'       ,    '=', 'CD1.id')
                                            ->join('users         AS US',  'US.id',                  '=', 'P1.user_id') 

    
                        ->where(static function ($query) use ($data) {
                            $query->where('P3.apellidos'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P2.apellidos' , 'LIKE', "%{$data}%")
                                  ->orWhere('P2.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('P1.apellidos' , 'LIKE', "%{$data}%")
                                  ->orWhere('P1.nombres'   , 'LIKE', "%{$data}%")
                                  ->orWhere('examenes.descripcion'  , 'LIKE', "%{$data}%")
                                  ->orWhere('C.nombre'     , 'LIKE', "%{$data}%")
                                  ->orWhere('TE.nombre'    , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.dni'       , 'LIKE', "%{$data}%")
                                  ->orWhere('P3.correo'    , 'LIKE', "%{$data}%");
                        })
                        ->where('examenes.id_tipo_examen'  , '=' , $id_examen)
                         
                        ->where('EE.nombre', '<>', 'Programado' )
                        ->where('EE.nombre', '<>', 'Cancelado' )
    
                        ->select(
                            'examenes.id AS IdExamen',
                            'examenes.fecha_inicio',
                            'examenes.fecha_fin',
                            'TE.nombre AS examen',
                            'TE.id     AS tipo_examen',
    
                            'C.id  AS id_clinica',
                            'C.nombre  AS clinica',
                            'examenes.id_estado_examen',
                            'examenes.descripcion',
                            'examenes.costo_examen',
                            
                            'EE.color  AS color',
                            'EE.nombre AS estado',
                            'EE.id     AS id_estado',
    
                            'P3.correo AS correo_paciente',
                            'P3.dni AS dni_paciente',
                            'CD1.nombre AS ciudad_paciente',
                            'examenes.medico_id AS medico_id',
                            'examenes.tecnico_id AS tecnico_id',
                            'examenes.paciente_id AS paciente_id',
                            'examenes.descripcion AS descripcion',
    
                            DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                            DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                            DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                        )
                        ->orderBy('examenes.id', 'ASC')
                        ->take(100)
                        ->get();

                    }
                                        
                }else{
                    
                    if($roll == 'Tecnico'){

                        $examen = examenes::join('personas AS P1',        'examenes.medico_id',      '=', 'P1.id')
                                        ->join('personas AS P2',      'examenes.tecnico_id',     '=', 'P2.id')
                                        ->join('estado_examen AS EE', 'EE.id',                   '=', 'examenes.id_estado_examen')
                                        ->join('personas AS P3',      'examenes.paciente_id',    '=', 'P3.id')
                                        ->join('tipo_examen AS TE',   'examenes.id_tipo_examen', '=', 'TE.id')
                                        ->join('clinicas    AS C' ,   'examenes.id_clinica' ,    '=', 'C.id')
                                        ->join('ciudades    AS CD1',  'P3.ciudad_id'       ,     '=', 'CD1.id')
 
                                        ->join('users       AS US',   'US.id',               '=', 'P2.user_id') 


                                    ->where('examenes.id_tipo_examen'  ,  '='  , $id_examen)
                                    ->where('EE.nombre', '<>', 'Programado' )
                                    ->where('EE.nombre', '<>', 'Cancelado' )
                                    ->where('US.id','=',$id_user) 


                                    ->select(
                                        'examenes.id AS IdExamen',
                                        'examenes.fecha_inicio',
                                        'examenes.fecha_fin',
                                        'TE.nombre AS examen',
                                        'TE.id     AS tipo_examen',

                                        'C.id  AS id_clinica',
                                        'C.nombre  AS clinica',
                                        'examenes.id_estado_examen',
                                        'examenes.descripcion',
                                        'examenes.costo_examen',
                                        
                                        'EE.color  AS color',
                                        'EE.nombre AS estado',
                                        'EE.id     AS id_estado',

                                        'P3.correo AS correo_paciente',
                                        'P3.dni AS dni_paciente',
                                        'CD1.nombre AS ciudad_paciente',
                                        'examenes.medico_id AS medico_id',
                                        'examenes.tecnico_id AS tecnico_id',
                                        'examenes.paciente_id AS paciente_id',
                                        'examenes.descripcion AS descripcion',

                                        DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                                        DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                        DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                    )
                                    ->orderBy('examenes.id', 'ASC')
                                    ->take(100)
                                    ->get(); 

                    }else if( $roll == 'Medico' ){

                        $examen = examenes::join('personas AS P1',        'examenes.medico_id',      '=', 'P1.id')
                        ->join('personas AS P2',      'examenes.tecnico_id',     '=', 'P2.id')
                        ->join('estado_examen AS EE', 'EE.id',                   '=', 'examenes.id_estado_examen')
                        ->join('personas AS P3',      'examenes.paciente_id',    '=', 'P3.id')
                        ->join('tipo_examen AS TE',   'examenes.id_tipo_examen', '=', 'TE.id')
                        ->join('clinicas    AS C' ,   'examenes.id_clinica' ,    '=', 'C.id')
                        ->join('ciudades    AS CD1',  'P3.ciudad_id'       ,     '=', 'CD1.id')
                        ->join('users       AS US',   'US.id',                   '=', 'P1.user_id') 

                            ->where('examenes.id_tipo_examen'  ,  '='  , $id_examen)
                            ->where('EE.nombre', '<>', 'Programado' )
                            ->where('EE.nombre', '<>', 'Cancelado' )
                            ->where('US.id','=',$id_user) 


                            ->select(
                                'examenes.id AS IdExamen',
                                'examenes.fecha_inicio',
                                'examenes.fecha_fin',
                                'TE.nombre AS examen',
                                'TE.id     AS tipo_examen',

                                'C.id  AS id_clinica',
                                'C.nombre  AS clinica',
                                'examenes.id_estado_examen',
                                'examenes.descripcion',
                                'examenes.costo_examen',
                                
                                'EE.color  AS color',
                                'EE.nombre AS estado',
                                'EE.id     AS id_estado',

                                'P3.correo AS correo_paciente',
                                'P3.dni AS dni_paciente',
                                'CD1.nombre AS ciudad_paciente',
                                'examenes.medico_id AS medico_id',
                                'examenes.tecnico_id AS tecnico_id',
                                'examenes.paciente_id AS paciente_id',
                                'examenes.descripcion AS descripcion',

                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                            )
                            ->orderBy('examenes.id', 'ASC')
                            ->take(100)
                            ->get();
                    }else {

                        $examen = examenes::join('personas AS P1',        'examenes.medico_id',      '=', 'P1.id')
                        ->join('personas AS P2',      'examenes.tecnico_id',     '=', 'P2.id')
                        ->join('estado_examen AS EE', 'EE.id',                   '=', 'examenes.id_estado_examen')
                        ->join('personas AS P3',      'examenes.paciente_id',    '=', 'P3.id')
                        ->join('tipo_examen AS TE',   'examenes.id_tipo_examen', '=', 'TE.id')
                        ->join('clinicas    AS C' ,   'examenes.id_clinica' ,    '=', 'C.id')
                        ->join('ciudades    AS CD1',  'P3.ciudad_id'       ,     '=', 'CD1.id') 

                        ->where('examenes.id_tipo_examen'  ,  '='  , $id_examen)
                        ->where('EE.nombre', '<>', 'Programado' )
                        ->where('EE.nombre', '<>', 'Cancelado' ) 


                        ->select(
                            'examenes.id AS IdExamen',
                            'examenes.fecha_inicio',
                            'examenes.fecha_fin',
                            'TE.nombre AS examen',
                            'TE.id     AS tipo_examen',

                            'C.id  AS id_clinica',
                            'C.nombre  AS clinica',
                            'examenes.id_estado_examen',
                            'examenes.descripcion',
                            'examenes.costo_examen',
                            
                            'EE.color  AS color',
                            'EE.nombre AS estado',
                            'EE.id     AS id_estado',

                            'P3.correo AS correo_paciente',
                            'P3.dni AS dni_paciente',
                            'CD1.nombre AS ciudad_paciente',
                            'examenes.medico_id AS medico_id',
                            'examenes.tecnico_id AS tecnico_id',
                            'examenes.paciente_id AS paciente_id',
                            'examenes.descripcion AS descripcion',

                            DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                            DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                            DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                        )
                        ->orderBy('examenes.id', 'ASC')
                        ->take(100)
                        ->get(); 

                    }// FINISH IF

                }/// END IF   

                return [ 'examenes' => $examen ];

            }elseif ( $mood == '2' ) { 

                if($roll == 'Tecnico'){
                    
                    if ( !isset($data) ) {
                        $examen = examenes::join('personas AS P1',    'examenes.medico_id',          '=', 'P1.id') 
                                            ->join('personas      AS P2',    'examenes.tecnico_id',  '=', 'P2.id')
                                            ->join('estado_examen AS EE',    'EE.id',                '=', 'examenes.id_estado_examen')
                                            ->join('personas      AS P3',    'examenes.paciente_id', '=', 'P3.id')
                                            ->join('tipo_examen   AS TE', 'examenes.id_tipo_examen', '=', 'TE.id')
                                            ->join('clinicas      AS C' , 'examenes.id_clinica' ,    '=', 'C.id')
                                            ->join('ciudades      AS CD1' ,'P3.ciudad_id'       ,    '=', 'CD1.id')

                                            ->join('users        AS US',   'US.id',               '=', 'P2.user_id') 

                                            
                                            ->where('examenes.id_tipo_examen'  , '=' , $id_examen)
                                            ->where('US.id','=',$id_user) 

                                            ->select(
                                                'examenes.id AS IdExamen',
                                                'examenes.fecha_inicio',
                                                'examenes.fecha_fin',
                                                'TE.nombre AS examen',
                                                'TE.id     AS tipo_examen',

                                                'C.id  AS id_clinica',
                                                'C.nombre  AS clinica',
                                                'examenes.id_estado_examen',
                                                'examenes.descripcion',
                                                'examenes.costo_examen',
                                                
                                                'EE.color  AS color',
                                                'EE.nombre AS estado',
                                                'EE.id     AS id_estado',

                                                'P3.correo AS correo_paciente',
                                                'P3.dni AS dni_paciente',
                                                'CD1.nombre AS ciudad_paciente',
                                                'examenes.medico_id AS medico_id',
                                                'examenes.tecnico_id AS tecnico_id',
                                                'examenes.paciente_id AS paciente_id',
                                                'examenes.descripcion AS descripcion',

                                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                            )
                                            ->orderBy('examenes.id', 'ASC')
                                            ->take(100)
                                            ->paginate($cantidad);
                    }else {
                        $examen = examenes::join('personas AS P1',    'examenes.medico_id',          '=', 'P1.id') 
                                            ->join('personas      AS P2',    'examenes.tecnico_id',  '=', 'P2.id')
                                            ->join('estado_examen AS EE',    'EE.id',                '=', 'examenes.id_estado_examen')
                                            ->join('personas      AS P3',    'examenes.paciente_id', '=', 'P3.id')
                                            ->join('tipo_examen   AS TE', 'examenes.id_tipo_examen', '=', 'TE.id')
                                            ->join('clinicas      AS C' , 'examenes.id_clinica' ,    '=', 'C.id')
                                            ->join('ciudades      AS CD1' ,'P3.ciudad_id'       ,    '=', 'CD1.id')

                                            ->join('users        AS US',   'US.id',               '=', 'P2.user_id') 
                                    
                                            ->where(static function ($query) use ($data) {
                                                $query->where('P3.apellidos'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('P3.nombres'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('P2.apellidos' , 'LIKE', "%{$data}%")
                                                    ->orWhere('P2.nombres'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('P1.apellidos' , 'LIKE', "%{$data}%")
                                                    ->orWhere('P1.nombres'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('examenes.descripcion'  , 'LIKE', "%{$data}%")
                                                    ->orWhere('C.nombre'     , 'LIKE', "%{$data}%")
                                                    ->orWhere('TE.nombre'    , 'LIKE', "%{$data}%")
                                                    ->orWhere('P3.dni'       , 'LIKE', "%{$data}%")
                                                    ->orWhere('P3.correo'    , 'LIKE', "%{$data}%");
                                            })
                                            ->where('examenes.id_tipo_examen'  , '=' , $id_examen)
                                            ->where('US.id','=',$id_user) 

                                            ->select(
                                                'examenes.id AS IdExamen',
                                                'examenes.fecha_inicio',
                                                'examenes.fecha_fin',
                                                'TE.nombre AS examen',
                                                'TE.id     AS tipo_examen',

                                                'C.id  AS id_clinica',
                                                'C.nombre  AS clinica',
                                                'examenes.id_estado_examen',
                                                'examenes.descripcion',
                                                'examenes.costo_examen',
                                                
                                                'EE.color  AS color',
                                                'EE.nombre AS estado',
                                                'EE.id     AS id_estado',

                                                'P3.correo AS correo_paciente',
                                                'P3.dni AS dni_paciente',
                                                'CD1.nombre AS ciudad_paciente',
                                                'examenes.medico_id AS medico_id',
                                                'examenes.tecnico_id AS tecnico_id',
                                                'examenes.paciente_id AS paciente_id',
                                                'examenes.descripcion AS descripcion',

                                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                            )
                                            ->orderBy('examenes.id', 'ASC')
                                            ->take(100)
                                            ->paginate($cantidad);
                    }

                }else if($roll == 'Medico'){

                    if ( !isset($data) ) {
                        $examen = examenes::join('personas AS P1',    'examenes.medico_id',          '=', 'P1.id') 
                                            ->join('personas      AS P2',    'examenes.tecnico_id',  '=', 'P2.id')
                                            ->join('estado_examen AS EE',    'EE.id',                '=', 'examenes.id_estado_examen')
                                            ->join('personas      AS P3',    'examenes.paciente_id', '=', 'P3.id')
                                            ->join('tipo_examen   AS TE', 'examenes.id_tipo_examen', '=', 'TE.id')
                                            ->join('clinicas      AS C' , 'examenes.id_clinica' ,    '=', 'C.id')
                                            ->join('ciudades      AS CD1' ,'P3.ciudad_id'       ,    '=', 'CD1.id')

                                            ->join('users        AS US',   'US.id',               '=', 'P1.user_id') 

                                            
                                            ->where('examenes.id_tipo_examen'  , '=' , $id_examen)
                                            ->where('US.id','=',$id_user) 

                                            ->select(
                                                'examenes.id AS IdExamen',
                                                'examenes.fecha_inicio',
                                                'examenes.fecha_fin',
                                                'TE.nombre AS examen',
                                                'TE.id     AS tipo_examen',

                                                'C.id  AS id_clinica',
                                                'C.nombre  AS clinica',
                                                'examenes.id_estado_examen',
                                                'examenes.descripcion',
                                                'examenes.costo_examen',
                                                
                                                'EE.color  AS color',
                                                'EE.nombre AS estado',
                                                'EE.id     AS id_estado',

                                                'P3.correo AS correo_paciente',
                                                'P3.dni AS dni_paciente',
                                                'CD1.nombre AS ciudad_paciente',
                                                'examenes.medico_id AS medico_id',
                                                'examenes.tecnico_id AS tecnico_id',
                                                'examenes.paciente_id AS paciente_id',
                                                'examenes.descripcion AS descripcion',

                                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                            )
                                            ->orderBy('examenes.id', 'ASC')
                                            ->take(100)
                                            ->paginate($cantidad);
                    }else {
 
                        $examen = examenes::join('personas AS P1',    'examenes.medico_id',          '=', 'P1.id') 
                                            ->join('personas      AS P2',    'examenes.tecnico_id',  '=', 'P2.id')
                                            ->join('estado_examen AS EE',    'EE.id',                '=', 'examenes.id_estado_examen')
                                            ->join('personas      AS P3',    'examenes.paciente_id', '=', 'P3.id')
                                            ->join('tipo_examen   AS TE', 'examenes.id_tipo_examen', '=', 'TE.id')
                                            ->join('clinicas      AS C' , 'examenes.id_clinica' ,    '=', 'C.id')
                                            ->join('ciudades      AS CD1' ,'P3.ciudad_id'       ,    '=', 'CD1.id')

                                            ->join('users        AS US',   'US.id',               '=', 'P1.user_id') 
                                    
                                            ->where(static function ($query) use ($data) {
                                                $query->where('P3.apellidos'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('P3.nombres'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('P2.apellidos' , 'LIKE', "%{$data}%")
                                                    ->orWhere('P2.nombres'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('P1.apellidos' , 'LIKE', "%{$data}%")
                                                    ->orWhere('P1.nombres'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('examenes.descripcion'  , 'LIKE', "%{$data}%")
                                                    ->orWhere('C.nombre'     , 'LIKE', "%{$data}%")
                                                    ->orWhere('TE.nombre'    , 'LIKE', "%{$data}%")
                                                    ->orWhere('P3.dni'       , 'LIKE', "%{$data}%")
                                                    ->orWhere('P3.correo'    , 'LIKE', "%{$data}%");
                                            })
                                            ->where('examenes.id_tipo_examen'  , '=' , $id_examen)
                                            ->where('US.id','=',$id_user) 

                                            ->select(
                                                'examenes.id AS IdExamen',
                                                'examenes.fecha_inicio',
                                                'examenes.fecha_fin',
                                                'TE.nombre AS examen',
                                                'TE.id     AS tipo_examen',

                                                'C.id  AS id_clinica',
                                                'C.nombre  AS clinica',
                                                'examenes.id_estado_examen',
                                                'examenes.descripcion',
                                                'examenes.costo_examen',
                                                
                                                'EE.color  AS color',
                                                'EE.nombre AS estado',
                                                'EE.id     AS id_estado',

                                                'P3.correo AS correo_paciente',
                                                'P3.dni AS dni_paciente',
                                                'CD1.nombre AS ciudad_paciente',
                                                'examenes.medico_id AS medico_id',
                                                'examenes.tecnico_id AS tecnico_id',
                                                'examenes.paciente_id AS paciente_id',
                                                'examenes.descripcion AS descripcion',

                                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                            )
                                            ->orderBy('examenes.id', 'ASC')
                                            ->take(100)
                                            ->paginate($cantidad);
                    }
                    
                }else {
                    
                    if ( !isset($data) ) {

                        $examen = examenes::join('personas AS P1',    'examenes.medico_id',          '=', 'P1.id') 
                                            ->join('personas      AS P2',    'examenes.tecnico_id',  '=', 'P2.id')
                                            ->join('estado_examen AS EE',    'EE.id',                '=', 'examenes.id_estado_examen')
                                            ->join('personas      AS P3',    'examenes.paciente_id', '=', 'P3.id')
                                            ->join('tipo_examen   AS TE', 'examenes.id_tipo_examen', '=', 'TE.id')
                                            ->join('clinicas      AS C' , 'examenes.id_clinica' ,    '=', 'C.id')
                                            ->join('ciudades      AS CD1' ,'P3.ciudad_id'       ,    '=', 'CD1.id')
                                             
                                            ->where('examenes.id_tipo_examen'  , '=' , $id_examen) 

                                            ->select(
                                                'examenes.id AS IdExamen',
                                                'examenes.fecha_inicio',
                                                'examenes.fecha_fin',
                                                'TE.nombre AS examen',
                                                'TE.id     AS tipo_examen',

                                                'C.id  AS id_clinica',
                                                'C.nombre  AS clinica',
                                                'examenes.id_estado_examen',
                                                'examenes.descripcion',
                                                'examenes.costo_examen',
                                                
                                                'EE.color  AS color',
                                                'EE.nombre AS estado',
                                                'EE.id     AS id_estado',

                                                'P3.correo AS correo_paciente',
                                                'P3.dni AS dni_paciente',
                                                'CD1.nombre AS ciudad_paciente',

                                                'examenes.medico_id AS medico_id',
                                                'examenes.tecnico_id AS tecnico_id',
                                                'examenes.paciente_id AS paciente_id',
                                                'examenes.descripcion AS descripcion',

                                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                            )
                                            ->orderBy('examenes.id', 'ASC')
                                            ->take(100)
                                            ->paginate($cantidad);
                    }else {  

                        $examen = examenes::join('personas AS P1',    'examenes.medico_id',          '=', 'P1.id') 
                                            ->join('personas      AS P2',    'examenes.tecnico_id',  '=', 'P2.id')
                                            ->join('estado_examen AS EE',    'EE.id',                '=', 'examenes.id_estado_examen')
                                            ->join('personas      AS P3',    'examenes.paciente_id', '=', 'P3.id')
                                            ->join('tipo_examen   AS TE', 'examenes.id_tipo_examen', '=', 'TE.id')
                                            ->join('clinicas      AS C' , 'examenes.id_clinica' ,    '=', 'C.id')
                                            ->join('ciudades      AS CD1' ,'P3.ciudad_id'       ,    '=', 'CD1.id')
 
                                    
                                            ->where(static function ($query) use ($data) {
                                                $query->where('P3.apellidos'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('P3.nombres'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('P2.apellidos' , 'LIKE', "%{$data}%")
                                                    ->orWhere('P2.nombres'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('P1.apellidos' , 'LIKE', "%{$data}%")
                                                    ->orWhere('P1.nombres'   , 'LIKE', "%{$data}%")
                                                    ->orWhere('examenes.descripcion'  , 'LIKE', "%{$data}%")
                                                    ->orWhere('C.nombre'     , 'LIKE', "%{$data}%")
                                                    ->orWhere('TE.nombre'    , 'LIKE', "%{$data}%")
                                                    ->orWhere('P3.dni'       , 'LIKE', "%{$data}%")
                                                    ->orWhere('P3.correo'    , 'LIKE', "%{$data}%");
                                            })
                                            ->where('examenes.id_tipo_examen'  , '=' , $id_examen) 
                                            ->select(
                                                'examenes.id AS IdExamen',
                                                'examenes.fecha_inicio',
                                                'examenes.fecha_fin',
                                                'TE.nombre AS examen',
                                                'TE.id     AS tipo_examen',

                                                'C.id  AS id_clinica',
                                                'C.nombre  AS clinica',
                                                'examenes.id_estado_examen',
                                                'examenes.descripcion',
                                                'examenes.costo_examen',
                                                
                                                'EE.color  AS color',
                                                'EE.nombre AS estado',
                                                'EE.id     AS id_estado',

                                                'P3.correo AS correo_paciente',
                                                'P3.dni AS dni_paciente',
                                                'CD1.nombre AS ciudad_paciente',
                                                'examenes.medico_id AS medico_id',
                                                'examenes.tecnico_id AS tecnico_id',
                                                'examenes.paciente_id AS paciente_id',
                                                'examenes.descripcion AS descripcion',

                                                DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                                                DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                                                DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente")
                                            )
                                            ->orderBy('examenes.id', 'ASC')
                                            ->take(100)
                                            ->paginate($cantidad);
                    }
                }

                return [ 
                    'pagination' => [
                        'total'         => $examen->total(),
                        'current_page'  => $examen->currentPage(),
                        'per_page'      => $examen->perPage(),
                        'last_page'     => $examen->lastPage(),
                        'from'          => $examen->firstItem(),
                        'to'            => $examen->lastItem(),
                    ],
                    'examenes'          => $examen 
                ];
                //return [ 'examenes' => $examen ];


            } else{
               return $examen = tipoExamen::select('*')->get();
            }

            

             
        } catch (\Exception  $e) {
            return $this->capturar($e, 'Error al consultar');            
        }
    }


    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {           
        
        try {
            
            $mood = $request->input('mood'); 
            // mood 1 = es para crear citas
            
            if ( $mood == '1' ) {
                
                $this->validate($request, [

                    'fecha_inicio'   => 'required',  
                    'fecha_fin'      => 'required',  
                    'medico_id'      => 'required',  
                    'tecnico_id'     => 'required',  
                    'paciente_id'    => 'required',  
                    'id_tipo_examen' => 'required',  
                    'id_clinica'     => 'required',  
                    'costo_examen'   => 'required'
                ]);
                    
                $fecha_inicial = $request->input('fecha_inicio');
                $fecha_fin     = $request->input('fecha_fin');

               
                $fecha_inicial = explode("T", $fecha_inicial);
                $fecha_inicial = $horas = date($fecha_inicial[0]." ".$fecha_inicial[1].":00");

                $fecha_fin     = explode("T", $fecha_fin);
                $fecha_fin     = $horas = date($fecha_fin[0]." ".$fecha_fin[1].":00");


                $examen = new examenes();

                $examen->fecha_inicio     = date($fecha_inicial);
                $examen->fecha_fin        = date($fecha_fin);
                $examen->medico_id        = $request->input('medico_id');
                $examen->tecnico_id       = $request->input('tecnico_id');
                $examen->paciente_id      = $request->input('paciente_id');
                $examen->id_tipo_examen   = $request->input('id_tipo_examen');
                $examen->id_clinica       = $request->input('id_clinica'); 
                $examen->id_estado_examen = '1';
                $examen->descripcion      = '';//$request->input('descripcion'); 

                $examen->costo_examen     = $request->input('costo_examen'); 

                $examen->save();



                if($examen){
                    $status = true;
                }else{
                    $status = false;
                }
    
                return [ 
                    'status'    => $status
                ];
                
            }elseif ( $mood == '2' ) {

                $this->validate($request, [

                    'descripcion'       => 'required',  
                    'id_estado_examen'  => 'required',
                    'idExamen'          => 'required'
    
                ]);

                $idExamen = $request->input('idExamen');


                $examen = examenes::find($idExamen);
                $examen->descripcion      = $request->input('descripcion');
                $examen->id_estado_examen = $request->input('id_estado_examen');
                $examen->save();

                $searchEstados = examenEstados::where('examen_id','=',$idExamen)->get();                

                if ( count($searchEstados) == 0 ) {
                    
                    $estados = new examenEstados();
                    $estados->examen_id	  = $idExamen;
                    $estados->descripcion = 'Descripcion Examen'; 
                    $estados->save(); 
                    $LastIdExamenEstado   = $estados->id;

                }else{

                    $LastIdExamenEstado   = $searchEstados[0]->id;

                }

                if($examen){
                    $status = true;
                }else{
                    $status = false;
                }
    
                return [ 
                    'status'    => $status,
                    'IdEstados' => $LastIdExamenEstado
                ];


            }else{

                $this->validate($request, [

                    'fecha_inicio'   => 'required',  
                    'fecha_fin'      => 'required',  
                    'medico_id'      => 'required',  
                    'tecnico_id'     => 'required',  
                    'paciente_id'    => 'required',  
                    'id_tipo_examen' => 'required',  
                    'id_clinica'     => 'required',  
                    'costo_examen'   => 'required'
    
                ]);
    
                $examen = new examenes();
    
                // $fecha_inicial = str_replace('/', '-', $request->input('fecha_inicio'));
                // $fecha_fin     = str_replace('/', '-', $request->input('fecha_fin'));

                $fecha_inicial = $request->input('fecha_inicio');
                $fecha_fin     = $request->input('fecha_fin');

               
                $fecha_inicial = explode("T", $fecha_inicial);
                $fecha_inicial = $horas = date($fecha_inicial[0]." ".$fecha_inicial[1].":00");

                $fecha_fin     = explode("T", $fecha_fin);
                $fecha_fin     = $horas = date($fecha_fin[0]." ".$fecha_fin[1].":00");
        
                $examen->fecha_inicio     = date($fecha_inicial);
                $examen->fecha_fin        = date($fecha_fin);
                $examen->medico_id        = $request->input('medico_id');
                $examen->tecnico_id       = $request->input('tecnico_id');
                $examen->paciente_id      = $request->input('paciente_id');
                $examen->id_tipo_examen   = $request->input('id_tipo_examen');
                $examen->id_clinica       = $request->input('id_clinica');
                $examen->descripcion      = $request->input('descripcion'); 
                $examen->id_estado_examen = '1';
                $examen->costo_examen     = $request->input('costo_examen');
                $examen->save();
    
                $LastId = $examen->id;
    
                $estados = new examenEstados();
                $estados->examen_id	  = $LastId;
                $estados->descripcion = 'Descripcion Examen'; 
                $estados->save(); 
    
                $LastIdExamenEstado = $estados->id;
    
                if($examen){
                    $status = true;
                }else{
                    $status = false;
                }
    
                return [ 
                    'status'    => $status,
                    'IdEstados' => $LastIdExamenEstado
                ];

            }/// END IF

        } catch (\Exception  $e) {
            return $this->capturar($e, 'Error al consultar');            
        }
    }

     
    public function show(examenes $examenes)
    {
        //
    }
 
    public function edit(examenes $examenes)
    {
        //
    }
 
    public function update(Request $request, $id_examen)
    {   
        try {

            //return $id_examen;
            $mood   = $request->input('mood');
            $estado = $request->input('estado');

            // mood 1 = solo cambio de estados

            if($mood == '1'){

                $examen = examenes::find($id_examen);
                $examen->id_estado_examen = $estado;
                $examen->save();

                if( $examen ){
                    $status = true;
                }else{
                    $status = false;
                }

                return [
                    'status' => $status
                ];

            }


        } catch (\Exception  $e) {
            return $this->capturar($e, 'Error al consultar');            
        }
    }
   
    public function destroy($idExamen)
    {   
        
        $examen          = examenes::where('id','=',$idExamen)->delete();
        $examen_estados  = examenEstados::where('examen_id','=',$idExamen)->delete(); 
        $examen_adjuntos = examenEstadoAdjuntos::where('id_examen_estados','=',$idExamen)->delete();

        if( $examen ){
            $result = [
                'status' => true
            ];
        }else{
            $result = [
                'status' => false
            ];
        }

        return $result; 
    }
}
