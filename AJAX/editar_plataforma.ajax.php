<?php

include("../CONTROLADOR/PlataformaControlador.php");

$controlador = new PlataformaControlador();

/*=====================================
RECIBIR DATOS
======================================*/

$id            = $_POST["id_producto"];
$categoria     = $_POST["categoria"];
$nombre        = $_POST["nombre"];
$descripcion   = $_POST["descripcion"];
$precio        = $_POST["precio"];
$stock         = $_POST["stock"];
$imagenActual  = $_POST["imagenActual"];

/*=====================================
SUBIR NUEVA IMAGEN
======================================*/

$nombreImagen = $imagenActual;

if(isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0){

    $extension = strtolower(pathinfo(
        $_FILES["imagen"]["name"],
        PATHINFO_EXTENSION
    ));

    $nombreImagen = uniqid().".".$extension;

    $rutaDestino = __DIR__ . "/../IMG/productos/" . $nombreImagen;

    if(move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)){

        // Eliminar imagen anterior
        if(
            $imagenActual != "sin-imagen.png" &&
            file_exists(__DIR__."/../IMG/productos/".$imagenActual)
        ){

            unlink(__DIR__."/../IMG/productos/".$imagenActual);

        }

    }else{

        die("Error al subir la nueva imagen.");

    }

}

/*=====================================
ACTUALIZAR EN LA BASE DE DATOS
======================================*/

$resultado = $controlador->Editar(

    $id,
    $categoria,
    $nombre,
    $descripcion,
    $precio,
    $nombreImagen,
    $stock

);

/*=====================================
RESPUESTA
======================================*/

if($resultado){

    echo "<script>

        alert('Plataforma actualizada correctamente');

        window.location='../PANEL/plataformas.php';

    </script>";

}else{

    echo "<script>

        alert('Error al actualizar la plataforma.');

        history.back();

    </script>";

}

?>