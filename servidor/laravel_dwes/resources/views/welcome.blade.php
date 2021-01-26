@extends("layouts.plantilla")


@section("cuerpo")
    <div style="display: flex">
        <div class="centros">
            <a href="{{ route('centros.index')}}">Centros</a>
        </div>
        <div class="departamentos">
            <a href="{{ route('departamentos.index')}}">Departamentos</a>
        </div>
    </div>
@endsection 

@section("pie")
@endsection 