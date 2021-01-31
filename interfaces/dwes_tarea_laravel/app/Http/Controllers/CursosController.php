<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Curso;

class CursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cursos = Curso::all();

        return view("cursos.index",compact("cursos"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("cursos.create");
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
        $validaciones = ['nombre' => 'required', 'horas' => 'required', 'plazas' => 'required', 'categoria' => 'required'];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.', 'horas.required' => 'El campo :attribute no puede estar vacío.', 'plazas.required' => 'El campo :attribute no puede estar vacío.', 'categoria.required' => 'El campo :attribute no puede estar vacío.'];

        $this->validate($request, $validaciones, $mensajes);

        $curso = new Curso;
        $curso->nombre = $request->nombre;
        $curso->duracion = $request->horas;
        $curso->plazas = $request->plazas;
        $curso->categoria_id = $request->categoria;
        $curso->save();

        return redirect('/cursos');

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
    }
}
