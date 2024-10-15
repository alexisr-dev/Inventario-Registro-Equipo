<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css']) <!-- Asegúrate de importar tus estilos -->
</head>
<body class="bg-green-500"> <!-- Cambia el color de fondo aquí -->
    <div>
        @yield('content')
    </div>
</body>
</html>
