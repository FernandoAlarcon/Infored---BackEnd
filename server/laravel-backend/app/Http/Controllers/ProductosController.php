<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data     = $request->input('data');
            $cantidad = $request->input('cantidad');
            if(!isset($cantidad)){
                $cantidad = 5;
            }
            $productos = Productos::
            join( 'categorias AS C', 'C.id','=','productos.categoria' )
            ->where( 'productos.nombre', 'LIKE','%'.$data.'%' )
            ->orWhere( 'productos.descripcion', 'LIKE','%'.$data.'%' )
            ->orWhere( 'productos.peso', 'LIKE','%'.$data.'%' )
            ->orWhere( 'C.nombre','LIKE','%'.$data.'%' )
            ->select([
                '*',
                'C.id AS IdCategoria',
                'productos.id AS id'
            ])
            ->paginate($cantidad);


            return [
                'pagination' => [
                    'total'         => $productos->total(),
                    'current_page'  => $productos->currentPage(),
                    'per_page'      => $productos->perPage(),
                    'last_page'     => $productos->lastPage(),
                    'from'          => $productos->firstItem(),
                    'to'            => $productos->lastItem(),
                ],
                'productos' => $productos
            ];
        } catch (\Exception $e) {
			       return $this->capturar($e, 'Error al consultar listados de productos');
		    }
    }

    public function store(Request $request)
    {
        try {

          $this->validate($request, [
            'nombre'    => 'required',
            'peso'      => 'required',
            'precio'    => 'required',
            'categoria' => 'required'
        ]);

          $Productos = new Productos();
          $Productos->nombre    = $request->input('nombre');
          $Productos->descripcion = $request->input('descripcion');
          $Productos->peso           = $request->input('peso');
          $Productos->precio          = $request->input('precio');
          $Productos->categoria          = $request->input('categoria');
          $Productos->save();

          if($Productos){
               $resultado = true;
          }else{
               $resultado = false;
          }

          return  [
               'succes' => $resultado
          ];

        }  catch (\Exception $e) {
			       return $this->capturar($e, 'Error al guardar productos');
		    }

    }

    public function update(Request $request, $id)
    {    
       
       $Productos = Productos::find($id);
       $Productos->nombre      = $request->input('nombre');
       $Productos->descripcion = $request->input('descripcion');
       $Productos->peso        = $request->input('peso');
       $Productos->precio      = $request->input('precio');
       $Productos->categoria   = $request->input('categoria');

       //return $Productos;
       $Productos->save();
       
       if(isset($Productos)){
            $data = [ 'succes' => true ];
       }else{
            $data = [ 'succes' => false ];
       }

       return $data;
    }

   public function destroy($id)
   {
       $Productos = Productos::find($id);
       $Productos->delete();

       return;
   }
}
