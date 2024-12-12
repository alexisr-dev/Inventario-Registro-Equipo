<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud pendiente</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
        }
        p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin: 10px 0;
        }
        .product-list {
            list-style-type: none;
            padding: 0;
        }
        .product-item {
            background-color: #ecf0f1;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .product-item strong {
            color: #2980b9;
        }
        .details {
            margin-top: 20px;
            background-color: #eaf2f8;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .details p {
            font-size: 1rem;
            color: #333;
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9rem;
            text-align: center;
            color: #7f8c8d;
        }
        .footer a {
            color: #2980b9;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Solicitud pendiente</h1>
        <p>El Docente <strong>{{ $data['name'] }}</strong> con Correo <strong>{{ $data['email'] }}</strong> ha solicitado los siguientes productos:</p>

        <ul class="product-list">
            @foreach($data['detalles_solicitud'] as $detalle)
                <li class="product-item">
                    <strong>Producto:</strong> {{ $detalle['producto'] }}<br>
                    <strong>Cantidad:</strong> {{ $detalle['cantidad'] }}
                </li>
            @endforeach
        </ul>

        <div class="details">
            <p><strong>Fecha de solicitud:</strong> {{ $data['fecha_requerida'] }}</p>
            <p><strong>Hora de inicio del préstamo:</strong> {{ $data['hora_inicio'] }}</p>
            <p><strong>Hora de fin del préstamo:</strong> {{ $data['hora_fin'] }}</p>
            <p><strong>Aula:</strong> {{ $data['aula'] }}</p>
        </div>

        <div class="footer">
            <p>Gracias por usar nuestro sistema de solicitud. Si tienes alguna pregunta, no dudes en <a href="mailto:soporte@example.com">contactarnos</a>.</p>
        </div>
    </div>
</body>
</html>
