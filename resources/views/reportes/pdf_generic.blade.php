<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #334155; margin: 15px; }
        .header { border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 25px; }
        .title { font-size: 20px; font-weight: bold; color: #0f172a; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: bold; padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        td { padding: 10px; font-size: 12px; border-bottom: 1px solid #f1f5f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">{{ $titulo }}</h1>
        <p style="font-size: 11px; color: #94a3b8; margin: 4px 0 0 0;">Generado automáticamente por el Sistema de Control Administrativo.</p>
    </div>

    @if($tipo === 'ventas')
        <table>
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Fecha/Hora</th>
                    <th>Servicio</th>
                    <th>Barbero</th>
                    <th class="text-right">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['items'] as $item)
                <tr>
                    <td class="font-bold">#{{ $item->ticket_id }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->fecha)) }} - {{ date('h:i A', strtotime($item->hora)) }}</td>
                    <td>{{ $item->nombre_servicio }}</td>
                    <td>{{ $item->barbero }}</td>
                    <td class="text-right font-bold">${{ number_format($item->precio_cobrado, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($tipo === 'top')
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 15%;">Posición</th>
                    <th>Nombre del Servicio Realizado</th>
                    <th class="text-center" style="width: 25%;">Cantidad Atendida</th>
                    <th class="text-right" style="width: 25%;">Total Recaudado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['items'] as $index => $item)
                <tr>
                    <td class="text-center font-bold">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $item->nombre_servicio }}</td>
                    <td class="text-center">{{ $item->cantidad_vendida }} veces</td>
                    <td class="text-right font-bold" style="color: #10b981;">${{ number_format($item->ingresos_totales, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>