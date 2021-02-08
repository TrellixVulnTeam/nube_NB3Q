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
        <th>Alumnos</th>
	</tr>
	@foreach ($cursos as $curso)
	<tr>
        <td>{{ $curso->nombre }}</td>
		<td>{{ $curso->horas }}</td>
        <td>{{ ($curso->plazas - $curso->alumnos()->count()) }} </td>
        <td>
		@foreach ($alumnos as $alumno)
        @if($curso->id == $alumno->curso_id  )
			<p><a href="{{ route('alumnos.show', $alumno->id)}}">{{ $alumno->nombre }} </a></p>
        @endif
		@endforeach
		</td>
	</tr>
	@endforeach
</table>

@endsection

@section("pie")
@endsection
