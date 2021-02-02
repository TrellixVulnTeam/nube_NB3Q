<?php
/*
Toda página del proyecto salvo la pantalla principal, tiene que tener una cabecera con:
● Un enlace que me permita volver a la página principal.
● Un encabezado que me indique donde estoy.  PONERLE LA URL A TODAS CON EL TITULO ?

campo de texto amplio es text ?

FALTA BORRAR

*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use App\Categoria;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categorias = Categoria::all();

        return view("categorias.index",compact("categorias"));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("categorias.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validaciones =['nombre' => ['required','max:100','unique:categorias']];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.',
         'nombre.unique' => 'Ese :attribute ya está dado de alta.',
         'nombre.max' => 'El campo :attribute no puede tener más de :max caracteres.'];

        $this->validate($request, $validaciones, $mensajes);

        $categoria = new Categoria;
        $categoria->nombre = $request->nombre;
        if($request->descripcion!=""){
            $categoria->descripcion = $request->descripcion;
        }else {
            $categoria->descripcion =" ";
        }
        $categoria->save();

        return redirect('/categorias');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $categorias = Categoria::with('cursos')->where('id',$id)->get();
        return view("categorias.show", compact('categorias'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /* */
        $categoria = Categoria::findOrFail($id);
        return view("categorias.edit", compact('categoria'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validaciones =['nombre' => ['required','max:100',Rule::unique('categorias')->ignore($id)]];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.',
         'nombre.unique' => 'Ese :attribute ya está dado de alta.',
         'nombre.max' => 'El campo :attribute no puede tener más de :max caracteres.'];


        $this->validate($request, $validaciones, $mensajes);

        $categoria = Categoria::findOrFail($id);
        $categoria->nombre = $request->nombre;
        if($request->descripcion!=""){
        $categoria->descripcion = $request->descripcion;
        } else {
            $categoria->descripcion =" ";
        }
        $categoria->save();

        return redirect('/categorias');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect('/categorias');
    }
}
