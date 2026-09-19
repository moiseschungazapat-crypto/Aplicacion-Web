<?php

require_once(__DIR__ . "/../CONFIG/conexion.php");

class ClienteModelo {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function obtenerClientes() {
        $sql = "SELECT id_cliente, nombre, telefono, correo, plataforma, estado, fecha_vencimiento FROM clientes ORDER BY id_cliente DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function totalActivos() {
        $sql = "SELECT COUNT(*) AS total FROM clientes WHERE fecha_vencimiento > CURRENT_DATE + INTERVAL '3 days'";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();
        return $res ? $res : ['total' => 0];
    }

    public function totalPorVencer() {
        $sql = "SELECT COUNT(*) AS total FROM clientes WHERE fecha_vencimiento BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL '3 days'";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();
        return $res ? $res : ['total' => 0];
    }

    public function totalVencidos() {
        $sql = "SELECT COUNT(*) AS total FROM clientes WHERE fecha_vencimiento < CURRENT_DATE";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();
        return $res ? $res : ['total' => 0];
    }

    public function registrarCliente($nombre, $telefono, $correo, $plataforma, $fecha_vencimiento) {
        $sql = "INSERT INTO clientes (nombre, telefono, correo, plataforma, fecha_vencimiento, estado) VALUES (?, ?, ?, ?, ?, TRUE)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $telefono, $correo, $plataforma, $fecha_vencimiento]);
    }

    public function actualizarCliente($id, $nombre, $telefono, $correo, $plataforma, $fecha_vencimiento) {
        $sql = "UPDATE clientes SET nombre = ?, telefono = ?, correo = ?, plataforma = ?, fecha_vencimiento = ? WHERE id_cliente = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $telefono, $correo, $plataforma, $fecha_vencimiento, $id]);
    }

    public function eliminarCliente($id) {
        $sql = "DELETE FROM clientes WHERE id_cliente = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}

?>
