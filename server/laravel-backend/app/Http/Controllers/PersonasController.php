<?php

namespace App\Http\Controllers;

use App\Models\personas;
use App\Models\usersRoles;
use App\Models\roles_permisos_modulos;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class PersonasController extends Controller
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
            $data    = $request->input('data');
            $mood    = $request->input('mood');
            //return   $Permisos       = usersRoles::where('rol_id', $id_roll)->get();

            if( isset($id_roll) ){

                $personas  = usersRoles::join('personas AS P', 'users_roles.user_id', '=', 'P.user_id' )  
                ->join('roles AS R', 'R.id', '=', 'users_roles.rol_id' )  
                ->where('R.name', '=', $id_roll )
                ->where(static function ($query) use ($data) {
                    $query->where( 'P.nombres',  'LIKE', '%'.$data.'%')
                        ->orWhere( 'P.apellidos','LIKE', '%'.$data.'%');
                })

                ->select(
                    'P.id        AS user_id',
                    'P.nombres   AS nombre',
                    'P.apellidos AS apellidos',
                    'R.name      AS roll'
                )->get();
            
            }elseif ( $mood == '2' ) {
                //return$mood    = $request->input('mood');
                
                if( isset($data) ){

                    $personas = personas::
                                    join('users           AS U'     , 'personas.user_id',    '=', 'U.id')
                                    ->join('users_roles     AS UR'    , 'UR.user_id',          '=', 'U.id')
                                    ->join('roles           AS RL'    , 'RL.id',               '=', 'UR.rol_id')
                                    ->join('tipo_documento  AS TD'    , 'TD.id',               '=', 'personas.tipo_documento_id')

                                    ->where(static function ($query) use ($data) {
                                        $query >where('U.name'                       , 'LIKE', "%{$data}%")
                                              ->orWhere('personas.nombres'           , 'LIKE', "%{$data}%")
                                              ->orWhere('personas.apellidos'         , 'LIKE', "%{$data}%")
                                              ->orWhere('personas.dni'               , 'LIKE', "%{$data}%")
                                              ->orWhere('personas.telefono'          , 'LIKE', "%{$data}%")
                                              ->orWhere('personas.correo'            , 'LIKE', "%{$data}%")
                                              ->orWhere('personas.direccion'         , 'LIKE', "%{$data}%")
                                              ->orWhere('personas.tipo_sangre'       , 'LIKE', "%{$data}%")
                                              ->orWhere('personas.descripcion'       , 'LIKE', "%{$data}%")
                                              ->orWhere('personas.fecha_nacimiento'  , 'LIKE', "%{$data}%")
                                              ->orWhere('TD.nombre'                  , 'LIKE', "%{$data}%")
                                              ->orWhere('RL.name'                    , 'LIKE', "%{$data}%");
                                    })

                                    ->select(
                                        'U.id        AS Id_User',
                                        'U.name      AS Username',
                                        'personas.nombres     AS NombreUser',
                                        'personas.apellidos   AS ApellidoUser',

                                        'personas.dni       AS Documento',
                                        'personas.telefono  AS Telefono',
                                        'personas.correo    AS Correo',
                                        'personas.direccion AS Direccion',
                                        'personas.tipo_sangre',
                                        'personas.descripcion',
                                        'personas.fecha_nacimiento',
                                        'TD.nombre AS TipoDocumento',
                                        'RL.name   AS Roll',
                                        'RL.id     AS Id_roll'
                                    )
                                    ->take(100)
                                    ->orderBy('Id_User','DESC')

                                    ->get();

                }else{

                    //return $personas = personas::join('users           AS U'     , 'personas.user_id',    '=', 'U.id')->join('users_roles    AS UR'    , 'U.id',         '=', 'UR.user_id')->get();

                    $personas = personas::join('users           AS U'     , 'personas.user_id',    '=', 'U.id')
                                        ->join('users_roles     AS UR'    , 'UR.user_id',          '=', 'U.id')
                                    
                                    ->join('roles           AS RL'    , 'RL.id',               '=', 'UR.rol_id')
                                    ->join('tipo_documento  AS TD'    , 'TD.id',               '=', 'personas.tipo_documento_id')
                                    ->select(
                                        
                                        'U.id        AS Id_User',
                                        'U.name      AS Username',
                                        'personas.nombres     AS NombreUser',
                                        'personas.apellidos   AS ApellidoUser',

                                        'personas.dni       AS Documento',
                                        'personas.telefono  AS Telefono',
                                        'personas.correo    AS Correo',
                                        'personas.direccion AS Direccion',
                                        'personas.tipo_sangre',
                                        'personas.descripcion',
                                        'personas.fecha_nacimiento',
                                        'TD.nombre AS TipoDocumento',
                                        'RL.name   AS Roll',
                                        'RL.id     AS Id_roll'
                                    )
                                    ->take(100)
                                    ->orderBy('Id_User','DESC')
                                    ->get();

                }/// END IF

                

            
            }elseif ( $mood == '3' ) {
                
                //$personas = personas::all();
               // $personas = personas::join('users           AS U'     , 'personas.user_id',    '=', 'U.id')->get();

                $personas = personas::join('users           AS U'     , 'personas.user_id',    '=', 'U.id')
                ->join('users_roles     AS UR'    , 'UR.user_id',          '=', 'U.id')
                ->join('roles           AS RL'    , 'RL.id',               '=', 'UR.rol_id')
                ->join('tipo_documento  AS TD'    , 'TD.id',               '=', 'personas.tipo_documento_id')
                ->select(
                    
                    'U.id        AS Id_User',
                    'U.name      AS Username',
                    'personas.nombres     AS NombreUser',
                    'personas.apellidos   AS ApellidoUser',

                    'personas.dni       AS Documento',
                    'personas.telefono  AS Telefono',
                    'personas.correo    AS Correo',
                    'personas.direccion AS Direccion',
                    'personas.tipo_sangre',
                    'personas.descripcion',
                    'personas.fecha_nacimiento',
                    'TD.nombre AS TipoDocumento',
                    'RL.name   AS Roll',
                    'RL.id     AS Id_roll'
                )
                //->take(100)
                ->orderBy('Id_User','DESC')

                ->get();
            

            }else{

                $personas  = usersRoles::join('personas AS P', 'users_roles.user_id', '=', 'P.user_id' )  
                ->where( 'users_roles.rol_id','=', $id_roll )
                ->select(
                    'P.id        AS user_id',
                    'P.nombres   AS nombre',
                    'P.apellidos AS apellidos'
                )
                ->get();

            }

            return [ 'personas' => $personas];


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
        try {
 
            $v = Validator::make($request->all(), [

                'Nombre'          => 'required',  
                'Apellido'        => 'required',  
                'TipoDoc'         => 'required',  
                'documento'       => 'required',  
                'fechaNacimiento' => 'required',  
                'Password'        => 'required',  
                'Ciudad'          => 'required',  
                'TipoSangre'      => 'required',
                'email'           => 'required',
                'roll'            => 'required'
                
            ]);
            
            // if ($v->fails()){
            //     return response()->json([
            //         'status' => 'error',
            //         'errors' => $v->errors()
            //     ], 422);
            // }

            $dni   = $request->input('documento');
            $email = $request->input('email');
            
            $user_mail = User::where('email', '=', $email)->count();
            if ( $user_mail != 0 ) {
                $status = true;
                return  [ 
                            'trouble_status' => $status, 
                            'trouble'        => 'Ya hay un usuario registrado con este email !..' 
                        ];
            }

            $user_dni = personas::where('dni','=',$dni)->count();

            if ( $user_dni != 0 ) {
                $status = true;
                return  [ 
                            'trouble_status' => $status, 
                            'trouble'        => 'Ya hay un usuario registrado con esta cedula !..' 
                        ];
            }

            $user = User::create([
                'name'     => $request->input('Nombre')." ".$request->input('Apellido'),
                'email'    => $request->input('email'),
                'password' => Hash::make($request->input('Password')),
            ]);

            $user_id = $user->id;  
            

            $user_roles = new usersRoles();
            $user_roles->user_id = $user_id;
            $user_roles->rol_id  = $request->input('roll');
            $user_roles->save();

            $personas = new personas();            
            $personas->nombres             = $request->input('Nombre');
            $personas->apellidos           = $request->input('Apellido');
            $personas->tipo_documento_id   = $request->input('TipoDoc');
            $personas->dni                 = $request->input('documento');
            $personas->fecha_nacimiento    = $request->input('fechaNacimiento');
            $personas->telefono            = $request->input('telefono');
            $personas->correo              = $request->input('email');
            $personas->direccion           = $request->input('direccion');
            $personas->ciudad_id           = $request->input('Ciudad');
            $personas->tipo_sangre         = $request->input('TipoSangre');
            $personas->descripcion         = 'User';
            $personas->user_id             = $user_id; 
            $personas->save();

            if ( !isset($personas) ) {
                $status = true;
                return [ 'trouble_dni' => $status ];
            }

            if ( isset($personas) ) {
               $status = true;
            }else{
               $status = false;
            }

            return [
                'status' => $status
            ];

        } catch (\Exception  $e) {
            return $this->capturar($e, 'Error al consultar');            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\personas  $personas
     * @return \Illuminate\Http\Response
     */
    public function show(personas $personas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\personas  $personas
     * @return \Illuminate\Http\Response
     */
    public function edit(personas $personas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\personas  $personas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, personas $personas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\personas  $personas
     * @return \Illuminate\Http\Response
     */
    public function destroy(personas $personas)
    {
        //
    }
}
