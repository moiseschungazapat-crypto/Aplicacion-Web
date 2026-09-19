<?php
session_start();
require_once "../config/conexion.php";

if(!isset($_SESSION["reset_email"]) || !isset($_SESSION["google_verified"]) || $_SESSION["google_verified"] !== true){
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_pass'])){
    $nueva_pass = $_POST['nueva_pass'];
    $confirmar_pass = $_POST['confirmar_pass'] ?? '';
    $email = $_SESSION["reset_email"];

    if($nueva_pass !== $confirmar_pass){
        header("Location: login.php?error=coincidencia");
        exit();
    }

    $password_hash = password_hash($nueva_pass, PASSWORD_BCRYPT);

    try {
        $db = Conexion::conectar();
        $stmt = $db->prepare("UPDATE usuarios SET password = :password WHERE correo = :correo");
        $stmt->execute([
            ':password' => $password_hash,
            ':correo'   => $email
        ]);

        if($stmt->rowCount() > 0){
            unset($_SESSION["reset_email"]);
            unset($_SESSION["google_verified"]);
            header("Location: login.php?success=1");
            exit();
        } else {
            header("Location: login.php?error=db");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: login.php?error=db");
        exit();
    }
}

header("Location: login.php");
exit();