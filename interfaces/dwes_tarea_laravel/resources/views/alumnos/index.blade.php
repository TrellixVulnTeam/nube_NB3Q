@extends("../layouts.plantilla")
<a href="{{ url('') }}">Inicio</a>
@section("cabecera")
	Listado de Alumnos
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Nombre</th>
        <th>Apellidos</th>
		<th>Acciones</th>
	</tr>

	@foreach ($alumnos as $alumno)
	<tr>
		<td>{{ $alumno->nombre }}</td>
        <td>{{ $alumno->apellidos }}</td>
		<td align="center"><a href="{{ route('alumnos.edit', $alumno->id)}}">editar</a> - <a href="{{ route('alumnos.show', $alumno->id)}}">mostrar</a> - <a href="{{ route('alumnos.destroy', $alumno->id)}}">borrar</a></td>
	</tr>
	@endforeach
	<tr>
		<td colspan="3" align="center"><a href="{{ route('alumnos.create')}}">Nuevo alumno</a></td>
	</tr>
</table>

@endsection

@section("pie")
@endsection
