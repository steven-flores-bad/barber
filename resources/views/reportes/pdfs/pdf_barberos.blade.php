<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            font-size: 12px;
            line-height: 1.5;
            margin: 10px;
        }
        .header {
            margin-bottom: 25px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 20px;
            color: #0f172a;
            margin: 0 0 5px 0;
        }
        .header p {
            color: #64748b;
            margin: 0;
            font-size: 11px;
        }
        .barbero-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .barbero-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            background-color: #f8fafc;
            padding: 6px 10px;
            border-left: 4px solid #f59e0b;
            margin-bottom: 10px;
        }
        .summary-box {
            margin-bottom: 10px;
            font-size: 11px;
            color: #475569;
        }
        .summary-box strong {
            color: #0f172a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 7px 10px;
            font-size: 10px;
            text-transform: uppercase;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .monto {
            font-weight: bold;
            color: #16a34a;
        }
        .empty {
            text-align: center;
            color: #94a3b8;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Rendimiento del Equipo (Detalle Acumulado)</h1>
        <p>{{ $titulo }}</p>
        <p>Generado el: {{ date('d/m/Y h:i A') }}</p>
    </div>

    @forelse($ventasPorBarbero as $barbero => $serviciosAcumulados)
        <div class="barbero-section">
            <div class="barbero-title"> Barbero: {{ $barbero }}</div>
            
            <div class="summary-box">
                <span><strong>Total Servicios Realizados:</strong> {{ $serviciosAcumulados->sum('cantidad') }}</span> | 
                <span><strong>Total Recaudado:</strong> ${{ number_format($serviciosAcumulados->sum('total_cobrado'), 2) }}</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 40%;">Servicio Realizado</th>
                        <th style="width: 20%; text-align: right;">Precio Unitario</th>
                        <th style="width: 20%; text-align: center;">Cantidad</th>
                        <th style="width: 20%; text-align: right;">Total Recaudado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviciosAcumulados as $nombreServicio => $info)
                        <tr>
                            <td style="font-weight: bold; color: #1e293b;">{{ $nombreServicio }}</td>
                            <td class="text-right" style="color: #475569;">${{ number_format($info['precio_unitario'], 2) }}</td>
                            <td class="text-center" style="font-weight: 500;">{{ $info['cantidad'] }}</td>
                            <td class="text-right monto">${{ number_format($info['total_cobrado'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="empty">
            No se registran transacciones ni servicios en este periodo para el equipo.
        </div>
    @endforelse

</body>
</html>