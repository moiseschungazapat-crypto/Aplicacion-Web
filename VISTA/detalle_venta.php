<?php
require_once(__DIR__ . "/../CONTROLADOR/VentaControlador.php");

$controlador = new VentaControlador();

$idVenta = isset($_GET['id']) ? intval($_GET['id']) : (isset($_GET['id_venta']) ? intval($_GET['id_venta']) : 0);

$venta = $controlador->MostrarVenta($idVenta);
$detalles = $controlador->MostrarDetalle($idVenta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Venta #<?php echo $idVenta; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Comprobante / Detalle de Venta #<?php echo $idVenta; ?></h5>
            <a href="javascript:history.back()" class="btn btn-sm btn-outline-light"><i class="fas fa-arrow-left me-1"></i> Volver</a>
        </div>
        <div class="card-body">
            <?php if ($venta): ?>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Cliente:</strong> <?php echo htmlspecialchars($venta['cliente'] ?? 'Cliente General'); ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Estado:</strong> 
                        <?php if ($venta['estado'] == 1): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Anulado</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <strong>Total Venta:</strong> S/ <?php echo number_format($venta['total'], 2); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">No se encontró la información de la venta especificada.</div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Plataforma / Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($detalles) && is_array($detalles)): ?>
                            <?php foreach ($detalles as $i => $item): ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                                    <td><?php echo intval($item['cantidad']); ?></td>
                                    <td>S/ <?php echo number_format($item['precio'], 2); ?></td>
                                    <td>S/ <?php echo number_format($item['subtotal'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay productos registrados para esta venta.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>