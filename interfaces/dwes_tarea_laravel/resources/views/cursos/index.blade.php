@extends("../layouts.plantilla")

@section("cabecera")
	Listado de Centros
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Nombre</th>
		<th>Acciones</th>
	</tr>


	@foreach ($centros as $centro)
	<tr>
		<td>{{ $centro->nombre }}</td>
		<td align="center"><a href="{{ route('centros.edit', $centro->id)}}">editar</a> - <a href="{{ route('centros.show', $centro->id)}}">mostrar</a></td>
	</tr>
	@endforeach
	<tr>
		<td colspan="2" align="center"><a href="{{ route('centros.create')}}">Nuevo centro</a></td>
	</tr>
</table>

@endsection 

@section("pie")
@endsection 