<?php

require_once(__DIR__ . "/../config/conexion.php");

class DashboardModelo {

    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    public function TotalProductos() {
        $sql = "SELECT COUNT(*) AS total FROM productos WHERE estado = TRUE";
        $stmt = $this->conexion->query($sql);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res : ['total' => 0];
    }

    public function TotalUsuarios() {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE estado = TRUE";
        $stmt = $this->conexion->query($sql);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res : ['total' => 0];
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

    public function StockBajo() {
        $sql = "SELECT COUNT(*) AS total FROM productos WHERE stock <= 5 AND estado = TRUE";
        $stmt = $this->conexion->query($sql);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res : ['total' => 0];
    }

    public function VentasAnuladas() {
        $sql = "SELECT COUNT(*) AS total FROM ventas WHERE estado = FALSE";
        $stmt = $this->conexion->query($sql);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res : ['total' => 0];
    }

    public function UltimasVentas() {
        $sql = "SELECT id_venta, cliente, fecha, total, estado FROM ventas ORDER BY id_venta DESC LIMIT 5";
        $stmt = $this->conexion->query($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function VentasPorMes() {
        $sql = "SELECT EXTRACT(MONTH FROM fecha)::int AS mes, SUM(total) AS total FROM ventas WHERE estado = TRUE GROUP BY EXTRACT(MONTH FROM fecha) ORDER BY mes";
        $stmt = $this->conexion->query($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function TopPlataformas() {
        try {
            $sql = "SELECT p.nombre, COALESCE(SUM(d.cantidad), COUNT(d.id_producto)) AS vendidos FROM detalle_ventas d INNER JOIN productos p ON d.id_producto = p.id_producto INNER JOIN ventas v ON d.id_venta = v.id_venta WHERE v.estado = TRUE GROUP BY p.id_producto, p.nombre ORDER BY vendidos DESC LIMIT 5";
            $stmt = $this->conexion->query($sql);
            $res = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            if(!empty($res)) return $res;
        } catch (PDOException $e) {}

        try {
            $sqlFallback = "SELECT p.nombre, COUNT(d.id_producto) AS vendidos FROM detalle_ventas d INNER JOIN productos p ON d.id_producto = p.id_producto GROUP BY p.id_producto, p.nombre ORDER BY vendidos DESC LIMIT 5";
            $stmt = $this->conexion->query($sqlFallback);
            return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (PDOException $ex) {
            return [];
        }
    }

}

?>
