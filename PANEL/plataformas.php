<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION["id_usuario"])){
    header("Location: ../LOGIN/login.php");
    exit();
}

$menu = "plataformas";
$titulo = "Plataformas";

include("../CONTROLADOR/PlataformaControlador.php");

$controlador = new PlataformaControlador();

// Procesar acciones POST (Guardar / Editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $nombre       = $_POST['nombre'] ?? '';
    $descripcion  = $_POST['descripcion'] ?? '';
    $precio       = $_POST['precio'] ?? 0;
    $stock        = $_POST['stock'] ?? 0;
    $id_categoria = $_POST['id_categoria'] ?? 1;
    
    $imagen = $_POST['imagen_actual'] ?? 'default.png';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $directorioDestino = "../IMG/productos/";
        if (!file_exists($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nuevoNombre = "plat_" . time() . "." . $ext;
        $rutaDestino = $directorioDestino . $nuevoNombre;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen = $nuevoNombre;
        }
    }

    if ($_POST['accion'] === 'guardar') {
        if (method_exists($controlador, 'RegistrarPlataforma')) {
            $controlador->RegistrarPlataforma($id_categoria, $nombre, $descripcion, $precio, $imagen, $stock);
        } elseif (method_exists($controlador, 'Registrar')) {
            $controlador->Registrar($id_categoria, $nombre, $descripcion, $precio, $imagen, $stock);
        }
        header("Location: plataformas.php");
        exit();
    }

    if ($_POST['accion'] === 'editar') {
        $id_producto = $_POST['id_producto'] ?? '';
        if (method_exists($controlador, 'ActualizarPlataforma')) {
            $controlador->ActualizarPlataforma($id_producto, $id_categoria, $nombre, $descripcion, $precio, $imagen, $stock);
        } elseif (method_exists($controlador, 'Actualizar')) {
            $controlador->Actualizar($id_producto, $id_categoria, $nombre, $descripcion, $precio, $imagen, $stock);
        }
        header("Location: plataformas.php");
        exit();
    }
}

// Procesar acción GET (Eliminar)
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (method_exists($controlador, 'EliminarPlataforma')) {
        $controlador->EliminarPlataforma($id);
    } elseif (method_exists($controlador, 'Eliminar')) {
        $controlador->Eliminar($id);
    }
    header("Location: plataformas.php");
    exit();
}

$plataformas = $controlador->Mostrar();

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

?>

<section class="content-header">

    <div class="container-fluid">

        <h1>
            Gestión de Plataformas
        </h1>

    </div>

</section>

<section class="content">

<div class="container-fluid">

<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-tv"></i>

            Gestión de Plataformas

        </h3>

        <div class="card-tools">

            <button
                class="btn btn-success"
                data-toggle="modal"
                data-target="#modalNuevaPlataforma">

                <i class="fas fa-plus"></i>

                Nueva Plataforma

            </button>

        </div>

    </div>

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-3">

            <label>Filtrar por Estado</label>

             <select
            class="form-control"
            id="filtroEstado">

            <option value="">Todos</option>
            <option value="Activo">Activos</option>
            <option value="Inactivo">Inactivos</option>

        </select>

    </div>

</div>

        <table id="tablaPlataformas" class="table table-bordered table-striped">

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Categoría</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

            <?php
            $filasPlataformas = [];

            if (is_array($plataformas)) {
                $filasPlataformas = $plataformas;
            } elseif ($plataformas) {
                while ($f = pg_fetch_assoc($plataformas)) {
                    $filasPlataformas[] = $f;
                }
            }

            foreach ($filasPlataformas as $fila) { 
            ?>

                <tr>

                    <td><?php echo $fila["id_producto"]; ?></td>
                    <td><?php echo $fila["id_categoria"]; ?></td>
                    <td><?php echo $fila["nombre"]; ?></td>
                    <td><?php echo $fila["descripcion"]; ?></td>
                    <td>S/. <?php echo number_format($fila["precio"],2); ?></td>

                    <td>

                        <img src="../IMG/productos/<?php echo $fila["imagen"]; ?>"
                             width="60">

                    </td>

                    <td><?php echo $fila["stock"]; ?></td>

                    <td>

                        <?php

                    if($fila["estado"] == "t" || $fila["estado"] == "1" || $fila["estado"] === true){

                    echo '

                    <button
                        class="btn btn-success btnEstado"
                        data-id="'.$fila["id_producto"].'"
                        data-estado="0">

                        Activo

                    </button>';

                   }else{

                    echo '

                    <button
                        class="btn btn-danger btnEstado"
                        data-id="'.$fila["id_producto"].'"
                        data-estado="1">

                        Inactivo

                    </button>';

                  }

                    ?>

                    </td>

                    <td>

                    <button

                    class="btn btn-warning btnEditar"

                    data-id="<?php echo $fila["id_producto"]; ?>"

                    data-categoria="<?php echo $fila["id_categoria"]; ?>"

                    data-nombre="<?php echo $fila["nombre"]; ?>"

                    data-descripcion="<?php echo $fila["descripcion"]; ?>"

                    data-precio="<?php echo $fila["precio"]; ?>"

                    data-stock="<?php echo $fila["stock"]; ?>"

                    data-imagen="<?php echo $fila["imagen"]; ?>">

                    <i class="fas fa-edit"></i>

                    </button>

                    <button
                    class="btn btn-danger btnEliminar"
                    data-id="<?php echo $fila["id_producto"]; ?>">

                    <i class="fas fa-trash"></i>

                    </button>

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

include("../MODALES/modal_nueva_plataforma.php");
include("../MODALES/modal_editar_plataforma.php");

include("includes/footer.php");
include("includes/scripts.php");

?>

<script>
$(document).ready(function(){
    $("#tablaPlataformas").DataTable({
        destroy: true, // Esto destruye cualquier instancia previa y evita el error
        responsive: true,
        autoWidth: false,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
        }
    });

    // Manejo del botón Eliminar
    $(document).on("click", ".btnEliminar", function(){
        let id = $(this).attr("data-id");
        if(confirm("¿Estás seguro de eliminar esta plataforma?")){
            window.location.href = "plataformas.php?accion=eliminar&id=" + id;
        }
    });
});
</script>

<script>
$(document).ready(function(){
    $("#tablaPlataformas").DataTable({
        destroy: true,
        responsive: true,
        autoWidth: false,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
        }
    });

    $(document).on("click", ".btnEditar", function(){
        let id = $(this).attr("data-id");
        let categoria = $(this).attr("data-categoria");
        let nombre = $(this).attr("data-nombre");
        let descripcion = $(this).attr("data-descripcion");
        let precio = $(this).attr("data-precio");
        let stock = $(this).attr("data-stock");
        let imagen = $(this).attr("data-imagen");

        $("#editarId").val(id);
        $("#editarCategoria").val(categoria);
        $("#editarNombre").val(nombre);
        $("#editarDescripcion").val(descripcion);
        $("#editarPrecio").val(precio);
        $("#editarStock").val(stock);
        $("#imagenActual").val(imagen);
        
        if(imagen && imagen !== ""){
            $("#previewEditar").attr("src", "../IMG/productos/" + imagen);
        } else {
            $("#previewEditar").attr("src", "../IMG/productos/sin-imagen.png");
        }

        $("#modalEditar").modal("show");
    });

    $(document).on("click", ".btnEliminar", function(){
        let id = $(this).attr("data-id");
        if(confirm("¿Estás seguro de eliminar esta plataforma?")){
            window.location.href = "../AJAX/plataforma.ajax.php?accion=eliminar&id=" + id;
        }
    });

    $(document).on("click", ".btnEstado", function(){
        let id = $(this).attr("data-id");
        let estado = $(this).attr("data-estado");
        window.location.href = "../AJAX/plataforma.ajax.php?accion=estado&id=" + id + "&estado=" + estado;
    });
});
</script>