@extends("../layouts.plantilla")

@section("cabecera")
	Insertar un nuevo alumno
@endsection

@section("cuerpo")
	<form method="post" action="/alumnos">
		@csrf
		<table>
			<tr>
				<td>Nombre</td>
				<td><input type="text" name="nombre"></td>
			</tr>
			<tr>
				<td>Apellidos</td>
				<td><input type="text" name="apellidos"></td>
			</tr>
            <tr>
				<td>Edad</td>
				<td><input type="text" name="edad"></td>
			</tr>
            <tr>
				<td>curso</td>
				<td><input type="text" name="curso_id"></td>
			</tr>
			<tr>
				<td colspan="3" align="center"><input type="submit" name="enviar" value="Enviar"></td>
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
