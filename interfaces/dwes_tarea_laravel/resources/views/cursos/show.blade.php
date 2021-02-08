@extends("../layouts.menus")

@section("cabecera")
	Información del curso
@endsection

@section("cuerpo")
<table border ="1">
<tr>
		<th>Nombre</th>
        <th>Número de horas</th>
        <th>Plazas disponibles</th>
	</tr>
	@foreach ($cursos as $curso)
	<tr>
        <td>{{ $curso->nombre }}</td>
		<td>{{ $curso->horas }}</td>
		<td>{{ $curso->plazas }}</td>
	</tr>
	@endforeach
</table>

@endsection

@section("pie")
@endsection
