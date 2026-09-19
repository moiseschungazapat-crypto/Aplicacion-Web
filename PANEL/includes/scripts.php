<script src="../ADMINLTE/plugins/jquery/jquery.min.js"></script>

<script src="../ADMINLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../ADMINLTE/dist/js/adminlte.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).ready(function(){

    //=========================
    // DATATABLE
    //=========================

    var tabla = $("#tablaPlataformas").DataTable({

    responsive:true,
    autoWidth:false,

    language:{
        url:"https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
    }

});

$("#filtroEstado").on("change", function(){

    tabla.column(7).search($(this).val()).draw();

});

    //=========================
    // TOOLTIPS
    //=========================

    $('[data-toggle="tooltip"]').tooltip();

});


//=========================
// BOTON EDITAR
//=========================

$(document).on("click",".btnEditar",function(){

    $("#editarId").val($(this).data("id"));

    $("#editarCategoria").val($(this).data("categoria"));

    $("#editarNombre").val($(this).data("nombre"));

    $("#editarDescripcion").val($(this).data("descripcion"));

    $("#editarPrecio").val($(this).data("precio"));

    $("#editarStock").val($(this).data("stock"));

    $("#imagenActual").val($(this).data("imagen"));

    let imagen=$(this).data("imagen");

    if(imagen==""){

        imagen="sin-imagen.png";

    }

    $("#previewEditar").attr(

        "src",

        "../IMG/productos/"+imagen

    );

    $("#modalEditar").modal("show");

});

/*=====================================
ELIMINAR PLATAFORMA
======================================*/

$(document).on("click", ".btnEliminar", function(){

    let id = $(this).data("id");

    let imagen = $(this).closest("tr").find("img").attr("src");

    imagen = imagen.split("/").pop();

    Swal.fire({

        title: "¿Eliminar plataforma?",

        text: "Esta acción no se puede deshacer.",

        icon: "warning",

        showCancelButton: true,

        confirmButtonColor: "#d33",

        cancelButtonColor: "#3085d6",

        confirmButtonText: "Sí, eliminar",

        cancelButtonText: "Cancelar"

    }).then((result)=>{

        if(result.isConfirmed){

            window.location =
            "../AJAX/eliminar_plataforma.ajax.php?id="
            + id +
            "&imagen=" + imagen;

        }

    });

});


/*=====================================
VISTA PREVIA NUEVA IMAGEN
======================================*/

$("#imagenNueva").change(function(){

    const archivo = this.files[0];

    if(!archivo) return;

    const lector = new FileReader();

    lector.onload = function(e){

        $("#previewNueva").attr("src", e.target.result);

    };

    lector.readAsDataURL(archivo);

});

/*=====================================
VISTA PREVIA EDITAR IMAGEN
======================================*/

$("#editarImagen").change(function(){

    const archivo = this.files[0];

    if(!archivo) return;

    const lector = new FileReader();

    lector.onload = function(e){

        $("#previewEditar").attr("src", e.target.result);

    };

    lector.readAsDataURL(archivo);

});

/*=====================================
CAMBIAR ESTADO
======================================*/

$(document).on("click", ".btnEstado", function(){

    let boton = $(this);

    let id = boton.data("id");

    let estado = boton.data("estado");

    $.ajax({

        url: "../AJAX/cambiar_estado.ajax.php",

        method: "POST",

        data: {

            id: id,
            estado: estado

        },

        success:function(respuesta){

                console.log(respuesta);

                if($.trim(respuesta) == "ok"){

                if(estado == 1){

                    boton
                    .removeClass("btn-danger")
                    .addClass("btn-success")
                    .text("Activo")
                    .data("estado",0);

                }else{

                    boton
                    .removeClass("btn-success")
                    .addClass("btn-danger")
                    .text("Inactivo")
                    .data("estado",1);

                }

                Swal.fire({

                    icon:"success",
                    title:"Estado actualizado",
                    timer:1200,
                    showConfirmButton:false

                });

            }else{

                Swal.fire({

                    icon:"error",
                    title:"No se pudo actualizar"

                });

            }

        }

    });

});

/*=====================================
AUTOCOMPLETAR PRECIO
======================================*/

$("#producto").change(function(){

    let precio = $(this).find(":selected").data("precio");

    $("#precio").val(precio);

});

/*=====================================
DETALLE DE LA VENTA
======================================*/

let totalVenta = 0;

$("#btnAgregarProducto").click(function(){

    let producto = $("#producto option:selected");

    if(producto.val()==""){

        Swal.fire({

            icon:"warning",

            title:"Seleccione una plataforma"

        });

        return;

    }

    let id = producto.val();

    let nombre = producto.text();

    let precio = parseFloat(producto.data("precio"));

    let cantidad = parseInt($("#cantidad").val());

    let subtotal = precio * cantidad;

    totalVenta += subtotal;

    $("#totalVenta").val(totalVenta.toFixed(2));

    $("#tablaDetalleVenta tbody").append(`

        <tr>

            <td>

                ${nombre}

                <input
                    type="hidden"
                    name="producto[]"
                    value="${id}">

            </td>

            <td>

                ${cantidad}

                <input
                    type="hidden"
                    name="cantidad[]"
                    value="${cantidad}">

            </td>

            <td>

                ${precio.toFixed(2)}

                <input
                    type="hidden"
                    name="precio[]"
                    value="${precio}">

            </td>

            <td class="subtotal">

                ${subtotal.toFixed(2)}

            </td>

            <td>

                <button

                    type="button"

                    class="btn btn-danger btn-sm eliminarFila">

                    <i class="fas fa-trash"></i>

                </button>

            </td>

        </tr>

    `);

});

/*=====================================
ELIMINAR FILA
======================================*/

$(document).on("click",".eliminarFila",function(){

    let fila = $(this).closest("tr");

    let subtotal = parseFloat(

        fila.find(".subtotal").text()

    );

    totalVenta -= subtotal;

    $("#totalVenta").val(totalVenta.toFixed(2));

    fila.remove();

});

/*=====================================
VER DETALLE DE VENTA
======================================*/

$(document).on("click",".btnDetalle",function(){

    let idVenta = $(this).data("id");

    // Actualizar enlace del PDF
    $("#btnPDFVenta").attr(
        "href",
        "../PDF/comprobante_venta.php?id=" + idVenta
    );

    // Mostrar cargando
    $("#contenidoDetalleVenta").html(

        '<div class="text-center">'+
        '<i class="fas fa-spinner fa-spin fa-2x"></i>'+
        '<br><br>Cargando...'+
        '</div>'

    );

    // Abrir modal
    $("#modalDetalleVenta").modal("show");

    // Cargar detalle
    $("#contenidoDetalleVenta").load(

        "../AJAX/detalle_venta.ajax.php?id=" + idVenta

    );

});

/*=====================================
IMPRIMIR VENTA
======================================*/

$(document).on("click","#btnImprimirVenta",function(){

    let contenido = $("#areaImprimir").html();

    let ventana = window.open(

        "",

        "",

        "width=900,height=700"

    );

    ventana.document.write(`

        <html>

        <head>

            <title>Detalle de Venta</title>

            <style>

                body{

                    font-family:Arial;

                    margin:25px;

                }

                table{

                    width:100%;

                    border-collapse:collapse;

                }

                table,th,td{

                    border:1px solid #000;

                }

                th,td{

                    padding:8px;

                    text-align:center;

                }

                h3{

                    text-align:right;

                }

            </style>

        </head>

        <body>

            ${contenido}

        </body>

        </html>

    `);

    ventana.document.close();

    ventana.focus();

    ventana.print();

});

/*=====================================
ANULAR VENTA
======================================*/

$(document).on("click", ".btnAnular", function(){

    let idVenta = $(this).data("id");

    Swal.fire({

        title: "¿Anular esta venta?",
        text: "Esta acción cambiará el estado de la venta.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, anular",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#d33"

    }).then((result)=>{

        if(result.isConfirmed){

            $.ajax({

                url:"../AJAX/anular_venta.ajax.php",

                method:"POST",

                data:{
                    idVenta:idVenta
                },

                success:function(respuesta){

                    if($.trim(respuesta)=="ok"){

                        Swal.fire({

                            icon:"success",
                            title:"Venta anulada",
                            timer:1200,
                            showConfirmButton:false

                        }).then(()=>{

                            location.reload();

                        });

                    }else{

                        Swal.fire({

                            icon:"error",
                            title:"No se pudo anular"

                        });

                    }

                }

            });

        }

    });

});

</script>

</body>

</html>