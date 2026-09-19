<?php
require_once(__DIR__ . "/../CONFIG/conexion.php");

if (
    isset($_POST["cliente"]) && 
    isset($_POST["total"]) && 
    isset($_POST["productos"]) && 
    is_array($_POST["productos"])
) {
    $conexion = Conexion::conectar();

    try {
        $conexion->beginTransaction();

        $cliente = trim($_POST["cliente"]);
        $total = floatval($_POST["total"]);
        $fecha = date("Y-m-d H:i:s");

        $sqlVenta = "INSERT INTO ventas (cliente, fecha, total) VALUES (?, ?, ?)";
        $stmtVenta = $conexion->prepare($sqlVenta);
        $stmtVenta->execute([$cliente, $fecha, $total]);
        $idVenta = $conexion->lastInsertId();

        $sqlDetalle = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmtDetalle = $conexion->prepare($sqlDetalle);

        $sqlStock = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
        $stmtStock = $conexion->prepare($sqlStock);

        $productos = $_POST["productos"];
        $cantidades = $_POST["cantidades"];
        $precios = $_POST["precios"];

        for ($i = 0; $i < count($productos); $i++) {
            $idProducto = intval($productos[$i]);
            $cantidad = intval($cantidades[$i]);
            $precio = floatval($precios[$i]);
            $stmtDetalle->execute([$idVenta, $idProducto, $cantidad, $precio]);
            $stmtStock->execute([$cantidad, $idProducto]);
        }

        $conexion->commit();
        header("Location: ../PANEL/ventas.php?status=success");
        exit();

    } catch (Exception $e) {
        $conexion->rollBack();
        header("Location: ../PANEL/ventas.php?status=error");
        exit();
    }
} else {
    header("Location: ../PANEL/ventas.php?status=invalid");
    exit();
}
?>
