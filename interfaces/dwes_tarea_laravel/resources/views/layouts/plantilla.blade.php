<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tarea Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <style>

            .cabecera{
                text-align: center;
                font-size: x-large;
                margin-bottom: 100px;
                color: blue;
            }

            .cuerpo form, .cuerpo table{
                width: 600px;
                margin: 0 auto;
            }

            .pie{
                position: fixed;
                bottom: 0px;
                width: 100%;
                font-size: 0.7em;
                margin-bottom: 15px;
            }

            .categorias, .cursos, .alumnos {
                width: 200px;
                text-align: center;
                font-size: 2em;

            }
        </style>
    </head>
    <body>
    <div class="cabecera">
    		@yield("cabecera")
            <a href="{{ url('') }}">Inicio</a>
    	</div>

    	<div class="cuerpo">

    		@yield("cuerpo")

    	</div>

    	<div class="pie">
            MANUEL MARTÍN FERNÁNDEZ
    		@yield("pie")
    	</div>

	</body>
</html>
