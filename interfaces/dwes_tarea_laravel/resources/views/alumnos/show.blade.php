@extends("../layouts.menus")

@section("cabecera")
	Información del Alumno
@endsection

@section("cuerpo")
<table border ="1">
<tr>
		<th>Nombre</th>
        <th>Apellidos</th>
        <th>Edad</th>
        <th>Curso</th>
	</tr>

	@foreach ($alumnos as $alumno)
	<tr>
		<td>{{ $alumno->nombre }}</td>
        <td>{{ $alumno->apellidos }}</td>
        <td>{{ $alumno->edad }}</td>
        <td>{{ $alumno->curso_id }}</td>
	</tr>
	@endforeach
</table>

@endsection

@section("pie")
@endsection
