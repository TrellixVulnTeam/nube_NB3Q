@extends("../layouts.plantilla")

@section("cabecera")
	Insertar un nuevo Curso
@endsection

@section("cuerpo")
	<form method="post" action="/cursos">
		@csrf
		<table>
			<tr>
				<td>Nombre</td>
				<td><input type="text" name="nombre"></td>
			</tr>
			<tr>
				<td>Horas del curso</td>
				<td><input type="text" name="horas"></td>
			</tr>
            <tr>
				<td>Plazas disponibles</td>
				<td><input type="text" name="plazas"></td>
			</tr>
            <td>Categoria</td>
				<td><select name="categoria_id">
						<option value="">Seleccione Categoria</option>
						@foreach ($categorias as $categoria)
						<option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
						@endforeach
					</select>
				</td>
			<tr>
				<td colspan="4" align="center"><input type="submit" name="enviar" value="Enviar"></td>
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
