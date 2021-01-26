<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Centro;
use App\Departamento;

class DepartamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $departamentos = Departamento::all();

        return view("departamentos.index",compact("departamentos"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $centros = Centro::all();
        return view("departamentos.create", compact("centros"));
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
        $validaciones = ['nombre' => 'required|unique:departamentos|max:100', 'presupuesto' => 'required', 'centro_id' => 'required'];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.', 'nombre.unique' => 'Ese :attribute ya está dado de alta.', 'nombre.max' => 'El campo :attribute no puede tener más de :max caracteres.', 'presupuesto.required' => 'El campo :attribute no puede estar vacío.', 'centro_id.required' => 'Debe seleccionar un centro.']; 
 
        $this->validate($request, $validaciones, $mensajes);

        $departamento = new Departamento;
        $departamento->nombre = $request->nombre;
        $departamento->presupuesto = $request->presupuesto;
        $departamento->centro_id = $request->centro_id;
        $departamento->save();
    
        return redirect('/departamentos');
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
        $departamento = Departamento::findOrFail($id);
        return view("departamentos.show", compact('departamento'));
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
        $departamento = Departamento::findOrFail($id);
        $centros = Centro::all();
        return view("departamentos.edit", compact('departamento', 'centros'));
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
        //$validaciones = ['nombre' => 'required|unique:departamentos|max:100', 'presupuesto' => 'required', 'centro_id' => 'required'];
        $validaciones =['nombre' => ['required','max:100',Rule::unique('departamentos')->ignore($id)], 'presupuesto' => 'required', 'centro_id' => 'required'];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.', 'nombre.unique' => 'Ese :attribute ya está dado de alta.', 'nombre.max' => 'El campo :attribute no puede tener más de :max caracteres.', 'presupuesto.required' => 'El campo :attribute no puede estar vacío.', 'centro_id.required' => 'Debe seleccionar un centro.']; 
 
        $this->validate($request, $validaciones, $mensajes);

        $departamento = Departamento::findOrFail($id);;
        $departamento->nombre = $request->nombre;
        $departamento->presupuesto = $request->presupuesto;
        $departamento->centro_id = $request->centro_id;
        $departamento->save();
    
        return redirect('/departamentos');
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
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();

        return redirect('/departamentos');
    }

}
