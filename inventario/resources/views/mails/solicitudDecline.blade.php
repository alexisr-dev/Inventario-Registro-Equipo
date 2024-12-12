<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Rechazada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #4CAF50;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        strong {
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Hola, {{ $data['name'] }}</h1>
    <p>¡Tu solicitud ha sido Rechazada!</p>
    <p><strong>Fecha requerida:</strong> {{ $data['fecha_requerida'] }}</p>
    <p><strong>Hora de inicio:</strong> {{ $data['hora_inicio'] }}</p>
    <p><strong>Hora de fin:</strong> {{ $data['hora_fin'] }}</p>
    <p><strong>Aula:</strong> {{ $data['aula'] }}</p>
    <p>.</p>
</body>
</html>
