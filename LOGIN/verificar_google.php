<?php
session_start();

if(!isset($_SESSION["reset_email"])){
    header("Location: olvide_password.php");
    exit();
}

if(isset($_POST['google_verified']) && $_POST['google_verified'] === '1'){
    $_SESSION['google_verified'] = true;
    header("Location: nueva_password.php");
    exit();
}

$email_esperado = $_SESSION["reset_email"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verificación Google - EvyStream</title>
<script src="https://accounts.google.com/gsi/client" async defer></script>
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
margin-bottom: 18px;
line-height: 1.5;
}
.email-box {
background: rgba(15, 15, 23, 0.7);
border: 1px solid rgba(168, 85, 247, 0.3);
border-radius: 12px;
padding: 12px 16px;
margin-bottom: 25px;
display: flex;
align-items: center;
justify-content: center;
gap: 10px;
word-break: break-all;
}
.email-box i {
color: #a855f7;
font-size: 16px;
}
.email-text {
font-size: 14px;
font-weight: 600;
color: #ffffff;
}
.google-btn-wrapper {
display: flex;
justify-content: center;
margin-bottom: 25px;
min-height: 50px;
}
.cancel-link {
text-align: center;
}
.cancel-link a {
color: #94a3b8;
font-size: 13px;
text-decoration: none;
display: inline-flex;
align-items: center;
gap: 8px;
transition: color 0.3s ease;
}
.cancel-link a:hover {
color: #ff4b5c;
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
<i class="fa-brands fa-google"></i>
</div>

<h1 class="title">Verificación con Google</h1>
<p class="subtitle">Inicia sesión con la cuenta de Google correspondiente a:</p>

<div class="email-box">
<i class="fa-regular fa-envelope"></i>
<span class="email-text"><?php echo htmlspecialchars($email_esperado); ?></span>
</div>

<div class="google-btn-wrapper">
<div id="g_id_onload"
     data-client_id="216556758699-uk8jkv0li20rup401e6km13fo8mpu3km.apps.googleusercontent.com"
     data-callback="handleCredentialResponse"
     data-auto_prompt="false">
</div>
<div class="g_id_signin"
     data-type="standard"
     data-size="large"
     data-theme="outline"
     data-text="sign_in_with"
     data-shape="rectangular"
     data-logo_alignment="left">
</div>
</div>

<form id="verify_form" action="verificar_google.php" method="POST" style="display:none;">
    <input type="hidden" name="google_verified" value="1">
</form>

<div class="cancel-link">
<a href="olvide_password.php">
<i class="fa-solid fa-xmark"></i>
<span>Cancelar proceso</span>
</a>
</div>
</div>
</div>

<script>
function handleCredentialResponse(response) {
    const credentialToken = response.credential;
    const payload = JSON.parse(atob(credentialToken.split('.')[1]));
    
    if(payload.email === "<?php echo $email_esperado; ?>"){
        document.getElementById('verify_form').submit();
    } else {
        alert("El correo de Google no coincide con el correo esperado.");
    }
}
</script>

</body>
</html>