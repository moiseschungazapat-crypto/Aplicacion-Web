<?php

class Conexion {
    private static $instancia = null;

    private static function cargarEntorno() {
        $archivo = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
        if (!is_file($archivo)) return;
        foreach (file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
            $linea = trim($linea);
            if ($linea === '' || str_starts_with($linea, '#') || !str_contains($linea, '=')) continue;
            [$clave, $valor] = explode('=', $linea, 2);
            $clave = trim($clave);
            $valor = trim($valor);
            if ((str_starts_with($valor, '"') && str_ends_with($valor, '"')) || (str_starts_with($valor, "'") && str_ends_with($valor, "'"))) {
                $valor = substr($valor, 1, -1);
            }
            if (getenv($clave) === false) putenv($clave . '=' . $valor);
        }
    }

    public static function conectar() {
        if (self::$instancia === null) {
            self::cargarEntorno();
            $driver = getenv('DB_DRIVER') ?: 'mysql';
            $databaseUrl = getenv('DATABASE_URL');

            if ($databaseUrl) {
                $url = parse_url($databaseUrl);
                if (!$url || empty($url['host'])) {
                    throw new InvalidArgumentException('DATABASE_URL no tiene un formato válido.');
                }
                $driver = str_starts_with($databaseUrl, 'mysql') ? 'mysql' : 'pgsql';
                $dsn = "$driver:host={$url['host']};port=" . ($url['port'] ?? ($driver === 'pgsql' ? '5432' : '3306')) . ";dbname=" . ltrim($url['path'] ?? '', '/');
                if ($driver === 'pgsql') $dsn .= ';sslmode=require';
                $usuario = $url['user'] ?? null;
                $contra = isset($url['pass']) ? urldecode($url['pass']) : null;
            } else {
                $servidor = getenv('DB_HOST') ?: '127.0.0.1';
                $puerto   = getenv('DB_PORT') ?: '3307';
                $usuario  = getenv('DB_USER') ?: 'root';
                $contra   = getenv('DB_PASSWORD') ?: '';
                $base     = getenv('DB_NAME') ?: 'evystream';
                $dsn = "$driver:host=$servidor;port=$puerto;dbname=$base" . ($driver === 'mysql' ? ';charset=utf8mb4' : '');
            }

            try {
                self::$instancia = new PDO($dsn, $usuario, $contra);
                self::$instancia->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instancia->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$instancia;
    }
}

?>
