@extends("../layouts.plantilla")

@section("cabecera")
	Información del Departamento
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Nombre</th>
		<th>Presupuesto</th>
		<th>Centro</th>
	</tr>
	<tr>
		<td>{{ $departamento->nombre }}</td>
		<td>{{ $departamento->presupuesto }}</td>
		<td>{{ $departamento->centro->nombre }}</td>
	</tr>
</table>

@endsection 

@section("pie")
@endsection 