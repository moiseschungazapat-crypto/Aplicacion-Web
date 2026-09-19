<?php

include_once(__DIR__."/../CONTROLADOR/VentaControlador.php");

$controlador = new VentaControlador();

$idVenta = $_REQUEST["id"] ?? $_REQUEST["idVenta"] ?? 0;

$resultado = $controlador->ActivarVenta($idVenta);

if(isset($_GET["id"])){
    if($resultado){
        echo "<script>alert('Venta activada correctamente'); window.location='../PANEL/ventas.php';</script>";
    }else{
        echo "<script>alert('Error al activar la venta'); history.back();</script>";
    }
}else{
    echo $resultado ? "ok" : "error";
}

?>