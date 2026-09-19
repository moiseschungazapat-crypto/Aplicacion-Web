<?php
session_start();

if(!isset($_SESSION["intentos_fallidos"])){
    $_SESSION["intentos_fallidos"] = 0;
}

if(!isset($_SESSION["bloqueado_hasta"])){
    $_SESSION["bloqueado_hasta"] = 0;
}

$tiempoActual = time();
$bloqueado = false;
$segundosRestantes = 0;

if($tiempoActual < $_SESSION["bloqueado_hasta"]){
    $bloqueado = true;
    $segundosRestantes = $_SESSION["bloqueado_hasta"] - $tiempoActual;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar Sesión | EvyStream</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="../CSS/estilo_login.css">
</head>

<body>

<div class="background">
    <div class="overlay"></div>
    <div class="light red"></div>
    <div class="light purple"></div>
    <div class="particles"></div>
</div>

<div class="login-container">
<div class="login-card">

<div class="logo">
<img src="../IMG/logo.png">
<h1>EvyStream</h1>
</div>

<h2>Panel Administrativo</h2>
<p>Acceso exclusivo para administradores.</p>

<form action="validar_login.php" method="POST">

<div id="mensajeLogin" class="mensaje-login" style="<?php echo (isset($_SESSION["error_login"]) || $bloqueado) ? 'display:block; color:#ff4d4d; background:rgba(255,0,0,0.1); padding:10px; border-radius:8px; margin-bottom:15px; text-align:center;' : ''; ?>">
    <?php
    if(isset($_SESSION["error_login"])){
        echo $_SESSION["error_login"];
        unset($_SESSION["error_login"]);
    }elseif($bloqueado){
        echo "Demasiados intentos fallidos. Intenta en <b id='contador'>".$segundosRestantes."</b> segundos.";
    }
    ?>
</div>

<div class="input-group">
<i class="fa-solid fa-envelope"></i>
<input
type="email"
name="correo"
placeholder="Correo electrónico"
required
<?php echo $bloqueado ? 'disabled' : ''; ?>>
</div>

<div class="input-group">
<i class="fa-solid fa-lock"></i>
<input
type="password"
id="password"
name="password"
placeholder="Contraseña"
required
<?php echo $bloqueado ? 'disabled' : ''; ?>>
<button
type="button"
id="togglePassword">
<i class="fa-solid fa-eye"></i>
</button>
</div>

<div class="extras">
<label>
<input type="checkbox">
Recordarme
</label>
<a href="olvide_password.php">
¿Olvidaste tu contraseña?
</a>
</div>

<button
type="submit"
id="btnLogin"
class="login-btn"
<?php echo $bloqueado ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : ''; ?>>
Ingresar al Panel
</button>

</form>

<div class="back-home">
<a href="../index.php">
← Volver al Inicio
</a>
</div>

</div>
</div>

<script src="../JS/login.js"></script>

<script>
let segundos = <?php echo $segundosRestantes; ?>;

if(segundos > 0){
    let msg = document.getElementById("mensajeLogin");
    let intervalo = setInterval(function(){
        segundos--;
        let elemContador = document.getElementById("contador");
        if(elemContador){
            elemContador.innerText = segundos;
        }

        if(segundos <= 0){
            clearInterval(intervalo);
            window.location.reload();
        }
    }, 1000);
}
</script>

</body>
</html>