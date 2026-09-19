<?php

require_once(__DIR__ . "/../CONFIG/conexion.php");

class ConfiguracionModelo {

    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function ObtenerConfiguracion() {
        $sql = "SELECT * FROM configuracion LIMIT 1";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }

    public function ActualizarConfiguracion($nombre, $telefono, $correo, $direccion, $logo) {
        $sql = "UPDATE configuracion SET nombre_negocio = ?, telefono = ?, correo = ?, direccion = ?, logo = ? WHERE id_config = 1";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $telefono, $correo, $direccion, $logo]);
    }

    public function ObtenerUsuarioPorId($idUsuario) {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUsuario]);
        return $stmt->fetch();
    }

    public function ActualizarPerfil($idUsuario, $nombre, $foto) {
        $sql = "UPDATE usuarios SET nombre = ?, foto = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $foto, $idUsuario]);
    }

    public function ActualizarPassword($idUsuario, $nuevaPassword) {
        $sql = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nuevaPassword, $idUsuario]);
    }

}

?>
