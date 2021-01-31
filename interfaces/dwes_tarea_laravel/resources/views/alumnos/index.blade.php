@extends("../layouts.plantilla")

@section("cabecera")
	Listado de Centros
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Nombre</th>
		<th>Acciones</th>
	</tr>


	@foreach ($alumnos as $alumno)
	<tr>
		<td>{{ $alumno->nombre }}</td>
		<td align="center"><a href="{{ route('alumnos.edit', $alumno->id)}}">editar</a> - <a href="{{ route('alumnos.show', $alumno->id)}}">mostrar</a></td>
	</tr>
	@endforeach
	<tr>
		<td colspan="2" align="center"><a href="{{ route('alumnos.create')}}">Nuevo centro</a></td>
	</tr>
</table>

@endsection

@section("pie")
@endsection
