<?php

require_once("../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

include("../CONTROLADOR/VentaControlador.php");

$controlador = new VentaControlador();
$idVenta = $_GET["id"] ?? 0;
$venta = $controlador->MostrarVenta($idVenta);
$detalle = $controlador->MostrarDetalle($idVenta);

if (!$venta) {
    die("No se encontró la venta solicitada.");
}

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);

$rutaLogo = __DIR__ . "/../IMG/logo2.jpg";
$logo = "";
if (file_exists($rutaLogo)) {
    $logo = "data:image/jpeg;base64," . base64_encode(file_get_contents($rutaLogo));
}

$listaDetalle = is_array($detalle) ? $detalle : [];
$subtotalCalculado = 0;
foreach ($listaDetalle as $fila) {
    $subtotalCalculado += (float)($fila["subtotal"] ?? ((int)$fila["cantidad"] * (float)$fila["precio"]));
}

$totalVenta = (float)($venta["total"] ?? $subtotalCalculado);
$descuento = max(0, $subtotalCalculado - $totalVenta);
$ventaActiva = $venta["estado"] == 1 || $venta["estado"] == "1" || $venta["estado"] === true || $venta["estado"] == "t";
$estadoTexto = $ventaActiva ? "PAGO CONFIRMADO" : "VENTA ANULADA";
$estadoColor = $ventaActiva ? "#159447" : "#b42318";
$estadoFondo = $ventaActiva ? "#e4f7ea" : "#fde8e7";
$cliente = htmlspecialchars((string)($venta["cliente"] ?? "Cliente"), ENT_QUOTES, 'UTF-8');
$fechaEmision = date("d/m/Y H:i", strtotime($venta["fecha"]));
$numeroComprobante = "EVY-" . str_pad($venta["id_venta"], 6, "0", STR_PAD_LEFT);
$whatsappUrl = "https://wa.me/51931880582?text=" . rawurlencode("Hola EvyStream, quiero información sobre las cuentas de streaming por favor.");
$qrWhatsapp = (new Builder(
    writer: new PngWriter(),
    writerOptions: [],
    validateResult: false,
    data: $whatsappUrl,
    encoding: new Encoding('UTF-8'),
    errorCorrectionLevel: ErrorCorrectionLevel::High,
    size: 180,
    margin: 8,
    roundBlockSizeMode: RoundBlockSizeMode::Margin
))->build()->getDataUri();

$html = '<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
@page { margin: 28px 34px 26px; }
* { box-sizing: border-box; }
body {
    margin: 0;
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 10px;
    color: #25304a;
    background: #ffffff;
}
table { border-collapse: collapse; }
.header { width: 100%; margin-bottom: 18px; }
.header-logo { width: 76px; height: 76px; object-fit: contain; }
.brand-name { color: #7227ed; font-size: 25px; font-weight: bold; letter-spacing: -.5px; }
.brand-name span { color: #e52b32; }
.brand-subtitle { margin-top: 3px; color: #68738a; font-size: 10px; }
.contact { border-left: 1px solid #dfe3ef; padding-left: 18px; color: #566178; font-size: 9px; line-height: 1.9; }
.contact strong { color: #26314a; }
.tagline { color: #7227ed; font-size: 10px; font-style: italic; text-align: right; }
.accent-line { width: 34px; height: 3px; margin: 5px 0 0 auto; background: #e52b32; }
.title-bar { width: 100%; background: #7227ed; color: #ffffff; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; }
.title-icon { width: 30px; height: 30px; color: #7227ed; background: #ffffff; border-radius: 6px; text-align: center; font-size: 20px; font-weight: bold; }
.title-text { padding-left: 12px; font-size: 17px; font-weight: bold; letter-spacing: .2px; }
.title-note { color: #f8d8de; font-size: 9px; font-style: italic; text-align: right; }
.info-card { width: 100%; border: 1px solid #dce2ef; border-radius: 8px; padding: 13px 14px 5px; margin-bottom: 16px; background: #fbfcff; }
.info-cell { width: 33.33%; padding: 0 12px 10px 0; vertical-align: top; }
.info-cell + .info-cell { padding-left: 12px; border-left: 1px solid #e1e5ef; }
.label { color: #727d92; font-size: 9px; margin-bottom: 4px; }
.value { color: #19243d; font-size: 11px; font-weight: bold; }
.value-large { font-size: 14px; }
.status { color: ' . $estadoColor . '; font-size: 9px; font-weight: bold; background: ' . $estadoFondo . '; padding: 6px 8px; border-radius: 6px; display: inline-block; }
.section-caption { color: #19243d; font-size: 11px; font-weight: bold; margin: 4px 0 7px; }
.details { width: 100%; margin-bottom: 12px; }
.details th { background: #e52b32; color: #ffffff; padding: 9px 10px; font-size: 9.5px; text-align: left; }
.details th.center, .details td.center { text-align: center; }
.details th.right, .details td.right { text-align: right; }
.details td { border: 1px solid #dfe3eb; padding: 9px 10px; font-size: 9.5px; }
.details tbody tr:nth-child(even) td { background: #fafbfe; }
.summary-wrap { width: 100%; margin-top: 4px; margin-bottom: 18px; }
.summary-message { width: 49%; padding: 13px 14px; background: #f6f3ff; border-radius: 8px; vertical-align: middle; }
.summary-message-title { color: #19243d; font-size: 12px; font-weight: bold; }
.summary-message-text { color: #68738a; font-size: 9px; line-height: 1.5; margin-top: 5px; }
.summary { width: 48%; margin-left: 3%; border: 1px solid #dce2ef; border-radius: 8px; overflow: hidden; }
.summary td { padding: 8px 10px; font-size: 10px; }
.summary .amount { text-align: right; font-weight: bold; }
.summary .total-label, .summary .total-amount { color: #ffffff; background: #e52b32; font-size: 12px; font-weight: bold; padding: 11px 8px; }
.summary .total-amount { text-align: right; }
.summary .total-label, .summary .total-amount { white-space: nowrap; }
.support { width: 100%; border-top: 1px solid #d9deea; border-bottom: 1px solid #d9deea; padding: 14px 0; }
.qr-cell { width: 96px; vertical-align: middle; }
.qr-code { width: 88px; height: 88px; }
.support-box { vertical-align: middle; padding-right: 16px; }
.support-icon { width: 42px; height: 42px; background: #e4f7ea; border-radius: 8px; color: #159447; font-size: 21px; font-weight: bold; text-align: center; vertical-align: middle; }
.support-title { color: #19243d; font-size: 11px; font-weight: bold; padding-left: 10px; }
.support-text { color: #68738a; font-size: 9px; line-height: 1.5; padding-left: 10px; }
.support-separator { border-left: 1px solid #dfe3ef; padding-left: 18px; }
.footer { width: 100%; padding-top: 13px; color: #68738a; font-size: 8.5px; }
.footer strong { color: #7227ed; }
.footer .right { text-align: right; }
</style>
</head>
<body>
<table class="header">
    <tr>
        <td width="12%">' . ($logo ? '<img class="header-logo" src="' . $logo . '" alt="EvyStream">' : '') . '</td>
        <td width="38%" valign="middle">
            <div class="brand-name">Evy<span>Stream</span></div>
            <div class="brand-subtitle">Sistema de Gestión Comercial</div>
        </td>
        <td width="30%" class="contact" valign="middle">
            <strong>Contacto</strong><br>
            WhatsApp: +51 931 880 582<br>
            EvyStream - Lima, Perú
        </td>
        <td width="20%" valign="middle">
            <div class="tagline">Tu entretenimiento,<br>más cerca de ti.</div>
            <div class="accent-line"></div>
        </td>
    </tr>
</table>

<table class="title-bar">
    <tr>
        <td class="title-icon">E</td>
        <td class="title-text">COMPROBANTE DE VENTA</td>
        <td class="title-note">Entretenimiento sin límites</td>
    </tr>
</table>

<table class="info-card">
    <tr>
        <td class="info-cell">
            <div class="label">N° Comprobante</div>
            <div class="value value-large">' . $numeroComprobante . '</div>
        </td>
        <td class="info-cell">
            <div class="label">Fecha de emisión</div>
            <div class="value">' . $fechaEmision . '</div>
        </td>
        <td class="info-cell">
            <div class="label">Estado de la venta</div>
            <div class="status">' . $estadoTexto . '</div>
        </td>
    </tr>
    <tr>
        <td class="info-cell">
            <div class="label">Cliente</div>
            <div class="value">' . $cliente . '</div>
        </td>
        <td class="info-cell">
            <div class="label">Productos registrados</div>
            <div class="value">' . count($listaDetalle) . '</div>
        </td>
        <td class="info-cell">
            <div class="label">Atención</div>
            <div class="value">Soporte personalizado</div>
        </td>
    </tr>
</table>

<div class="section-caption">Detalle de productos y plataformas</div>
<table class="details">
    <thead>
        <tr>
            <th width="46%">Producto</th>
            <th width="16%" class="center">Cantidad</th>
            <th width="19%" class="right">Precio</th>
            <th width="19%" class="right">Subtotal</th>
        </tr>
    </thead>
    <tbody>';

if (!empty($listaDetalle)) {
    foreach ($listaDetalle as $fila) {
        $nombreProducto = htmlspecialchars((string)($fila["nombre"] ?? "Producto"), ENT_QUOTES, 'UTF-8');
        $cantidad = (int)($fila["cantidad"] ?? 0);
        $precio = (float)($fila["precio"] ?? 0);
        $subtotal = (float)($fila["subtotal"] ?? ($cantidad * $precio));
        $html .= '
        <tr>
            <td>' . $nombreProducto . '</td>
            <td class="center">' . $cantidad . '</td>
            <td class="right">S/. ' . number_format($precio, 2) . '</td>
            <td class="right">S/. ' . number_format($subtotal, 2) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="4" class="center">No se registraron productos para esta venta.</td></tr>';
}

$html .= '
    </tbody>
</table>

<table class="summary-wrap">
    <tr>
        <td class="summary-message">
            <div class="summary-message-title">Gracias por ser parte de EvyStream</div>
            <div class="summary-message-text">Disfruta el mejor contenido en un solo lugar, con atención y soporte personalizado.</div>
        </td>
        <td width="3%"></td>
        <td valign="middle">
            <table class="summary">
                <tr><td>Subtotal</td><td class="amount">S/. ' . number_format($subtotalCalculado, 2) . '</td></tr>
                <tr><td>Descuento</td><td class="amount">S/. ' . number_format($descuento, 2) . '</td></tr>
                <tr><td class="total-label">TOTAL</td><td class="total-amount">S/. ' . number_format($totalVenta, 2) . '</td></tr>
            </table>
        </td>
    </tr>
</table>

<table class="support">
    <tr>
        <td class="qr-cell">
            <img class="qr-code" src="' . $qrWhatsapp . '" alt="Código QR de WhatsApp">
        </td>
        <td class="support-box" width="47%">
            <table>
                <tr>
                    <td>
                        <div class="support-title">Contáctanos por WhatsApp</div>
                        <div class="support-text">Escanea el código QR<br>o escribe al +51 931 880 582.</div>
                    </td>
                </tr>
            </table>
        </td>
        <td class="support-separator" width="45%">
            <div class="support-title">Compra 100% segura</div>
            <div class="support-text">Tus datos están protegidos.<br>Gracias por confiar en EvyStream.</div>
        </td>
    </tr>
</table>

<table class="footer">
    <tr>
        <td><strong>EvyStream</strong><br>Sistema de Gestión Comercial</td>
        <td class="right">© ' . date("Y") . ' EvyStream. Todos los derechos reservados.<br>Comprobante generado automáticamente.</td>
    </tr>
</table>
</body>
</html>';

$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("comprobante_venta_" . $venta["id_venta"] . ".pdf", [
    "Attachment" => false
]);

?>
