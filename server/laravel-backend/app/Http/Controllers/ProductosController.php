<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
 
    public function index(Request $request)
    {
        try {
            $data     = $request->input('data');
            $cantidad = $request->input('cantidad');
            if(!isset($cantidad)){
                $cantidad = 5;
            }
            $productos = Productos::
            join('categorias AS C', 'C.id','=','productos.categoria' )
            ->where( 'productos.nombre', 'LIKE','%'.$data.'%' )
            ->orWhere( 'productos.descripcion', 'LIKE','%'.$data.'%' )
            ->orWhere( 'productos.precio', 'LIKE','%'.$data.'%' )
            ->orWhere( 'C.nombre','LIKE','%'.$data.'%' )
            ->select([
                '*',
                'C.id AS IdCategoria',
                'C.nombre AS categoria',
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
            'cantidad'  => 'required',
            'categoria' => 'required'
        ]);

          $Productos = new Productos();
          $Productos->nombre      = $request->input('nombre');
          $Productos->descripcion = $request->input('descripcion');
          $Productos->cantidad    = $request->input('cantidad');
          $Productos->precio      = $request->input('precio');
          $Productos->categoria   = $request->input('categoria');
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
        try {
                $Productos = Productos::find($id);
                $Productos->nombre      = $request->input('nombre');
                $Productos->descripcion = $request->input('descripcion');
                $Productos->cantidad    = $request->input('cantidad');
                $Productos->precio      = $request->input('precio');
                $Productos->categoria   = $request->input('categoria');
 
                $Productos->save();
                
                if(isset($Productos)){
                        $data = [ 'succes' => true ];
                }else{
                        $data = [ 'succes' => false ];
                }

                return $data;
        }  catch (\Exception $e) {
            return $this->capturar($e, 'Error al actualizar Producto');
        }
    }

    public function destroy($id)
    {   
        try {
            $Productos = Productos::find($id);
            $Productos->delete();

            return;
        }  catch (\Exception $e) {
            return $this->capturar($e, 'Error al eliminar Producto');
        }
    }
}
