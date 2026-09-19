<?php

require_once(__DIR__ . "/../config/conexion.php");

class PlataformaModelo {

    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    public function Mostrar() {
        $sql = "SELECT p.id_producto, p.id_categoria, c.nombre AS categoria, p.nombre, p.descripcion, p.precio, p.imagen, p.stock, p.estado FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id_categoria ORDER BY p.id_producto ASC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    public function Registrar($categoria, $nombre, $descripcion, $precio, $imagen, $stock) {
        $sql = "INSERT INTO productos (id_categoria, nombre, descripcion, precio, imagen, stock, estado) VALUES (?, ?, ?, ?, ?, ?, TRUE)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$categoria, $nombre, $descripcion, $precio, $imagen, $stock]);
    }

    public function Editar($id, $categoria, $nombre, $descripcion, $precio, $imagen, $stock) {
        $sql = "UPDATE productos SET id_categoria = ?, nombre = ?, descripcion = ?, precio = ?, imagen = ?, stock = ? WHERE id_producto = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$categoria, $nombre, $descripcion, $precio, $imagen, $stock, $id]);
    }

    public function Eliminar($id) {
        try {
            $this->conexion->beginTransaction();

            $sqlDetalle = "DELETE FROM detalle_ventas WHERE id_producto = ?";
            $stmtDetalle = $this->conexion->prepare($sqlDetalle);
            $stmtDetalle->execute([$id]);

            $sqlProducto = "DELETE FROM productos WHERE id_producto = ?";
            $stmtProducto = $this->conexion->prepare($sqlProducto);
            $stmtProducto->execute([$id]);

            $this->conexion->commit();
            return true;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            return false;
        }
    }

    public function CambiarEstado($id, $estado) {
        $sql = "UPDATE productos SET estado = ? WHERE id_producto = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }

    public function MostrarActivos() {
        $sql = "SELECT id_producto, nombre, precio, stock FROM productos WHERE estado = TRUE ORDER BY nombre ASC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

}

?>
