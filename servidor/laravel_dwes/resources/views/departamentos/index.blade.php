@extends("../layouts.plantilla")

@section("cabecera")
	Listado de Departamentos
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Departamento</th>
		<th>Acciones</th>
	</tr>


	@foreach ($departamentos as $departamento)
	<tr>
		<td>{{ $departamento->nombre }}</td>
		<td align="center"><a href="{{ route('departamentos.edit', $departamento->id)}}">editar</a> - <a href="{{ route('departamentos.show', $departamento->id)}}">mostrar</a></td>
	</tr>
	@endforeach
	<tr>
		<td colspan="2" align="center"><a href="{{ route('departamentos.create')}}">Nuevo departamento</a></td>
	</tr>
</table>

@endsection 

@section("pie")
@endsection 