@extends("../layouts.plantilla")
<a href="{{ url('') }}">Inicio</a>

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
		<td>{{ $categoria->descripcion }}</td>
	</tr>
	@endforeach
</table>

@endsection

@section("pie")
@endsection
