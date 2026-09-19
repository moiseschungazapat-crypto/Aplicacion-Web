<?php

session_start();

if(!isset($_SESSION["id_usuario"]) || !isset($_SESSION["nombre"])){
    header("Location: ../LOGIN/login.php");
    exit();
}

$menu   = "configuracion";
$titulo = "Configuración";

include("../CONTROLADOR/ConfiguracionControlador.php");

$controlador = new ConfiguracionControlador();

$mensajeError = "";
$mensajeExito = "";

if(isset($_POST["guardarConfiguracion"])){
    $configuracionActual = $controlador->ObtenerConfiguracion();
    $logo = $configuracionActual["logo"] ?? "";

    if(isset($_FILES["logo_file"]) && $_FILES["logo_file"]["error"] == 0){
        $directorioDestino = "../ARCHIVOS/";

        if(!file_exists($directorioDestino)){
            mkdir($directorioDestino, 0777, true);
        }

        $nombreOriginal = $_FILES["logo_file"]["name"];
        $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
        $nuevoNombre = "logo_" . time() . "." . $ext;
        $rutaDestino = $directorioDestino . $nuevoNombre;

        if(move_uploaded_file($_FILES["logo_file"]["tmp_name"], $rutaDestino)){
            $logo = $nuevoNombre;
        }
    }

    $controlador->ActualizarConfiguracion(
        $_POST["nombre_negocio"],
        $_POST["telefono"],
        $_POST["correo"],
        $_POST["direccion"],
        $logo
    );

    header("Location: configuracion.php?msj=negocio_actualizado");
    exit();
}

$datosUsuario = $controlador->ObtenerUsuarioPorId($_SESSION["id_usuario"]);

if(isset($_POST["guardarPerfil"])){
    $idUsuario = $_SESSION["id_usuario"];
    $nuevoNombre = trim($_POST["nombre_perfil"]);
    $foto = $datosUsuario["foto"] ?? "";

    if(isset($_FILES["foto_perfil"]) && $_FILES["foto_perfil"]["error"] == 0){
        $directorioDestino = "../ARCHIVOS/";

        if(!file_exists($directorioDestino)){
            mkdir($directorioDestino, 0777, true);
        }

        $ext = pathinfo($_FILES["foto_perfil"]["name"], PATHINFO_EXTENSION);
        $nuevoNombreFoto = "user_" . $idUsuario . "_" . time() . "." . $ext;
        $rutaDestino = $directorioDestino . $nuevoNombreFoto;

        if(move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $rutaDestino)){
            $foto = $nuevoNombreFoto;
        }
    }

    $res = $controlador->ActualizarPerfil($idUsuario, $nuevoNombre, $foto);

    if($res){
        $_SESSION["nombre"] = $nuevoNombre;
        $_SESSION["foto"] = $foto;

        header("Location: configuracion.php?msj=perfil_actualizado");
        exit();
    }else{
        $mensajeError = "Error al actualizar la información del perfil.";
    }
}

if(isset($_POST["cambiarPassword"])){
    $respuesta = $controlador->CambiarPassword(
        $_SESSION["id_usuario"],
        $_POST["pass_actual"],
        $_POST["pass_nueva"],
        $_POST["pass_confirmar"]
    );

    if($respuesta["status"] == "success"){
        $mensajeExito = $respuesta["mensaje"];
    }else{
        $mensajeError = $respuesta["mensaje"];
    }
}

$configuracion = $controlador->ObtenerConfiguracion();

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    <i class="fas fa-cog"></i>
                    Configuración
                </h1>
                <p class="text-muted">
                    Administra la configuración general del sistema.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <?php if(isset($_GET["msj"]) && $_GET["msj"] == "negocio_actualizado"){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> Configuración del negocio guardada exitosamente.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>

        <?php if(isset($_GET["msj"]) && $_GET["msj"] == "perfil_actualizado"){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> Datos de perfil actualizados correctamente.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>

        <?php if(!empty($mensajeExito)){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo $mensajeExito; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>

        <?php if(!empty($mensajeError)){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $mensajeError; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-lg-6">
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-building"></i>
                            Información del Negocio
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Nombre del Negocio</label>
                                <input type="text" name="nombre_negocio" class="form-control" value="<?php echo $configuracion["nombre_negocio"] ?? ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" class="form-control" value="<?php echo $configuracion["telefono"] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Correo</label>
                                <input type="email" name="correo" class="form-control" value="<?php echo $configuracion["correo"] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <textarea name="direccion" class="form-control" rows="3"><?php echo $configuracion["direccion"] ?? ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Logo del Sistema</label>
                                <div class="text-center mb-2">
                                    <?php
                                    $rutaLogo = "../ARCHIVOS/" . ($configuracion["logo"] ?? "");
                                    if(!empty($configuracion["logo"]) && file_exists($rutaLogo)){
                                    ?>
                                        <img id="imgPreview" src="<?php echo $rutaLogo; ?>" class="img-fluid rounded" style="max-height:120px;">
                                    <?php
                                    }else{
                                    ?>
                                        <img id="imgPreview" src="../DIST/img/logo.png" class="img-fluid rounded" style="max-height:120px;">
                                    <?php
                                    }
                                    ?>
                                </div>
                                <input type="file" name="logo_file" id="logo_file" class="form-control-file" accept="image/*">
                            </div>
                            <hr>
                            <div class="text-right">
                                <button type="submit" name="guardarConfiguracion" class="btn btn-danger">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-circle"></i>
                            Mi Perfil
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="text-center mb-3">
                                <?php
                                $rutaFoto = "../ARCHIVOS/" . ($datosUsuario["foto"] ?? "");
                                if(!empty($datosUsuario["foto"]) && file_exists($rutaFoto)){
                                ?>
                                    <img id="userPreview" src="<?php echo $rutaFoto; ?>" class="img-circle elevation-2" style="width:120px;height:120px;object-fit:cover;">
                                <?php
                                }else{
                                ?>
                                    <img id="userPreview" src="../DIST/img/user.png" class="img-circle elevation-2" style="width:120px;height:120px;object-fit:cover;">
                                <?php
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Foto de Perfil</label>
                                <input type="file" name="foto_perfil" id="foto_perfil" class="form-control-file" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="nombre_perfil" class="form-control" value="<?php echo $_SESSION["nombre"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Correo / Usuario</label>
                                <input type="text" class="form-control" value="<?php echo $datosUsuario["correo"] ?? "Administrador"; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Cargo</label>
                                <input type="text" class="form-control" value="Administrador" readonly>
                            </div>
                            <div class="text-right">
                                <button type="submit" name="guardarPerfil" class="btn btn-primary">
                                    <i class="fas fa-user-edit"></i> Actualizar Perfil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-lock"></i>
                            Seguridad
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Contraseña actual</label>
                                <input type="password" name="pass_actual" class="form-control" placeholder="************" required>
                            </div>
                            <div class="form-group">
                                <label>Nueva contraseña</label>
                                <input type="password" name="pass_nueva" class="form-control" placeholder="Ingrese la nueva contraseña" required>
                            </div>
                            <div class="form-group">
                                <label>Confirmar contraseña</label>
                                <input type="password" name="pass_confirmar" class="form-control" placeholder="Repita la contraseña" required>
                            </div>
                            <div class="text-right">
                                <button type="submit" name="cambiarPassword" class="btn btn-warning">
                                    <i class="fas fa-key"></i> Cambiar contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-server"></i>
                            Información del Sistema
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered">
                            <tr>
                                <th width="45%">Sistema</th>
                                <td>EvyStream</td>
                            </tr>
                            <tr>
                                <th>Versión</th>
                                <td>1.0</td>
                            </tr>
                            <tr>
                                <th>PHP</th>
                                <td><?php echo phpversion(); ?></td>
                            </tr>
                            <tr>
                                <th>Servidor</th>
                                <td><?php echo $_SERVER["SERVER_SOFTWARE"] ?? "Apache"; ?></td>
                            </tr>
                            <tr>
                                <th>Base de Datos</th>
                                <td>PostgreSQL</td>
                            </tr>
                            <tr>
                                <th>Usuario conectado</th>
                                <td><?php echo $_SESSION["nombre"]; ?></td>
                            </tr>
                            <tr>
                                <th>Fecha</th>
                                <td><?php echo date("d/m/Y H:i"); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include("includes/footer.php");
include("includes/scripts.php");
?>

<script>
$(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $("#logo_file").change(function(){
        const file = this.files[0];
        if (file){
            let reader = new FileReader();
            reader.onload = function(event){
                $("#imgPreview").attr("src", event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
    $("#foto_perfil").change(function(){
        const file = this.files[0];
        if (file){
            let reader = new FileReader();
            reader.onload = function(event){
                $("#userPreview").attr("src", event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>