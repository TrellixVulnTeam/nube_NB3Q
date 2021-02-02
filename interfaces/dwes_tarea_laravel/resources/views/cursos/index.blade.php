@extends("../layouts.plantilla")
<a href="{{ url('') }}">Inicio</a>
@section("cabecera")
	Listado de Cursos
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Nombre</th>
        <th>Número de Alumnos</th>
        <th>Plazas disponibles</th>
		<th>Acciones</th>
	</tr>


	@foreach ($cursos as $curso)
	<tr>
		<td>{{ $curso->nombre }}</td>
		<td>{{ $curso->alumnos }}</td>

		<td align="center"><a href="{{ route('cursos.edit', $curso->id)}}">editar</a> - <a href="{{ route('cursos.show', $curso->id)}}">mostrar</a> - <a href="{{ route('cursos.destroy', $curso->id)}}">borrar</a></td>
    </tr>
	@endforeach
	<tr>
		<td colspan="4" align="center"><a href="{{ route('cursos.create')}}">Nuevo curso</a></td>
	</tr>
</table>

@endsection

@section("pie")
@endsection
