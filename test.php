<?php

include("CONFIG/conexion.php");

try {
    $conexion = Conexion::conectar();
    $resultado = $conexion->query("SELECT current_database() AS base, current_schema() AS esquema")->fetch();
    echo "Conectado correctamente a PostgreSQL/Supabase.\n";
    echo "Base: {$resultado['base']} | Esquema: {$resultado['esquema']}\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo "No se pudo conectar: " . $e->getMessage();
}
