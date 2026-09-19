<?php

require_once(__DIR__ . "/../config/conexion.php");

class UsuarioModelo {

    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    public function ValidarLogin($correo, $password) {
        $sql = "SELECT * FROM usuarios WHERE correo = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            if (password_verify($password, $usuario["password"]) || $password === $usuario["password"]) {
                return $usuario;
            }
        }

        return false;
    }

}