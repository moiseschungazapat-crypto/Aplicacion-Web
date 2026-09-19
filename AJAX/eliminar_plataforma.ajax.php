<?php

include("../CONTROLADOR/PlataformaControlador.php");

$controlador = new PlataformaControlador();

/*=====================================
RECIBIR DATOS
======================================*/

$id = $_GET["id"];
$imagen = $_GET["imagen"];

/*=====================================
ELIMINAR IMAGEN
======================================*/

if($imagen != "sin-imagen.png"){

    $ruta = "../IMG/productos/".$imagen;

    if(file_exists($ruta)){

        unlink($ruta);

    }

}

/*=====================================
ELIMINAR REGISTRO
======================================*/

$resultado = $controlador->Eliminar($id);

if($resultado){

    echo "<script>

    alert('Plataforma eliminada correctamente');

    window.location='../PANEL/plataformas.php';

    </script>";

}else{

    echo "<script>

    alert('Ocurrió un error.');

    history.back();

    </script>";

}

?>