@extends("../layouts.plantilla")

@section("cabecera")
	Insertar un nuevo Departamento
@endsection

@section("cuerpo")
	<form method="post" action="/departamentos">
		@csrf
		<table>
			<tr>
				<td>Nombre</td>
				<td><input type="text" name="nombre"></td>
			</tr>
			<tr>
				<td>Presupuesto</td>
				<td><input type="text" name="presupuesto"></td>
			</tr>
			<tr>
				<td>Centro</td>
				<td><select name="centro_id">
						<option value="">Seleccione Centro</option>
						@foreach ($centros as $centro)
						<option value="{{ $centro->id }}">{{ $centro->nombre }}</option>
						@endforeach
					</select>
				</td>
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