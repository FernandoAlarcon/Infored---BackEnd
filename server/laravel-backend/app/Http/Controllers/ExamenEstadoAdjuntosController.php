<?php

namespace App\Http\Controllers;

use App\Models\examenes;
use App\Models\examenEstados;
use App\Models\examenEstadoAdjuntos; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class ExamenEstadoAdjuntosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $idExamen = $request->input('idExamen');
        
        if(isset($idExamen)){                      

                    // $examen = examenes::join('personas AS P1',     'examenes.medico_id',   '=', 'P1.id')
                    //                   ->join('personas AS P2',     'examenes.tecnico_id',  '=', 'P2.id')
                    //                   ->join('personas AS P3',     'examenes.paciente_id', '=', 'P3.id')
                    //                   ->join('tipo_examen AS TE',  'examenes.id_tipo_examen', '=', 'TE.id')
                    //                   ->join('clinicas    AS C' ,  'examenes.id_clinica' , '=', 'C.id')
                    //                   ->join('ciudades    AS CD1', 'P3.ciudad_id'       , '=', 'CD1.id')
                                      
                    //                   ->where('examenes.id','=',$idExamen)
                    //                   ->select(

                    //                     'examenes.id AS IdExamen',
                    //                     'examenes.fecha_inicio',
                    //                     'examenes.fecha_fin',
                    //                     'TE.nombre AS examen',
                    //                     'C.nombre  AS clinica',
                    //                     'examenes.id_estado_examen',
                    //                     'examenes.descripcion',
                    //                     'examenes.costo_examen',
                    //                     'CD1.nombre AS ciudad_paciente',
                    //                     DB::raw("CONCAT(P1.nombres,' ',P1.apellidos) AS medico"),
                    //                     DB::raw("CONCAT(P2.nombres,' ',P2.apellidos) AS tecnico"),
                    //                     DB::raw("CONCAT(P3.nombres,' ',P3.apellidos) AS paciente"),
                    //                     'P3.correo AS correo_paciente',
                    //                     'P3.dni    AS dni_paciente'

                    //                  )
                    //                  ->get();

                   $examen_estados = examenEstados::where('examen_id', '=', $idExamen)->get();
                   $id_adjunto = $examen_estados[0]->id;
                   $adjunto    = examenEstadoAdjuntos::where('id_examen_estados', '=', $id_adjunto)->get();

                   return   [   //'examen'   => $examen,
                                'adjuntos' => $adjunto,
                                //'path'     => public_path()
                            ];

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
        $id_adjuntos = $request->input('id_estados');
        $status      = false;

        if ($request->hasFile('imagenes')) {
            //  Let's do everything here

            if ($request->file('imagenes')->isValid()) {
                //
                $validated = $request->validate([
                    'name'     => 'string|max:40',
                    'imagenes' => 'mimes:jpeg,png,jpg|max:1014',
                ]);

                $nombre  = str_replace(' ', '_',$request->input('name'));
                $nombre  = str_replace('.', '_',$nombre);
                $imgName = rand(11111, 99999).'_'.$nombre;

                $extension = $request->imagenes->extension();
                // $request->imagenes->storeAs('/public/adjuntos', $nombre.".".$extension);
                // $url = Storage::url($nombre.".".$extension);

                $file = $request->file('imagenes');
                $name = $imgName.'_'.$file->getClientOriginalName();
                $url  = $file->move(public_path().'/adjuntos/examenes/', $name);  
                $data = $name;  
               
                $file = examenEstadoAdjuntos::create([
                   'adjunto'           => $name,
                   'id_examen_estados' => $id_adjuntos,
                ]);
                
                if($file){
                    $status = true;
                }else{
                    $status = false;
                }
            }
        }
        return $status;
                
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\examenEstadoAdjuntos  $examenEstadoAdjuntos
     * @return \Illuminate\Http\Response
     */

    public function show(examenEstadoAdjuntos $examenEstadoAdjuntos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\examenEstadoAdjuntos  $examenEstadoAdjuntos
     * @return \Illuminate\Http\Response
     */
    public function edit(examenEstadoAdjuntos $examenEstadoAdjuntos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\examenEstadoAdjuntos  $examenEstadoAdjuntos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, examenEstadoAdjuntos $examenEstadoAdjuntos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\examenEstadoAdjuntos  $examenEstadoAdjuntos
     * @return \Illuminate\Http\Response
     */
    public function destroy(examenEstadoAdjuntos $examenEstadoAdjuntos)
    {
        //
    }
}
