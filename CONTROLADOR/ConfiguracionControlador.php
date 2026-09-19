<?php

include_once(__DIR__ . "/../MODELO/ConfiguracionModelo.php");

class ConfiguracionControlador{

    private $modelo;

    public function __construct(){

        $this->modelo = new ConfiguracionModelo();

    }

    public function ObtenerConfiguracion(){

        return $this->modelo->ObtenerConfiguracion();

    }

    public function ActualizarConfiguracion($nombre, $telefono, $correo, $direccion, $logo){

        return $this->modelo->ActualizarConfiguracion($nombre, $telefono, $correo, $direccion, $logo);

    }

    public function ObtenerUsuarioPorId($idUsuario){

        return $this->modelo->ObtenerUsuarioPorId($idUsuario);

    }

    public function ActualizarPerfil($idUsuario, $nombre, $foto){

        return $this->modelo->ActualizarPerfil($idUsuario, $nombre, $foto);

    }

    public function CambiarPassword($idUsuario, $passActual, $passNueva, $passConfirmar){

        if($passNueva !== $passConfirmar){

            return array("status" => "error", "mensaje" => "Las contraseñas nuevas no coinciden.");

        }

        $usuario = $this->modelo->ObtenerUsuarioPorId($idUsuario);

        if(!$usuario){

            return array("status" => "error", "mensaje" => "Usuario no encontrado.");

        }

        if($usuario["password"] !== $passActual && !password_verify($passActual, $usuario["password"])){

            return array("status" => "error", "mensaje" => "La contraseña actual es incorrecta.");

        }

        $nuevaHash = password_hash($passNueva, PASSWORD_DEFAULT);

        $resultado = $this->modelo->ActualizarPassword($idUsuario, $nuevaHash);

        if($resultado){

            return array("status" => "success", "mensaje" => "Contraseña actualizada correctamente.");

        }else{

            return array("status" => "error", "mensaje" => "Error al actualizar la contraseña.");

        }

    }

}