<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        table {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        caption {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .table-container {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <header>
        <h1>Inventario</h1>
    </header>

    <div class="table-container">
        <table>
            <caption>Listado de Inventario</caption>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Número de Serie</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventarios as $inventario)
                <tr>
                    <td>{{ $inventario->producto->nombre }}</td>
                    <td>{{ $inventario->marca }}</td>
                    <td>{{ $inventario->modelo }}</td>
                    <td>{{ $inventario->numero_serie }}</td>
                    <td>{{ $inventario->estado }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
