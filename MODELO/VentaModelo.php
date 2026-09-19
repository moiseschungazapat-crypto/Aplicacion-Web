<?php

require_once(__DIR__ . "/../CONFIG/conexion.php");

class VentaModelo {

    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    public function RegistrarVenta($cliente, $total) {
        try {
            $sql = "INSERT INTO ventas (cliente, total, estado, fecha) VALUES (?, ?, TRUE, CURRENT_TIMESTAMP)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$cliente, $total]);
            return intval($this->conexion->lastInsertId());
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function RegistrarDetalle($idVenta, $idProducto, $cantidad, $precio) {
        try {
            $sql = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$idVenta, $idProducto, $cantidad, $precio]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function DescontarStock($idProducto, $cantidad) {
        try {
            $sql = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$cantidad, $idProducto]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function MostrarVentas() {
        $sql = "SELECT * FROM ventas ORDER BY id_venta DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function MostrarDetalle($idVenta) {
        $sql = "SELECT d.id_detalle, d.id_producto, COALESCE(p.nombre, 'Plataforma Eliminada') AS nombre, d.cantidad, d.precio, (d.cantidad * d.precio) AS subtotal 
                FROM detalle_ventas d 
                LEFT JOIN productos p ON d.id_producto = p.id_producto 
                WHERE d.id_venta = ? 
                ORDER BY d.id_detalle ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$idVenta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function MostrarVenta($idVenta) {
        $sql = "SELECT * FROM ventas WHERE id_venta = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$idVenta]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function AnularVenta($idVenta) {
        $this->conexion->beginTransaction();

        try {
            $stmtVenta = $this->conexion->prepare("SELECT estado FROM ventas WHERE id_venta = ?");
            $stmtVenta->execute([$idVenta]);
            $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

            if (!$venta) {
                throw new Exception("La venta no existe.");
            }

            if ($venta["estado"] == 0) {
                throw new Exception("La venta ya fue anulada.");
            }

            $detalle = $this->ObtenerDetalleVenta($idVenta);

            foreach ($detalle as $fila) {
                $this->DevolverStock($fila["id_producto"], $fila["cantidad"]);
            }

            $stmtUpdate = $this->conexion->prepare("UPDATE ventas SET estado = FALSE WHERE id_venta = ?");
            $actualizar = $stmtUpdate->execute([$idVenta]);

            if (!$actualizar) {
                throw new Exception("No se pudo actualizar la venta.");
            }

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            return false;
        }
    }

    public function ActivarVenta($idVenta) {
        $this->conexion->beginTransaction();

        try {
            $stmtVenta = $this->conexion->prepare("SELECT estado FROM ventas WHERE id_venta = ?");
            $stmtVenta->execute([$idVenta]);
            $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

            if (!$venta) {
                throw new Exception("La venta no existe.");
            }

            if ($venta["estado"] == 1) {
                throw new Exception("La venta ya se encuentra activa.");
            }

            $detalle = $this->ObtenerDetalleVenta($idVenta);

            foreach ($detalle as $fila) {
                $this->DescontarStock($fila["id_producto"], $fila["cantidad"]);
            }

            $stmtUpdate = $this->conexion->prepare("UPDATE ventas SET estado = TRUE WHERE id_venta = ?");
            $actualizar = $stmtUpdate->execute([$idVenta]);

            if (!$actualizar) {
                throw new Exception("No se pudo activar la venta.");
            }

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            return false;
        }
    }

    public function ObtenerDetalleVenta($idVenta) {
        $sql = "SELECT id_producto, cantidad FROM detalle_ventas WHERE id_venta = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$idVenta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function DevolverStock($idProducto, $cantidad) {
        $sql = "UPDATE productos SET stock = stock + ? WHERE id_producto = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$cantidad, $idProducto]);
    }

    public function TotalVentas() {
        $sql = "SELECT COUNT(*) AS total FROM ventas WHERE estado = TRUE";
        $stmt = $this->conexion->query($sql);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res : ['total' => 0];
    }

    public function TotalIngresos() {
        $sql = "SELECT COALESCE(SUM(total), 0) AS total FROM ventas WHERE estado = TRUE";
        $stmt = $this->conexion->query($sql);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res : ['total' => 0];
    }

    public function TotalPlataformas() {
        $sql = "SELECT COUNT(*) AS total FROM productos";
        $stmt = $this->conexion->query($sql);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res : ['total' => 0];
    }

    public function StockBajo() {
        $sql = "SELECT COUNT(*) AS total FROM productos WHERE stock <= 5";
        $stmt = $this->conexion->query($sql);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res : ['total' => 0];
    }
}
?>
