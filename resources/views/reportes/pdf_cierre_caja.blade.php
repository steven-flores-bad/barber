<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cierre de Caja Oficial</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; color: #1e293b; font-size: 12px; margin: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; letter-spacing: 1px; }
        
        .seccion-titulo { font-weight: bold; margin-top: 20px; margin-bottom: 8px; border-bottom: 1px dashed #000; padding-bottom: 3px; text-transform: uppercase; }
        
        .tabla-resumen { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .tabla-resumen th { font-weight: bold; border-bottom: 1px solid #000; padding: 5px 0; text-align: left; }
        .tabla-resumen td { padding: 6px 0; }
        
        .caja-total { border: 1px solid #000; padding: 15px; text-align: center; margin-bottom: 20px; background-color: #f8fafc; }
        .caja-total .monto { font-size: 24px; font-weight: bold; margin-top: 5px; }

        .firmas-container { margin-top: 60px; width: 100%; }
        .firma-block { width: 45%; display: inline-block; text-align: center; }
        .linea-firma { border-top: 1px solid #000; width: 80%; margin: 0 auto 5px auto; }
    </style>
</head>
<body>

    <div class="header">
        <h1>✂️ CORTE DE CAJA DIARIO ✂️</h1>
        <p>BARBERÍA SANTEIN</p>
        <p><strong>FECHA DE AUDITORÍA:</strong> {{ date('d/m/Y', strtotime($fecha)) }}</p>
    </div>

    <div class="caja-total">
        <div style="font-size: 10px; letter-spacing: 1px; font-weight: bold;">TOTAL BRUTO EN CAJA</div>
        <div class="monto">${{ number_format($granTotal, 2) }}</div>
        <div style="font-size: 11px; margin-top: 5px;">Total de servicios: <strong>{{ $totalCortes }} atendidos</strong></div>
    </div>

    <div class="seccion-titulo">👨‍🎨 Productividad por Barbero</div>
    <table class="tabla-resumen">
        <thead>
            <tr>
                <th>Nombre del Barbero</th>
                <th class="text-center" style="width: 25%;">Servicios</th>
                <th class="text-right" style="width: 30%;">Total Producido</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumenBarberos as $rb)
            <tr>
                <td>• {{ $rb->barbero }}</td>
                <td class="text-center">{{ $rb->cortes }} u.</td>
                <td class="text-right" style="font-weight: bold;">${{ number_format($rb->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="seccion-titulo">📦 Servicios Desglosados</div>
    <table class="tabla-resumen">
        <thead>
            <tr>
                <th>Servicio</th>
                <th class="text-center" style="width: 25%;">Cant.</th>
                <th class="text-right" style="width: 30%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumenServicios as $rs)
            <tr>
                <td>{{ $rs->servicio }}</td>
                <td class="text-center">x{{ $rs->cantidad }}</td>
                <td class="text-right">${{ number_format($rs->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="seccion-titulo">📝 Verificación de Efectivo (Arqueo Manual)</div>
    <table class="tabla-resumen" style="margin-top: 5px;">
        <tr>
            <td>(+) Fondo Fijo Inicial (Caja Chica):</td>
            <td class="text-right" style="width: 30%;">[ $___________ ]</td>
        </tr>
        <tr>
            <td>(+) Ingreso Total del Turno:</td>
            <td class="text-right"><strong>${{ number_format($granTotal, 2) }}</strong></td>
        </tr>
        <tr>
            <td>(=) Total Esperado en Caja:</td>
            <td class="text-right">[ $___________ ]</td>
        </tr>
        <tr>
            <td>(-) Efectivo Real Contado:</td>
            <td class="text-right">[ $___________ ]</td>
        </tr>
        <tr>
            <td>(±) Diferencia (Faltante / Sobrante):</td>
            <td class="text-right">[ $___________ ]</td>
        </tr>
    </table>

    <div class="firmas-container">
        <div class="firma-block">
            <div class="linea-firma"></div>
            <p>Entregado por: Cajero/Barbero</p>
        </div>
        <div class="firma-block" style="float: right;">
            <div class="linea-firma"></div>
            <p>Auditado por: Administrador</p>
        </div>
    </div>

</body>
</html>