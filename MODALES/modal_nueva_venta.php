<?php
include_once("../CONTROLADOR/PlataformaControlador.php");
$controladorPlat = new PlataformaControlador();
$productos = $controladorPlat->MostrarActivos();
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="modal fade" id="modalNuevaVenta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="formNuevaVenta" action="../AJAX/registrar_venta.ajax.php" method="POST">
                
                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title">
                        <i class="fas fa-shopping-cart"></i> Nueva Venta
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cliente</label>
                                <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Nombre del cliente" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="date" class="form-control" value="<?php echo date('Y-m-d');?>" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-5">
                            <label>Plataforma o producto</label>
                            <small class="form-text text-muted mb-1">Para un combo, agrega una plataforma y repite el proceso con las demás.</small>
                            <select class="form-control" id="producto">
                                <option value="">Seleccione una plataforma</option>
                                <?php
                                $listaProductos = is_array($productos) ? $productos : [];
                                foreach($listaProductos as $fila){
                                ?>
                                <option value="<?php echo $fila["id_producto"]; ?>" data-nombre="<?php echo htmlspecialchars($fila["nombre"]); ?>" data-precio="<?php echo $fila["precio"]; ?>" data-stock="<?php echo $fila["stock"]; ?>">
                                    <?php echo $fila["nombre"] . " | Stock: ".$fila["stock"]. " | S/. ".$fila["precio"]; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" value="1" min="1">
                        </div>

                        <div class="col-md-2">
                            <label>Precio</label>
                            <input type="text" class="form-control" id="precio" readonly>
                        </div>

                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-success btn-block" id="btnAgregarProducto">
                                <i class="fas fa-plus"></i> Agregar
                            </button>
                        </div>
                    </div>

                    <br>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="tablaDetalleVenta">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Producto</th>
                                    <th width="100">Cantidad</th>
                                    <th width="120">Precio</th>
                                    <th width="120">Subtotal</th>
                                    <th width="80">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">TOTAL S/.</span>
                                </div>
                                <input type="text" class="form-control font-weight-bold" id="totalVenta" name="total" value="0.00" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Venta
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

    function actualizarCampoPrecio(){
        let opt = $("#producto option:selected");
        let pAttr = opt.attr("data-precio");
        if(pAttr && !isNaN(parseFloat(pAttr))){
            $("#precio").val(parseFloat(pAttr).toFixed(2));
            return;
        }
        let txt = opt.text();
        let match = txt.match(/S\/\.\s*([\d.]+)/i);
        if(match){
            $("#precio").val(parseFloat(match[1]).toFixed(2));
        } else {
            $("#precio").val("");
        }
    }

    $(document).on("change", "#producto", function(){
        actualizarCampoPrecio();
    });

    $("#modalNuevaVenta").on("shown.bs.modal", function(){
        actualizarCampoPrecio();
    });

    $("#btnAgregarProducto").click(function(){
        let opt = $("#producto option:selected");
        let idProducto = $("#producto").val();

        if(!idProducto){
            alert("Seleccione una plataforma.");
            return;
        }

        if($("#tablaDetalleVenta input[name='productos[]'][value='" + idProducto + "']").length > 0){
            alert("Esta plataforma ya fue agregada a la venta.");
            return;
        }

        let nombreLimpio = opt.attr("data-nombre");
        if(!nombreLimpio){
            let txt = opt.text();
            nombreLimpio = txt.split("|")[0].trim();
        }

        let precioVal = $("#precio").val();
        let precio = parseFloat(precioVal);

        if(isNaN(precio) || precio <= 0){
            let pAttr = opt.attr("data-precio");
            if(pAttr) precio = parseFloat(pAttr);
        }

        if(isNaN(precio) || precio <= 0){
            let txt = opt.text();
            let match = txt.match(/S\/\.\s*([\d.]+)/i);
            if(match) precio = parseFloat(match[1]);
        }

        if(isNaN(precio) || precio <= 0){
            alert("Seleccione un producto con precio válido.");
            return;
        }

        let cantidad = parseInt($("#cantidad").val());
        if(isNaN(cantidad) || cantidad <= 0){
            alert("Ingrese una cantidad válida.");
            return;
        }

        let stockAttr = opt.attr("data-stock");
        let stock = parseInt(stockAttr);
        if(!isNaN(stock) && cantidad > stock){
            alert("La cantidad supera el stock disponible.");
            return;
        }

        let subtotalNum = cantidad * precio;
        let subtotalStr = subtotalNum.toFixed(2);
        let precioStr = precio.toFixed(2);

        let fila = '<tr>' +
            '<td>' +
                '<input type="hidden" name="productos[]" value="' + idProducto + '">' +
                '<strong>' + nombreLimpio + '</strong>' +
            '</td>' +
            '<td>' +
                '<input type="hidden" name="cantidades[]" value="' + cantidad + '">' +
                cantidad +
            '</td>' +
            '<td>' +
                '<input type="hidden" name="precios[]" value="' + precioStr + '">' +
                'S/. ' + precioStr +
            '</td>' +
            '<td class="subtotal-item" data-val="' + subtotalStr + '">S/. ' + subtotalStr + '</td>' +
            '<td>' +
                '<button type="button" class="btn btn-danger btn-sm btnEliminarFila"><i class="fas fa-trash"></i></button>' +
            '</td>' +
        '</tr>';

        $("#tablaDetalleVenta tbody").append(fila);
        calcularTotal();

        $("#producto").val("");
        $("#precio").val("");
        $("#cantidad").val(1);
    });

    $(document).on("click", ".btnEliminarFila", function(){
        $(this).closest("tr").remove();
        calcularTotal();
    });

    $("#formNuevaVenta").submit(function(e){
        if($("#tablaDetalleVenta tbody tr").length === 0){
            e.preventDefault();
            alert("Debe agregar al menos un producto a la venta.");
            return false;
        }
    });

    function calcularTotal(){
        let total = 0;
        $("#tablaDetalleVenta tbody tr").each(function(){
            let sub = parseFloat($(this).find(".subtotal-item").attr("data-val")) || 0;
            total += sub;
        });
        $("#totalVenta").val(total.toFixed(2));
    }
});
</script>
