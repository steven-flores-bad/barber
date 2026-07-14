<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cajas Chicas</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; color: #1e293b; font-size: 11px; margin: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; margin: 0; letter-spacing: 1px; }
        
        .tabla-kpi { width: 100%; margin-bottom: 20px; border: 1px solid #000; background-color: #f8fafc; }
        .tabla-kpi td { padding: 10px; text-align: center; width: 50%; }
        .tabla-kpi .monto { font-size: 18px; font-weight: bold; margin-top: 3px; }

        .tabla-datos { width: 100%; border-collapse: collapse; }
        .tabla-datos th { font-weight: bold; border-bottom: 1px solid #000; padding: 6px 4px; text-align: left; text-transform: uppercase; font-size: 10px; }
        .tabla-datos td { padding: 8px 4px; border-bottom: 1px dashed #e2e8f0; }
    </style>
</head>
<body>

    <div class="header">
        <h1>HISTORIAL DE AUDITORÍA: CAJAS CHICAS</h1>
        <p>SISTEMA BARBERADMIN — INFORME GERENCIAL</p>
        <p><strong>FECHA IMPRESIÓN:</strong> {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="tabla-kpi">
        <tr>
            <td style="border-right: 1px solid #000;">
                <div style="font-size: 9px; font-weight: bold; color: #64748b;">TOTAL FONDOS INYECTADOS</div>
                <div class="monto">${{ number_format($totalFondos, 2) }}</div>
            </td>
            <td>
                <div style="font-size: 9px; font-weight: bold; color: #64748b;">FONDO PROMEDIO DIARIO</div>
                <div class="monto">${{ number_format($promedioFondo, 2) }}</div>
            </td>
        </tr>
    </table>

    <table class="tabla-datos">
        <thead>
            <tr>
                <th style="width: 40%;">Fecha de Registro</th>
                <th style="width: 30%;">Código Interno</th>
                <th class="text-right" style="width: 30%;">Monto Inicial Base</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cajas as $caja)
            <tr>
                <td>• {{ date('d/m/Y', strtotime($caja->fecha)) }}</td>
                <td>REG-CC-{{ str_pad($caja->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="text-right" style="font-weight: bold;">${{ number_format($caja->monto_inicial, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>