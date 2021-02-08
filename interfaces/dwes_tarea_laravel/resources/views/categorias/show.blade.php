@extends("../layouts.menus")

@section("cabecera")
	Información de la categoría
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Nombre</th>
		<th>Descripción</th>
	</tr>
	<tr>
		<td>{{ $categorias->nombre }}</td>
		<td>{{ $categorias->descripcion }}</td>
	</tr>
</table>

@endsection

@section("pie")
@endsection
