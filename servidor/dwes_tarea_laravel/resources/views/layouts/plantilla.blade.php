<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel CRUD</title>

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
                width: 400px;
                margin: 0 auto;
            }

            .pie{
                position: fixed;
                bottom: 0px;
                width: 100%;
                font-size: 0.7em;
                margin-bottom: 15px;
            }

            .centros, .departamentos {
                width: 200px;
                text-align: center;
                font-size: 2em;

            }
        </style>
    </head>
    <body>
        <a href="{{ url('') }}">Inicio</a>
    	<div class="cabecera">
    		@yield("cabecera")

    	</div>

    	<div class="cuerpo">

    		@yield("cuerpo")

    	</div>

    	<div class="pie">
            IES Velázquez
    		@yield("pie")
    	</div>

	</body>
</html>
