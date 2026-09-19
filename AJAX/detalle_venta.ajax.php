<?php

include_once(__DIR__ . "/../CONTROLADOR/VentaControlador.php");

$idVenta = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($idVenta <= 0) {
    echo '<div class="alert alert-danger">ID de venta no válido.</div>';
    exit();
}

$controlador = new VentaControlador();
$venta = $controlador->MostrarVenta($idVenta);
$detalles = $controlador->MostrarDetalle($idVenta);

if (!$venta) {
    echo '<div class="alert alert-warning">No se encontró la información de la venta.</div>';
    exit();
}
?>

<div id="areaImpresionVenta" class="p-3 bg-white">
    <div class="form-group mb-2">
        <label class="font-weight-bold mb-0">Venta N°</label>
        <div><?php echo intval($venta['id_venta']); ?></div>
    </div>

    <div class="form-group mb-2">
        <label class="font-weight-bold mb-0">Cliente</label>
        <div><?php echo htmlspecialchars($venta['cliente']); ?></div>
    </div>

    <div class="form-group mb-3">
        <label class="font-weight-bold mb-0">Fecha</label>
        <div><?php echo date("d/m/Y H:i", strtotime($venta['fecha'])); ?></div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Plataforma</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($detalles) && is_array($detalles)) {
                    foreach ($detalles as $item) {
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td><?php echo intval($item['cantidad']); ?></td>
                            <td><?php echo number_format($item['precio'], 2); ?></td>
                            <td><?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="4" class="text-center py-3">
                            <i class="fas fa-exclamation-triangle text-warning"></i> No hay productos registrados en esta venta.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="text-right my-3">
        <h2 class="font-weight-bold text-dark">Total: S/. <?php echo number_format($venta['total'], 2); ?></h2>
    </div>
</div>

<div class="text-right pt-3 border-top">
    <button type="button" class="btn btn-danger" onclick="generarPDFVenta()">
        <i class="fas fa-file-pdf"></i> PDF
    </button>
    <button type="button" class="btn btn-primary" onclick="imprimirVentaDirecto()">
        <i class="fas fa-print"></i> Imprimir
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        <i class="fas fa-times"></i> Cerrar
    </button>
</div>

<script>
function imprimirVentaDirecto() {
    let contenido = document.getElementById('areaImpresionVenta').innerHTML;
    let ventana = window.open('', '', 'height=600,width=800');
    ventana.document.write('<html><head><title>Comprobante de Venta</title>');
    ventana.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">');
    ventana.document.write('</head><body class="p-4">');
    ventana.document.write(contenido);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.focus();
    setTimeout(function() {
        ventana.print();
        ventana.close();
    }, 500);
}

function generarPDFVenta() {
    window.open('../PDF/comprobante_venta.php?id=<?php echo $idVenta; ?>', '_blank');
}
</script>