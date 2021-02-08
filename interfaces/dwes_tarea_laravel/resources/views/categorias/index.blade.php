@extends("../layouts.menus")

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
        <td align="center">
                <form action="{{ route('categorias.destroy',$categoria->id) }}" method="POST">
                    <a href="{{ route('categorias.edit',$categoria->id) }}">editar</a> -
                    <a href="{{ route('categorias.show',$categoria->id) }}">mostrar</a> -

                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </td>
    </tr>
	@endforeach
	<tr>
		<td colspan="2" align="center"><a href="{{ route('categorias.create')}}">Nueva categoria</a></td>
	</tr>
</table>
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
