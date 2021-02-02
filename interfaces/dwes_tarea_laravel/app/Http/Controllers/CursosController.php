<?php
/*
NUMERO DE ALUMNOS MATRICULADO EN PG INDEX
IF DE SI HAY PLAZAS DISPONIBLES ? EN PG INDEX

SELECT PARA ASOCIAR CURSO A CATEGORIA AL CREAR CURSO ?
FALTA COMPROBACION DE QUE CURSO TENGA ASOCIADA UNA CATEGORIA, ES CON REQUIRE ??

REPASAR QUE NO FALTE COMPROBACIONES DE CREAR POR EJEMPLO
EN EDITAR : tener en cuenta que si queremos modificar el número de plazas no inferior al número de alumnos matriculados

EN MOSTRAR FALTA LISTA DE ALUMNOS INSCRITOS Y REDIRIGIR AL MOSTRAR DE ESE ALUMNO

FALTA BORRAR

*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

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
        /*
        @if ($curso->plazas >  $curso->alumnos)
            <td>SI</td>
        @else
            <td>NO</td>
        @endelse
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
        $validaciones =['nombre' => ['required','max:100','unique:categorias'],
        'horas' => 'required', 'plazas' => 'required'];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.',
         'nombre.unique' => 'Ese :attribute ya está dado de alta.',
         'nombre.max' => 'El campo :attribute no puede tener más de :max caracteres.',
         'horas.required' => 'El campo :attribute no puede estar vacío.',
         'plazas.required' => 'El campo :attribute no puede estar vacío.'];

        $this->validate($request, $validaciones, $mensajes);

        $curso = new Curso;
        $curso->nombre = $request->nombre;
        $curso->horas = $request->horas;
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
        $cursos = Curso::with('alumnos')->where('id',$id)->get();
        return view("cursos.show", compact('cursos'));

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
        $validaciones =['nombre' => ['required','max:100',Rule::unique('categorias')->ignore($id)],
        'horas' => 'required', 'plazas' => 'required'];
        $mensajes = ['nombre.required' => 'El campo :attribute no puede estar vacío.',
         'nombre.unique' => 'Ese :attribute ya está dado de alta.',
         'nombre.max' => 'El campo :attribute no puede tener más de :max caracteres.',
         'horas.required' => 'El campo :attribute no puede estar vacío.',
         'plazas.required' => 'El campo :attribute no puede estar vacío.'];

        $this->validate($request, $validaciones, $mensajes);

        $curso = Curso::findOrFail($id);
        $curso->nombre = $request->nombre;
        $curso->horas = $request->horas;
        //if($request->plazas!="")
        $curso->plazas = $request->plazas;
        $curso->categoria_id = $request->categoria;
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
        $curso = Categoria::findOrFail($id);
        $curso->delete();

        return redirect('/cursos');
    }
}
