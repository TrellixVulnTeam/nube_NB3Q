<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Centro;

class CentrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $centros = Centro::all();

        return view("centros.index",compact("centros"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("centros.create");
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
        $validaciones = ['nombre' => 'required', 'direccion' => 'required'];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.', 'direccion.required' => 'El campo :attribute no puede estar vacío.']; 
 
        $this->validate($request, $validaciones, $mensajes);
        
        $centro = new Centro;
        $centro->nombre = $request->nombre;
        $centro->direccion = $request->direccion;
        $centro->save();
        
        return redirect('/centros');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $centros = Centro::with('departamentos')->where('id',$id)->get();
        return view("centros.show", compact('centros'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $centro = Centro::findOrFail($id);
        return view("centros.edit", compact('centro'));
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
        //
        $validaciones = ['nombre' => 'required', 'direccion' => 'required'];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.', 'direccion.required' => 'El campo :attribute no puede estar vacío.']; 
 
        $this->validate($request, $validaciones, $mensajes);
        
        $centro = Centro::findOrFail($id);
        $centro->nombre = $request->nombre;
        $centro->direccion = $request->direccion;
        $centro->save();
        
        return redirect('/centros');
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
        $centro = Centro::findOrFail($id);
        $centro->delete();

        return redirect('/centros');
    }
}
