<?php

session_start();

if(!isset($_SESSION["id_usuario"])){
    header("Location: ../LOGIN/login.php");
    exit();
}

include_once("../CONTROLADOR/VentaControlador.php");

$controlador = new VentaControlador();
$ventas = $controlador->MostrarVentas();

$menu = "ventas";
$titulo = "Ventas";

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Gestión de Ventas</h1>
            </div>
            <div class="col-sm-6 text-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#modalNuevaVenta">
                    <i class="fas fa-shopping-cart"></i> Nueva Venta
                </button>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cash-register"></i> Historial de Ventas
                </h3>
            </div>
            <div class="card-body">
                <table id="tablaVentas" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $datos = is_array($ventas) ? $ventas : [];
                    foreach ($datos as $fila) {
                    ?>
                        <tr>
                            <td><?php echo $fila["id_venta"]; ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($fila["fecha"])); ?></td>
                            <td><?php echo $fila["cliente"]; ?></td>
                            <td>S/. <?php echo number_format($fila["total"], 2); ?></td>
                            <td>
                                <?php if($fila["estado"] == "1" || $fila["estado"] === true || $fila["estado"] == "t"){ ?>
                                    <span class="badge badge-success">Activa</span>
                                <?php }else{ ?>
                                    <span class="badge badge-danger">Anulada</span>
                                <?php } ?>
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm btnDetalle" data-id="<?php echo $fila["id_venta"]; ?>" title="Ver Detalle">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if($fila["estado"] == "1" || $fila["estado"] === true || $fila["estado"] == "t"){ ?>
                                    <button class="btn btn-danger btn-sm btnAnular" data-id="<?php echo $fila["id_venta"]; ?>" title="Anular Venta">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                <?php }else{ ?>
                                    <button class="btn btn-success btn-sm btnActivar" data-id="<?php echo $fila["id_venta"]; ?>" title="Reactivar Venta">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php
if(file_exists("../MODALES/modal_nueva_venta.php")) {
    include("../MODALES/modal_nueva_venta.php");
}
include("../MODALES/modal_detalle_venta.php");
include("includes/footer.php");
include("includes/scripts.php");
?>

<script>
$(document).ready(function(){
    if ($.fn.DataTable.isDataTable('#tablaVentas')) {
        $('#tablaVentas').DataTable().destroy();
    }

    $("#tablaVentas").DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
        }
    });

    $(document).on("click", ".btnAnular", function(){
        let id = $(this).attr("data-id");
        if(confirm("¿Estás seguro de anular esta venta?")){
            window.location.href = "../AJAX/anular_venta.ajax.php?id=" + id;
        }
    });

    $(document).on("click", ".btnActivar", function(){
        let id = $(this).attr("data-id");
        if(confirm("¿Estás seguro de reactivar esta venta?")){
            window.location.href = "../AJAX/activar_venta.ajax.php?id=" + id;
        }
    });

    $(document).on("click", ".btnDetalle", function(){
        let id = $(this).attr("data-id");
        
        $("#contenidoDetalleVenta").html(`
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x text-info"></i>
                <p class="mt-2 text-muted">Cargando detalle...</p>
            </div>
        `);
        
        $("#modalDetalleVenta").modal("show");

        $.ajax({
            url: "../AJAX/detalle_venta.ajax.php?id=" + id,
            type: "GET",
            success: function(response){
                $("#contenidoDetalleVenta").html(response);
            },
            error: function(){
                $("#contenidoDetalleVenta").html('<div class="alert alert-danger">Error al cargar la información.</div>');
            }
        });
    });
});
</script>