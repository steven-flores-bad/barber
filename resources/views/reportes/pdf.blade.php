<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas - {{ $fecha }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #334155; margin: 10px; }
        .header { border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 25px; }
        .title { font-size: 24px; font-weight: bold; color: #0f172a; margin: 0; }
        .subtitle { font-size: 14px; color: #64748b; margin-top: 5px; }
        .kpi-container { margin-bottom: 25px; overflow: hidden; width: 100%; }
        .kpi-card { width: 45%; float: left; bg: #f8fafc; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px; margin-right: 4%; }
        .kpi-card-last { margin-right: 0; }
        .kpi-title { font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: bold; }
        .kpi-value { font-size: 20px; font-weight: bold; color: #0f172a; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background-color: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: bold; padding: 10px 15px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        td { padding: 12px 15px; font-size: 13px; border-bottom: 1px solid #f1f5f9; color: #334155; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .section-title { font-size: 16px; font-weight: bold; color: #0f172a; margin-bottom: 15px; clear: both; }
        .barbero-box { background: #f8fafc; padding: 12px; margin-bottom: 15px; border-left: 4px solid #f59e0b; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">Cierre de Ventas Diario</h1>
        <div class="subtitle">Fecha de Reporte: <strong>{{ date('d/m/Y', strtotime($fecha)) }}</strong></div>
    </div>

    <div class="kpi-container">
        <div class="kpi-card">
            <div class="kpi-title">Servicios Totales</div>
            <div class="kpi-value">{{ $totalCortesDia }} Realizados</div>
        </div>
        <div class="kpi-card kpi-card-last">
            <div class="kpi-title">Ingreso Total en Caja</div>
            <div class="kpi-value">${{ number_format($totalDineroDia, 2) }}</div>
        </div>
    </div>

    <h2 class="section-title">Resumen por Barbero</h2>
    <table>
        <thead>
            <tr>
                <th>Barbero</th>
                <th class="text-center">Servicios Totales</th>
                <th class="text-right">Total Producido</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumenBarberos as $rb)
            <tr>
                <td class="font-bold">{{ $rb->barbero }}</td>
                <td class="text-center">{{ $rb->total_cortes }}</td>
                <td class="text-right font-bold" style="color: #10b981;">${{ number_format($rb->total_recaudado, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">Desglose Detallado de Tickets</h2>
    @foreach($ventasPorBarbero as $barbero => $servicios)
        <div class="barbero-box">
            <strong>Barbero: {{ $barbero }}</strong> ({{ count($servicios) }} servicios asignados)
        </div>
        <table style="margin-bottom: 15px;">
            <thead>
                <tr>
                    <th style="width: 25%;">Hora</th>
                    <th style="width: 50%;">Servicio Realizado</th>
                    <th style="width: 25%; text-align: right;">Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios as $s)
                <tr>
                    <td>{{ date('h:i A', strtotime($s->hora)) }}</td>
                    <td>{{ $s->servicio }}</td>
                    <td class="text-right font-bold">${{ number_format($s->precio_cobrado, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>
</html>