<?php

require_once(__DIR__ . "/../MODELO/DashboardModelo.php");

class DashboardControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new DashboardModelo();
    }

    public function TotalProductos() {
        return $this->modelo->TotalProductos();
    }

    public function TotalUsuarios() {
        return $this->modelo->TotalUsuarios();
    }

    public function TotalClientes() {
        return $this->modelo->TotalClientes();
    }

    public function TotalVentas() {
        return $this->modelo->TotalVentas();
    }

    public function TotalIngresos() {
        return $this->modelo->TotalIngresos();
    }

    public function StockBajo() {
        return $this->modelo->StockBajo();
    }

    public function VentasAnuladas() {
        return $this->modelo->VentasAnuladas();
    }

    public function UltimasVentas() {
        return $this->modelo->UltimasVentas();
    }

    public function VentasPorMes() {
        return $this->modelo->VentasPorMes();
    }

    public function TopPlataformas() {
        return $this->modelo->TopPlataformas();
    }

}

?>
