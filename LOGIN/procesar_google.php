<?php
session_start();

if (isset($_POST['credential'])) {
    $id_token = $_POST['credential'];
    
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $id_token;
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['email'])) {
        $google_email = $data['email'];
        $email_esperado = $_SESSION["reset_email"] ?? '';

        if ($google_email === $email_esperado) {
            header("Location: cambiar_password.php");
            exit();
        } else {
            echo "El correo introducido no coincide con la solicitud.";
        }
    } else {
        echo "Token de Google inválido.";
    }
} else {
    header("Location: olvide_password.php");
    exit();
}