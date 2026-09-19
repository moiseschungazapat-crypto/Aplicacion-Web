<?php
session_start();

if(!isset($_SESSION["reset_email"]) || !isset($_SESSION["google_verified"]) || $_SESSION["google_verified"] !== true){
    header("Location: olvide_password.php");
    exit();
}

$email_esperado = $_SESSION["reset_email"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nueva Contraseña - EvyStream</title>
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
max-width: 460px;
padding: 20px;
}
.card-verify {
background: rgba(22, 22, 32, 0.85);
backdrop-filter: blur(20px);
-webkit-backdrop-filter: blur(20px);
border: 1px solid rgba(255, 255, 255, 0.08);
border-radius: 20px;
padding: 40px 35px;
box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
text-align: center;
transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card-verify:hover {
border-color: rgba(168, 85, 247, 0.3);
box-shadow: 0 25px 60px rgba(168, 85, 247, 0.15);
}
.logo-badge {
width: 60px;
height: 60px;
background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
border-radius: 16px;
display: inline-flex;
align-items: center;
justify-content: center;
margin-bottom: 16px;
box-shadow: 0 10px 25px rgba(168, 85, 247, 0.4);
}
.logo-badge i {
font-size: 28px;
color: #ffffff;
}
.title {
font-size: 24px;
font-weight: 700;
letter-spacing: -0.5px;
background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
margin-bottom: 8px;
}
.subtitle {
font-size: 13px;
color: #94a3b8;
margin-bottom: 25px;
line-height: 1.5;
}
.input-group {
margin-bottom: 18px;
text-align: left;
}
.input-group label {
display: block;
font-size: 12px;
color: #cbd5e1;
margin-bottom: 6px;
}
.input-field {
width: 100%;
background: rgba(15, 15, 23, 0.7);
border: 1px solid rgba(255, 255, 255, 0.1);
border-radius: 12px;
padding: 12px 16px;
color: #ffffff;
font-size: 14px;
outline: none;
transition: border-color 0.3s ease;
}
.input-field:focus {
border-color: #a855f7;
}
.btn-submit {
width: 100%;
padding: 12px;
background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
border: none;
border-radius: 12px;
color: #ffffff;
font-weight: 600;
font-size: 14px;
cursor: pointer;
transition: opacity 0.3s ease;
margin-top: 10px;
}
.btn-submit:hover {
opacity: 0.9;
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
<div class="card-verify">
<div class="logo-badge">
<i class="fa-solid fa-lock"></i>
</div>

<h1 class="title">Restablecer Contraseña</h1>
<p class="subtitle">Ingresa tu nueva contraseña para la cuenta: <br><strong><?php echo htmlspecialchars($email_esperado); ?></strong></p>

<form action="procesar_verificacion.php" method="POST">
<div class="input-group">
<label for="nueva_pass">Nueva Contraseña</label>
<input type="password" id="nueva_pass" name="nueva_pass" class="input-field" required placeholder="••••••••">
</div>

<div class="input-group">
<label for="confirmar_pass">Confirmar Contraseña</label>
<input type="password" id="confirmar_pass" name="confirmar_pass" class="input-field" required placeholder="••••••••">
</div>

<button type="submit" class="btn-submit">Guardar Nueva Contraseña</button>
</form>
</div>
</div>

</body>
</html>