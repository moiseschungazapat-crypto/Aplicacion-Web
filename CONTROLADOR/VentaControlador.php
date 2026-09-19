<?php

include_once(__DIR__."/../MODELO/VentaModelo.php");

class VentaControlador{

    private $modelo;

    public function __construct(){
        $this->modelo = new VentaModelo();
    }

    public function RegistrarVenta($cliente, $total){
        return $this->modelo->RegistrarVenta($cliente, $total);
    }

    public function RegistrarDetalle($idVenta, $idProducto, $cantidad, $precio){
        return $this->modelo->RegistrarDetalle($idVenta, $idProducto, $cantidad, $precio);
    }

    public function DescontarStock($idProducto, $cantidad){
        return $this->modelo->DescontarStock($idProducto, $cantidad);
    }

    public function MostrarVentas(){
        return $this->modelo->MostrarVentas();
    }

    public function MostrarDetalle($idVenta){
        return $this->modelo->MostrarDetalle($idVenta);
    }

    public function MostrarVenta($idVenta){
        return $this->modelo->MostrarVenta($idVenta);
    }

    public function AnularVenta($idVenta){
        return $this->modelo->AnularVenta($idVenta);
    }

    public function ActivarVenta($idVenta){
        return $this->modelo->ActivarVenta($idVenta);
    }

    public function ObtenerDetalleVenta($idVenta){
        return $this->modelo->ObtenerDetalleVenta($idVenta);
    }

    public function DevolverStock($idProducto, $cantidad){
        return $this->modelo->DevolverStock($idProducto, $cantidad);
    }

}
?>