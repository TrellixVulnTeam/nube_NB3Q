@extends("layouts.plantilla")

@section("cuerpo")
<div style="display: flex">
        <div class="categorias">
            <a href="{{ route('categorias.index')}}">Categorias</a>
        </div>
        <div class="cursos">
            <a href="{{ route('cursos.index')}}">Cursos</a>
        </div>
        <div class="alumnos">
            <a href="{{ route('alumnos.index')}}">Alumnos</a>
        </div>
    </div>
@endsection

@section("pie")
@endsection
