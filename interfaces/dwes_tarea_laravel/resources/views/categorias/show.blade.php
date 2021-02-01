@extends("../layouts.plantilla")

@section("cabecera")
	Información de la categoría
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Nombre</th>
		<th>Descripción</th>
	</tr>
	@foreach ($categorias as $categoria)
	<tr>
		<td>{{ $categoria->nombre }}</td>
		<td>{{ $categoria->direccion }}</td>
	</tr>
	@endforeach
</table>

@endsection

@section("pie")
@endsection
