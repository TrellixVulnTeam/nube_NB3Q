@extends("../layouts.plantilla")

@section("cabecera")
	Insertar un nuevo Centro
@endsection

@section("cuerpo")
	<form method="post" action="/centros">
		@csrf
		<table>
			<tr>
				<td>Nombre</td>
				<td><input type="text" name="nombre"></td>
			</tr>
			<tr>
				<td>Dirección</td>
				<td><input type="text" name="direccion"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="enviar" value="Enviar"></td>
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