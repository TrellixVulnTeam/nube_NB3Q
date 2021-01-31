@extends("../layouts.plantilla")

@section("cabecera")
	Actualizar un Centro
@endsection

@section("cuerpo")
	<form method="post" action="/centros/{{ $centro->id }}">
		@method("PUT")
		@csrf
		<table>
			<tr>
				<td>Nombre</td>
				<td><input type="text" name="nombre" value="{{ $centro->nombre }}"></td>
			</tr>
			<tr>
				<td>Dirección</td>
				<td><input type="text" name="direccion" value="{{ $centro->direccion }}"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="enviar" value="Actualizar"></td>
			</tr>
		</table>
	</form>
	<form method="post" action="/centros/{{ $centro->id }}">
		@method("DELETE")
		@csrf
		<input type="submit" name="borrar" value="Eliminar" 
		@if (count($centro->departamentos) >0 ) disabled @endif>
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