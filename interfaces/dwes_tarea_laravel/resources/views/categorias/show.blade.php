@extends("../layouts.plantilla")

@section("cabecera")
	Información del Centro
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Nombre</th>
		<th>Dirección</th>
		<th>Departamentos</th>
	</tr>
	@foreach ($centros as $centro)
	<tr>
		<td>{{ $centro->nombre }}</td>
		<td>{{ $centro->direccion }}</td>
		<td>
			@if(count($centro->departamentos) == 0)
				<p>No tiene departamentos asociados.</p>
			@else
				<ul>
					@foreach ($centro->departamentos as $departamento)
						<li>{{ $departamento->nombre }}</li>
					@endforeach
				</ul>
			@endif
		</td>
	</tr>
	@endforeach
</table>

@endsection 

@section("pie")
@endsection 