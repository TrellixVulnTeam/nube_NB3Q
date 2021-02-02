@extends("../layouts.plantilla")

@section("cabecera")
	Actualizar un Alumno
@endsection

@section("cuerpo")
	<form method="post" action="/alumnos/{{ $alumno->id }}">
		@method("PUT")
		@csrf
		<table>
			<tr>
				<td>Nombre</td>
				<td><input type="text" name="nombre" value="{{ $alumno->nombre }}"></td>
			</tr>
			<tr>
				<td>Apellidps</td>
				<td><input type="text" name="apellidos" value="{{ $alumno->apellidos }}"></td>
			</tr>
            <tr>
				<td>Edad</td>
				<td><input type="text" name="edad" value="{{ $alumno->edad }}"></td>
			</tr>
            <tr>
				<td>curso</td>
				<td><input type="hidden" name="curso_id" value="{{ $alumno->curso_id }}">{{ $alumno->curso_id }}</td>
			</tr>
			<tr>
				<td colspan="4" align="center"><input type="submit" name="enviar" value="Actualizar"></td>
			</tr>
		</table>
	</form>
	@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
	@endif

@endsection

@section("pie")
@endsection
