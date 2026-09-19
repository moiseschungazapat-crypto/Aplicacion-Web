<?php

include_once(__DIR__ . "/../MODELO/ProductoModelo.php");

class ProductoControlador{

    private $modelo;

    public function __construct(){

        $this->modelo = new ProductoModelo();

    }

    public function MostrarProductos(){

        return $this->modelo->MostrarProductos();

    }

}

?>