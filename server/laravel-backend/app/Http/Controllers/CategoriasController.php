<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
  
    public function index(Request $request)
    {   
        $Data       = trim($request->get('data'));
        $categories = Categorias::select('*')->where('nombre','LIKE','%'.$Data.'%')
        ->orWhere('descripcion','LIKE','%'.$Data.'%')  
        ->orderBy('id', 'DESC')      
        ->paginate(5);
        return [
            'pagination' => [
                'total'         => $categories->total(),
                'current_page'  => $categories->currentPage(),
                'per_page'      => $categories->perPage(),
                'last_page'     => $categories->lastPage(),
                'from'          => $categories->firstItem(),
                'to'            => $categories->lastItem(),
            ],
            'categories' => $categories
        ];
    }
 
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nombre'     => 'required',  
            ]);

            $Categoria = new Categorias();
            $Categoria->nombre      = $request->input('nombre');
            $Categoria->descripcion = $request->input('descripcion'); 
            $Categoria->save();

            if($Categoria){
                $resultado = true;
           }else{
                $resultado = false;
           }

           return  [
                'succes' => $resultado
            ];

        }  catch (\Exception $e) {
                    return $this->capturar($e, 'Error al guardar Categoria');
        }
    }
 
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'nombre'      => 'required', 
                'descripcion' => 'required'
            ]);

            $Categoria = Categorias::find($id);
            $Categoria->nombre      = $request->input('nombre');
            $Categoria->descripcion = $request->input('descripcion'); 
            $Categoria->save();

            if($Categoria){
                $resultado = true;
           }else{
                $resultado = false;
           }

           return  [
                'succes' => $resultado
            ];

        }  catch (\Exception $e) {
            return $this->capturar($e, 'Error al actualizar Categoria');
        }
    }
  
    public function destroy($id,Categorias $categorias)
    {   
        try {
            $categorias = Categorias::find($id);
            $categorias->delete();
            return;
        }  catch (\Exception $e) {
            return $this->capturar($e, 'Error al eliminar Categoria');
        }

    }
    
}
