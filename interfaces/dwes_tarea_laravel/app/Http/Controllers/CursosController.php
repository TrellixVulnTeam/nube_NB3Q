<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use App\Curso;
use App\Categoria;
use App\Alumno;
use DB;

class CursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        @if ($curso->plazas >  $curso->alumnos)
            <td>SI</td>
        @else
            <td>NO</td>
	    @endif
        */
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
        $categorias = Categoria::all();
        return view("cursos.create", compact("categorias"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validaciones =['nombre' => ['required','max:100'],
        'horas' => 'required', 'plazas' => 'required', 'categoria_id' => 'required'];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.',
         'nombre.max' => 'El campo :attribute no puede tener más de :max caracteres.',
         'horas.required' => 'El campo :attribute no puede estar vacío.',
         'plazas.required' => 'El campo :attribute no puede estar vacío.',
         'categoria_id.required' => 'El campo :attribute no puede estar vacío.'];

        $this->validate($request, $validaciones, $mensajes);

        $curso = new Curso;
        $curso->nombre = $request->nombre;
        $curso->horas = $request->horas;
        $curso->plazas = $request->plazas;
        $curso->categoria_id = $request->categoria_id;
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
        //también podría establecer la relación en el módulo
        $cursos = Curso::with('alumnos')->where('id',$id)->get();
        $alumnos = Alumno::all();
        return view("cursos.show", compact('cursos', 'alumnos'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $curso = Curso::findOrFail($id);
        return view("cursos.edit", compact('curso'));

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
        $alumnos = DB::select('SELECT * FROM alumnos WHERE curso_id='.$id);

        if(count($alumnos) > 0){
            $validaciones =['nombre' => ['required','max:100'],
        'horas' => 'required', 'plazas' => ['required', 'min:'.count($alumnos)], 'categoria_id' => 'required'];
        } else {
            $validaciones =['nombre' => ['required','max:100'],
            'horas' => 'required', 'plazas' => 'required', 'categoria_id' => 'required'];
        }

        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.',
         'nombre.max' => 'El campo :attribute no puede tener más de :max caracteres.',
         'horas.required' => 'El campo :attribute no puede estar vacío.',
         'plazas.required' => 'El campo :attribute no puede estar vacío.',
         'plazas.min' => 'El campo :attribute no puede tener menos de :min alumnos.',
         'categoria_id.required' => 'El campo :attribute no puede estar vacío.'];

        $this->validate($request, $validaciones, $mensajes);

        $curso = Curso::findOrFail($id);
        $curso->nombre = $request->nombre;
        $curso->horas = $request->horas;
        $curso->plazas = $request->plazas;
        $curso->categoria_id = $request->categoria_id;
        $curso->save();

        return redirect('/cursos');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $curso = Curso::findOrFail($id);
        $alumnos = Alumno::all();

        $alumnos = DB::select('SELECT * FROM alumnos WHERE curso_id='.$id);

        if(count($alumnos) == 0){
        $curso->delete();
        }

        return redirect('/cursos');
    }
}
