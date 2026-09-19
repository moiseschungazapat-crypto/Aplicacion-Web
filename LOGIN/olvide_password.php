<?php

session_start();

include_once(__DIR__ . "/../CONFIG/conexion.php");

$error = "";

$permitidos = array("evy3286@gmail.com", "moiseschungazapata@gmail.com");

if(isset($_POST["verificar_correo"])){

    $correoIngresado = strtolower(trim($_POST["correo"]));

    if(!in_array($correoIngresado, $permitidos)){

        $error = "El correo no está autorizado para la recuperación de administrador.";

    }else{

        global $Conectar, $conexion;
        $db = $Conectar ?? $conexion ?? null;

        if(!$db && class_exists('Conexion') && method_exists('Conexion', 'Conectar')){
            $db = Conexion::Conectar();
        }

        if($db){

            try {

                $stmt = $db->prepare("SELECT id_usuario, correo FROM usuarios WHERE LOWER(correo) = :correo LIMIT 1");
                $stmt->execute([':correo' => $correoIngresado]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if($row){

                    $_SESSION["reset_email"] = $correoIngresado;
                    $_SESSION["reset_id"] = $row["id_usuario"];

                    header("Location: verificar_google.php");
                    exit();

                }else{

                    $stmtFallback = $db->prepare("SELECT id_usuario FROM usuarios ORDER BY id_usuario ASC LIMIT 1");
                    $stmtFallback->execute();
                    $rowFallback = $stmtFallback->fetch(PDO::FETCH_ASSOC);

                    if($rowFallback){

                        $_SESSION["reset_email"] = $correoIngresado;
                        $_SESSION["reset_id"] = $rowFallback["id_usuario"];

                        header("Location: verificar_google.php");
                        exit();

                    }else{

                        $error = "No se encontró ningún usuario registrado en el sistema.";

                    }

                }

            } catch (PDOException $e) {
                $error = "Error en la consulta a la base de datos.";
            }

        }else{

            $error = "Error de conexión a la base de datos.";

        }

    }

}

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Recuperar Contraseña - EvyStream</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

* {

margin: 0;

padding: 0;

box-sizing: border-box;

font-family: 'Poppins', sans-serif;

}

body {

background: #0b0b10;

min-height: 100vh;

display: flex;

align-items: center;

justify-content: center;

position: relative;

overflow: hidden;

color: #ffffff;

}

.bg-decorations {

position: absolute;

width: 100%;

height: 100%;

top: 0;

left: 0;

z-index: 1;

pointer-events: none;

overflow: hidden;

}

.brand-watermark {

position: absolute;

font-size: 5rem;

font-weight: 800;

opacity: 0.04;

color: #ffffff;

user-select: none;

white-space: nowrap;

}

.mark-1 { top: 10%; left: 5%; transform: rotate(-10deg); }

.mark-2 { top: 20%; right: 5%; transform: rotate(15deg); }

.mark-3 { bottom: 15%; left: 10%; transform: rotate(8deg); }

.mark-4 { bottom: 10%; right: 8%; transform: rotate(-12deg); }

.glow-bg {

position: absolute;

width: 500px;

height: 500px;

background: radial-gradient(circle, rgba(168, 85, 247, 0.25) 0%, rgba(229, 9, 20, 0.15) 50%, rgba(0,0,0,0) 70%);

top: 50%;

left: 50%;

transform: translate(-50%, -50%);

z-index: 1;

filter: blur(50px);

}

.card-container {

position: relative;

z-index: 10;

width: 100%;

max-width: 440px;

padding: 20px;

}

.card-recovery {

background: rgba(22, 22, 32, 0.85);

backdrop-filter: blur(20px);

-webkit-backdrop-filter: blur(20px);

border: 1px solid rgba(255, 255, 255, 0.08);

border-radius: 20px;

padding: 40px 35px;

box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);

transition: transform 0.3s ease, box-shadow 0.3s ease;

}

.card-recovery:hover {

border-color: rgba(168, 85, 247, 0.3);

box-shadow: 0 25px 60px rgba(168, 85, 247, 0.15);

}

.logo-container {

text-align: center;

margin-bottom: 25px;

}

.logo-badge {

width: 60px;

height: 60px;

background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);

border-radius: 16px;

display: inline-flex;

align-items: center;

justify-content: center;

margin-bottom: 12px;

box-shadow: 0 10px 25px rgba(168, 85, 247, 0.4);

}

.logo-badge i {

font-size: 28px;

color: #ffffff;

}

.title {

font-size: 26px;

font-weight: 700;

letter-spacing: -0.5px;

background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);

-webkit-background-clip: text;

-webkit-text-fill-color: transparent;

margin-bottom: 4px;

}

.subtitle {

font-size: 13px;

color: #94a3b8;

font-weight: 400;

}

.alert-custom {

background: rgba(239, 68, 68, 0.12);

border: 1px solid rgba(239, 68, 68, 0.3);

color: #fca5a5;

padding: 12px 16px;

border-radius: 12px;

font-size: 13px;

margin-bottom: 20px;

display: flex;

align-items: center;

gap: 10px;

}

.form-group {

margin-bottom: 22px;

position: relative;

}

.form-group label {

display: block;

font-size: 12px;

font-weight: 500;

color: #cbd5e1;

margin-bottom: 8px;

text-transform: uppercase;

letter-spacing: 0.5px;

}

.input-wrapper {

position: relative;

display: flex;

align-items: center;

}

.input-wrapper i {

position: absolute;

left: 16px;

color: #64748b;

font-size: 16px;

transition: color 0.3s ease;

}

.form-control {

width: 100%;

height: 50px;

background: rgba(15, 15, 23, 0.7);

border: 1px solid rgba(255, 255, 255, 0.1);

border-radius: 12px;

padding: 0 16px 0 46px;

color: #ffffff;

font-size: 14px;

outline: none;

transition: all 0.3s ease;

}

.form-control:focus {

border-color: #a855f7;

background: rgba(15, 15, 23, 0.95);

box-shadow: 0 0 15px rgba(168, 85, 247, 0.25);

}

.form-control:focus + i,

.form-control:focus ~ i {

color: #a855f7;

}

.btn-submit {

width: 100%;

height: 52px;

background: linear-gradient(135deg, #ff4b5c 0%, #ff2a4b 100%);

border: none;

border-radius: 12px;

color: #ffffff;

font-size: 15px;

font-weight: 600;

cursor: pointer;

display: flex;

align-items: center;

justify-content: center;

gap: 10px;

box-shadow: 0 10px 25px rgba(255, 42, 75, 0.35);

transition: all 0.3s ease;

}

.btn-submit:hover {

transform: translateY(-2px);

box-shadow: 0 15px 30px rgba(255, 42, 75, 0.5);

background: linear-gradient(135deg, #ff5c6d 0%, #ff3b5c 100%);

}

.btn-submit:active {

transform: translateY(0);

}

.back-link {

text-align: center;

margin-top: 22px;

}

.back-link a {

color: #94a3b8;

font-size: 13px;

text-decoration: none;

display: inline-flex;

align-items: center;

gap: 8px;

transition: color 0.3s ease;

}

.back-link a:hover {

color: #a855f7;

}

</style>

</head>

<body>

<div class="bg-decorations">

<div class="brand-watermark mark-1">NETFLIX</div>

<div class="brand-watermark mark-2">Disney+</div>

<div class="brand-watermark mark-3">prime video</div>

<div class="brand-watermark mark-4">HBO max</div>

</div>

<div class="glow-bg"></div>

<div class="card-container">

<div class="card-recovery">

<div class="logo-container">

<div class="logo-badge">

<i class="fa-solid fa-play"></i>

</div>

<h1 class="title">EvyStream</h1>

<p class="subtitle">Recuperación de cuenta de Administrador</p>

</div>

<?php if(!empty($error)){ ?>

<div class="alert-custom">

<i class="fa-solid fa-circle-exclamation"></i>

<span><?php echo $error; ?></span>

</div>

<?php } ?>

<form method="POST">

<div class="form-group">

<label>Correo Electrónico</label>

<div class="input-wrapper">

<i class="fa-regular fa-envelope"></i>

<input 

type="email" 

name="correo" 

class="form-control" 

placeholder="ejemplo@evystream.com" 

required 

autocomplete="off">

</div>

</div>

<button type="submit" name="verificar_correo" class="btn-submit">

<span>Continuar Verificación</span>

<i class="fa-solid fa-arrow-right"></i>

</button>

</form>

<div class="back-link">

<a href="login.php">

<i class="fa-solid fa-arrow-left"></i>

<span>Volver al Inicio de Sesión</span>

</a>

</div>

</div>

</div>

</body>

</html>