<?php

include_once(__DIR__."/../MODELO/PlataformaModelo.php");

class PlataformaControlador{

    private $modelo;

    public function __construct(){

        $this->modelo = new PlataformaModelo();

    }

    /*=====================================
    MOSTRAR
    =====================================*/

    public function Mostrar(){

        return $this->modelo->Mostrar();

    }

    /*=====================================
    REGISTRAR
    =====================================*/

    public function Registrar(

        $categoria,
        $nombre,
        $descripcion,
        $precio,
        $imagen,
        $stock

    ){

        return $this->modelo->Registrar(

            $categoria,
            $nombre,
            $descripcion,
            $precio,
            $imagen,
            $stock

        );

    }

    /*=====================================
    EDITAR
    =====================================*/

    public function Editar(

        $id,
        $categoria,
        $nombre,
        $descripcion,
        $precio,
        $imagen,
        $stock

    ){

        return $this->modelo->Editar(

            $id,
            $categoria,
            $nombre,
            $descripcion,
            $precio,
            $imagen,
            $stock

        );

    }

/*=====================================
ELIMINAR
======================================*/

public function Eliminar($id){

    return $this->modelo->Eliminar($id);

}

/*=====================================
CAMBIAR ESTADO
======================================*/

public function CambiarEstado($id, $estado){

    return $this->modelo->CambiarEstado(

        $id,
        $estado

    );

}

/*=====================================
MOSTRAR ACTIVOS
======================================*/

public function MostrarActivos(){

    return $this->modelo->MostrarActivos();

}
    

}
