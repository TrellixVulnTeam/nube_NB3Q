<?php
/*
EN NUEVO ALUMNO FALTA CURSOS EN LOS QUE HUBIERA PLAZA LIBRE :es decir los cursos en los que el número de alumnos
inscritos es inferior al número de plazas disponibles.

FALTA COMPROBACION DE QUE ALUMNO TENGA ASOCIADA UN CURSO, ES CON REQUIRE ??

FALTA BORRAR

*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use App\Alumno;
use App\Curso;

class AlumnosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $alumnos = Alumno::all();

        return view("alumnos.index",compact("alumnos"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("alumnos.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validaciones =['nombre' => ['required','max:100','unique:categorias'], 'apellidos' => 'required'];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.',
        'apellidos.required' => 'El campo :attribute no puede estar vacío.',
         'nombre.unique' => 'Ese :attribute ya está dado de alta.',
         'nombre.max' => 'El campo :attribute no puede tener más de :max caracteres.'];

        $this->validate($request, $validaciones, $mensajes);

        $alumno = new Alumno;
        $alumno->nombre = $request->nombre;
        $alumno->apellidos = $request->apellidos;
        $alumno->edad = $request->edad;
        $alumno->curso_id = $request->curso_id;
        $alumno->save();

        return redirect('/alumnos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alumnos = Alumno::with('curso')->where('id',$id)->get();
        return view("alumnos.show", compact('alumnos'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $alumno = Alumno::findOrFail($id);
        return view("alumnos.edit", compact('alumno'));

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
        $validaciones =['nombre' => ['required','max:100','unique:categorias'], 'apellidos' => 'required'];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.',
        'apellidos.required' => 'El campo :attribute no puede estar vacío.',
         'nombre.unique' => 'Ese :attribute ya está dado de alta.',
         'nombre.max' => 'El campo :attribute no puede tener más de :max caracteres.'];

        $this->validate($request, $validaciones, $mensajes);

        $alumno = Alumno::findOrFail($id);
        $alumno->nombre = $request->nombre;
        $alumno->apellidos = $request->apellidos;
        $alumno->edad = $request->edad;
        $alumno->curso_id = $request->curso_id;
        $alumno->save();

        return redirect('/alumnos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $alumno = Alumno::findOrFail($id);
        $alumno->delete();

        return redirect('/alumnos');
    }
}
