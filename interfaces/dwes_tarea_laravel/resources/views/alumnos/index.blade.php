@extends("../layouts.menus")

@section("cabecera")
	Listado de Alumnos
@endsection

@section("cuerpo")
<table border ="1">
	<tr>
		<th>Nombre</th>
        <th>Apellidos</th>
		<th>Acciones</th>
	</tr>

	@foreach ($alumnos as $alumno)
	<tr>
		<td>{{ $alumno->nombre }}</td>
        <td>{{ $alumno->apellidos }}</td>
		<td align="center">
                <form action="{{ route('alumnos.destroy',$alumno->id) }}" method="POST">
                    <a href="{{ route('alumnos.edit',$alumno->id) }}">editar</a> -
                    <a href="{{ route('alumnos.show',$alumno->id) }}">mostrar</a> -

                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </td>
	</tr>
	@endforeach
	<tr>
		<td colspan="3" align="center"><a href="{{ route('alumnos.create')}}">Nuevo alumno</a></td>
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
