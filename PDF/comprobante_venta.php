<?php

require_once("../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;

include("../CONTROLADOR/VentaControlador.php");

$controlador = new VentaControlador();

$idVenta = $_GET["id"] ?? 0;

$venta = $controlador->MostrarVenta($idVenta);
$detalle = $controlador->MostrarDetalle($idVenta);

if(!$venta){
    die("No se encontró la venta solicitada.");
}

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);

$rutaLogo = __DIR__ . "/../IMG/logo2.jpg";
$logo = "";

if(file_exists($rutaLogo)){
    $tipo = pathinfo($rutaLogo, PATHINFO_EXTENSION);
    $logo = "data:image/".$tipo.";base64,".base64_encode(file_get_contents($rutaLogo));
}

$html = '
<style>
body{
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size:12px;
    color:#333;
}
h1{
    text-align:center;
    margin-bottom:0;
}
hr{
    border:0;
    border-top:1px solid #999;
    margin:15px 0;
}
.info{
    width:100%;
    margin-bottom:20px;
}
.info td{
    padding:4px;
}
table.tabla-detalle{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}
table.tabla-detalle th{
    background:#d62828;
    color:#ffffff;
    padding:10px;
    border:1px solid #cccccc;
    text-align:center;
    font-size:13px;
}
table.tabla-detalle td{
    padding:10px;
    border:1px solid #dddddd;
    font-size:12px;
}
.footer{
    margin-top:50px;
    text-align:center;
    font-size:11px;
    color:#666;
}
</style>

<div style="text-align:center;">
    '.($logo ? '<img src="'.$logo.'" width="95">' : '').'
    <h1 style="margin:5px 0 0 0; color:#d62828; font-size:30px;">
        EvyStream
    </h1>
    <div style="font-size:13px; color:#666;">
        Sistema de Gestion Comercial
    </div>
    <div style="margin-top:15px; font-size:18px; font-weight:bold; background:#d62828; color:white; padding:8px;">
        COMPROBANTE DE VENTA
    </div>
</div>

<br>
<hr>

<table class="info" style="margin-top:10px;">
<tr>
<td width="50%">
<b>N Comprobante:</b><br>
<span style="font-size:16px;">
EVY-'.str_pad($venta["id_venta"], 6, "0", STR_PAD_LEFT).'
</span>
</td>
<td width="50%">
<b>Fecha de emision:</b><br>
'.date("d/m/Y H:i", strtotime($venta["fecha"])).'
</td>
</tr>
<tr>
<td>
<b>Cliente:</b><br>
'.htmlspecialchars($venta["cliente"]).'
</td>
<td>
<b>Estado:</b><br>
'.($venta["estado"] == 1 || $venta["estado"] == "1" || $venta["estado"] === true || $venta["estado"] == "t" ? "ACTIVA" : "ANULADA").'
</td>
</tr>
</table>

<hr>

<table class="tabla-detalle">
<thead>
<tr>
<th>Plataforma</th>
<th>Cantidad</th>
<th>Precio</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>
';

$listaDetalle = is_array($detalle) ? $detalle : [];

if(!empty($listaDetalle)){
    foreach($listaDetalle as $fila){
        $cant = (int)$fila["cantidad"];
        $prec = (float)$fila["precio"];
        $sub = (float)($fila["subtotal"] ?? ($cant * $prec));

        $html .= '
        <tr>
            <td>'.htmlspecialchars($fila["nombre"]).'</td>
            <td align="center">'.$cant.'</td>
            <td align="right">S/. '.number_format($prec, 2).'</td>
            <td align="right">S/. '.number_format($sub, 2).'</td>
        </tr>
        ';
    }
} else {
    $html .= '
    <tr>
        <td colspan="4" align="center" style="color:#777;">No se registraron productos para esta venta.</td>
    </tr>
    ';
}

$html .= '
</tbody>
</table>

<br>

<table width="100%">
<tr>
<td width="50%"></td>
<td width="50%">
<table width="100%" style="border-collapse:collapse;">
<tr>
<td style="background:#d62828; color:white; padding:12px; font-size:16px; font-weight:bold; text-align:center;">
TOTAL
</td>
<td style="padding:12px; font-size:18px; font-weight:bold; text-align:right; border:1px solid #d62828;">
S/. '.number_format($venta["total"], 2).'
</td>
</tr>
</table>
</td>
</tr>
</table>

<div class="footer">
<hr>
<b>Gracias por confiar en EvyStream!</b>
<br><br>
Este comprobante fue generado automaticamente por el sistema.
<br>
EvyStream '.date("Y").'
</div>
';

$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("comprobante_venta_".$venta["id_venta"].".pdf", array(
    "Attachment" => false
));

?>