@extends("../layouts.menus")

@section("cabecera")
	Actualizar un Curso
@endsection

@section("cuerpo")
	<form method="post" action="/cursos/{{ $curso->id }}">
		@method("PUT")
		@csrf
		<table>
            <tr>
				<td>Nombre</td>
				<td><input type="text" name="nombre" value="{{ $curso->nombre }}"></td>
			</tr>
			<tr>
				<td>Horas del curso</td>
				<td><input type="text" name="horas" value="{{ $curso->horas }}"></td>
			</tr>
            <tr>
				<td>Plazas disponibles</td>
				<td><input type="text" name="plazas" value="{{ $curso->plazas }}"></td>
			</tr>
            <tr>
				<td>Categoria</td>
				<td><input type="text" name="categoria" value="{{ $curso->categoria }}"></td>
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
