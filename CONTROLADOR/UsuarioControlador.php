<?php

include_once(__DIR__."/../MODELO/UsuarioModelo.php");

class UsuarioControlador{

    private $modelo;

    public function __construct(){
        $this->modelo = new UsuarioModelo();
    }

    public function ValidarLogin($correo, $password){
        return $this->modelo->ValidarLogin($correo, $password);
    }

}