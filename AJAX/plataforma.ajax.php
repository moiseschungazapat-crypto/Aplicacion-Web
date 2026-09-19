<?php

include_once(__DIR__."/../CONTROLADOR/PlataformaControlador.php");

$controlador = new PlataformaControlador();

$accion = $_POST["accion"] ?? $_GET["accion"] ?? "";

if($accion === "crear"){
    $categoria   = $_POST["categoria"] ?? 1;
    $nombre      = $_POST["nombre"] ?? "";
    $descripcion = $_POST["descripcion"] ?? "";
    $precio      = $_POST["precio"] ?? 0;
    $stock       = $_POST["stock"] ?? 0;

    $nombreImagen = "sin-imagen.png";

    if(isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0){
        $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
        $nombreImagen = uniqid().".".$extension;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], "../IMG/productos/".$nombreImagen);
    }

    $resultado = $controlador->Registrar($categoria, $nombre, $descripcion, $precio, $nombreImagen, $stock);

    if($resultado){
        echo '<script>alert("Plataforma registrada correctamente"); window.location="../PANEL/plataformas.php";</script>';
    }else{
        echo '<script>alert("Error al registrar la plataforma"); history.back();</script>';
    }
    exit();
}

if($accion === "editar"){
    $id           = $_POST["id"] ?? 0;
    $categoria    = $_POST["categoria"] ?? 1;
    $nombre       = $_POST["nombre"] ?? "";
    $descripcion  = $_POST["descripcion"] ?? "";
    $precio       = $_POST["precio"] ?? 0;
    $stock        = $_POST["stock"] ?? 0;
    $imagenActual = $_POST["imagenActual"] ?? "sin-imagen.png";

    $nombreImagen = $imagenActual;

    if(isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0){
        $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
        $nombreImagen = uniqid().".".$extension;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], "../IMG/productos/".$nombreImagen);
    }

    $resultado = $controlador->Editar($id, $categoria, $nombre, $descripcion, $precio, $nombreImagen, $stock);

    if($resultado){
        echo '<script>alert("Plataforma actualizada correctamente"); window.location="../PANEL/plataformas.php";</script>';
    }else{
        echo '<script>alert("Ocurrió un error al actualizar"); history.back();</script>';
    }
    exit();
}

if($accion === "eliminar" || (isset($_GET["id"]) && $accion === "")){
    $id = $_GET["id"] ?? $_POST["id"] ?? 0;
    if($id > 0){
        $controlador->Eliminar($id);
    }
    header("Location: ../PANEL/plataformas.php");
    exit();
}

if($accion === "estado"){
    $id = $_GET["id"] ?? 0;
    $estado = $_GET["estado"] ?? 0;
    $controlador->CambiarEstado($id, $estado);
    header("Location: ../PANEL/plataformas.php");
    exit();
}

header("Location: ../PANEL/plataformas.php");
exit();
?>