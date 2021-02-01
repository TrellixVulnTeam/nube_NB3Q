@extends("../layouts.plantilla")

@section("cabecera")
	Borrar categoria
@endsection

@section("cuerpo")
<form method="post" action="/categorias/{{ $categoria->id }}">
		@method("DELETE")
		@csrf
		<input type="submit" name="borrar" value="Eliminar"
		@if (count($categoria->Categorias) >0 ) disabled @endif>
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
