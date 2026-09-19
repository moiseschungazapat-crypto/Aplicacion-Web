<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION["id_usuario"])){
    header("Location: ../LOGIN/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>
<?php echo isset($titulo) ? $titulo : "Panel Administrativo"; ?> | EvyStream
</title>

<link rel="stylesheet" href="../ADMINLTE/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../ADMINLTE/dist/css/adminlte.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">