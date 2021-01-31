@extends("../layouts.plantilla")
<a href="{{ url('') }}">Inicio</a>

@section("cabecera")
	Listado de Categorias
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Categorias</th>
		<th>Acciones</th>
	</tr>


	@foreach ($categorias as $categoria)
	<tr>
		<td>{{ $categoria->nombre }}</td>
		<td align="center"><a href="{{ route('categorias.edit', $categoria->id)}}">editar</a> - <a href="{{ route('categorias.show', $categoria->id)}}">mostrar</a>- <a href="{{ route('categorias.destroy', $categoria->id)}}">borrar</a></td>
	</tr>
	@endforeach
	<tr>
		<td colspan="2" align="center"><a href="{{ route('categorias.create')}}">Nueva categoria</a></td>
	</tr>
</table>

@endsection

@section("pie")
@endsection
