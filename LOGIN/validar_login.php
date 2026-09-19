<?php

session_start();

include_once("../CONTROLADOR/UsuarioControlador.php");

if(!isset($_SESSION["intentos_fallidos"])){
    $_SESSION["intentos_fallidos"] = 0;
}

if(!isset($_SESSION["bloqueado_hasta"])){
    $_SESSION["bloqueado_hasta"] = 0;
}

if(time() < $_SESSION["bloqueado_hasta"]){
    $_SESSION["error_login"] = "Tu cuenta se encuentra temporalmente bloqueada.";
    header("Location: login.php");
    exit();
}

$correo = $_POST["correo"] ?? "";
$password = $_POST["password"] ?? "";

$controlador = new UsuarioControlador();
$usuario = $controlador->ValidarLogin($correo, $password);

if($usuario){
    $_SESSION["id_usuario"] = $usuario["id_usuario"];
    $_SESSION["nombre"] = $usuario["nombre"];
    $_SESSION["intentos_fallidos"] = 0;
    $_SESSION["bloqueado_hasta"] = 0;

    header("Location: ../PANEL/dashboard.php");
    exit();
}else{
    $_SESSION["intentos_fallidos"]++;
    $intentos = $_SESSION["intentos_fallidos"];

    if($intentos >= 3){
        if($intentos == 3){
            $tiempoBloqueo = 60;
        }elseif($intentos == 4){
            $tiempoBloqueo = 180;
        }elseif($intentos == 5){
            $tiempoBloqueo = 300;
        }else{
            $tiempoBloqueo = 600;
        }

        $_SESSION["bloqueado_hasta"] = time() + $tiempoBloqueo;
        $_SESSION["error_login"] = "Demasiados intentos fallidos. Intenta en <b id='contador'>" . $tiempoBloqueo . "</b> segundos.";
    }else{
        $restantes = 3 - $intentos;
        $_SESSION["error_login"] = "Credenciales incorrectas. Te quedan " . $restantes . " intento(s).";
    }

    header("Location: login.php");
    exit();
}

?>