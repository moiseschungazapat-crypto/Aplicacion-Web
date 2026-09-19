<?php
// Entrada serverless para Vercel. Conserva la aplicación MVC existente.
try {
    require dirname(__DIR__) . '/index.php';
} catch (Throwable $error) {
    error_log('EvyStream Vercel error: ' . $error->getMessage());
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Error interno de la aplicación. Revisa los logs de Vercel.\n";
    echo "Detalle: " . $error->getMessage();
}
