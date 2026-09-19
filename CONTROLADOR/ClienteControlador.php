<?php
include_once(__DIR__ . "/../MODELO/ClienteModelo.php");

class ClienteControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new ClienteModelo();
    }

    public function listarClientes() {
        return $this->modelo->obtenerClientes();
    }

    public function ObtenerClientes() {
        return $this->modelo->obtenerClientes();
    }

    public function TotalActivos() {
        return $this->modelo->totalActivos();
    }

    public function TotalPorVencer() {
        return $this->modelo->totalPorVencer();
    }

    public function TotalVencidos() {
        return $this->modelo->totalVencidos();
    }

    public function ObtenerMetricas() {
        $activos   = $this->TotalActivos();
        $porVencer = $this->TotalPorVencer();
        $vencidos  = $this->TotalVencidos();

        return [
            'activos'    => $activos['total'] ?? 0,
            'por_vencer' => $porVencer['total'] ?? 0,
            'vencidos'   => $vencidos['total'] ?? 0
        ];
    }

    public function RegistrarCliente($nombre, $telefono, $correo, $plataforma, $fecha_vencimiento) {
        return $this->modelo->registrarCliente($nombre, $telefono, $correo, $plataforma, $fecha_vencimiento);
    }

    public function ActualizarCliente($id, $nombre, $telefono, $correo, $plataforma, $fecha_vencimiento) {
        return $this->modelo->actualizarCliente($id, $nombre, $telefono, $correo, $plataforma, $fecha_vencimiento);
    }

    public function EliminarCliente($id) {
        return $this->modelo->eliminarCliente($id);
    }
}