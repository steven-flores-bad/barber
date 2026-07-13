<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Original de Auditoría de Servicios</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #334155;
            font-size: 12px;
            line-height: 1.5;
            margin: 20px;
        }
        .header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 22px;
            margin: 0;
            color: #0f172a;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-size: 13px;
        }
        
        /* Tabla Principal */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #cbd5e1;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Bloque de Resumen Solicitado (Aparte de la tabla) */
        .resumen-container {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .resumen-titulo {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .grid-resumen {
            width: 100%;
            margin-bottom: 20px;
        }
        .card-resumen {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            width: 48%;
            vertical-align: top;
        }
        .card-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
        }
        .card-value {
            font-size: 20px;
            font-weight: bold;
            margin-top: 5px;
        }
        .text-emerald { color: #16a34a; }
        
        /* Listado de Servicios por Nombre */
        .lista-servicios {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #f8fafc;
            padding: 15px;
        }
        .item-servicio {
            padding: 6px 0;
            border-bottom: 1px solid #edf2f7;
            font-size: 12px;
        }
        .item-servicio:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>💈 REPORTE OFICIAL DE AUDITORÍA</h1>
        <p>Periodo del reporte: <strong>{{ date('d/m/Y', strtotime($desde)) }}</strong> al <strong>{{ date('d/m/Y', strtotime($hasta)) }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 12%;">Ticket</th>
                <th style="width: 23%;">Fecha y Hora</th>
                <th>Servicio Realizado</th>
                <th style="width: 25%;">Barbero Atendió</th>
                <th class="text-right" style="width: 18%;">Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $v)
                <tr>
                    <td class="text-center" style="color: #94a3b8;">#{{ $v->ticket_id }}</td>
                    <td>{{ date('d/m/Y', strtotime($v->fecha)) }} - {{ date('h:i A', strtotime($v->hora)) }}</td>
                    <td style="font-weight: bold;">{{ $v->nombre_servicio }}</td>
                    <td>{{ $v->barbero }}</td>
                    <td class="text-right" style="font-weight: bold;">${{ number_format($v->precio_cobrado, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="resumen-container">
        <div class="resumen-titulo">📊 Resumen Financiero y Operativo del Periodo</div>
        
        <table class="grid-resumen" style="border: none; margin-bottom: 15px;">
            <tr style="border: none;">
                <td class="card-resumen" style="border: 1px solid #e2e8f0;">
                    <div class="card-label">Suma de Dinero Obtenido</div>
                    <div class="card-value text-emerald">${{ number_format($ventas->sum('precio_cobrado'), 2) }}</div>
                </td>
                <td style="width: 4%; border: none;"></td>
                <td class="card-resumen" style="border: 1px solid #e2e8f0;">
                    <div class="card-label">Total de Ventas Realizadas</div>
                    <div class="card-value" style="color: #0f172a;">{{ $ventas->count() }} servicios</div>
                </td>
            </tr>
        </table>

        <div class="resumen-titulo" style="margin-top: 20px;">✨ Frecuencia y Cantidad por Tipo de Servicio</div>
        <div class="lista-servicios">
            @foreach($ventas->groupBy('nombre_servicio') as $nombreServicio => $items)
                <div class="item-servicio">
                    <span style="font-weight: bold; color: #334155;">• {{ $nombreServicio }}</span>
                    <span style="float: right; font-weight: bold; color: #0f172a; background-color: #e2e8f0; padding: 2px 8px; border-radius: 4px;">
                        {{ count($items) }} realizados
                    </span>
                    <div style="clear: both;"></div>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>