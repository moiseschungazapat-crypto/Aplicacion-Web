<?php

require_once(__DIR__ . "/../config/conexion.php");

class ProductoModelo {

    private $conexion;

    public function __construct(){
        $this->conexion = Conexion::conectar();
    }

    public function MostrarProductos(){
        $sql = "SELECT * 
                FROM productos 
                WHERE estado = TRUE 
                ORDER BY id_producto ASC";

        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

}

?>
