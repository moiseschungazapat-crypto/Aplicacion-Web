<?php

include("../CONTROLADOR/PlataformaControlador.php");

$controlador = new PlataformaControlador();

$id = $_POST["id"];
$estado = $_POST["estado"];

$resultado = $controlador->CambiarEstado($id, $estado);

echo $resultado ? "ok" : "error";