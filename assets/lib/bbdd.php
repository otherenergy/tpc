<?php

if (!function_exists('loadEnv')) {

    function loadEnv($path) {
        if (!file_exists($path)) {
            throw new Exception("El archivo .env no existe en la ruta especificada: $path");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);

            $key = trim($key);
            $value = trim($value);

            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
            }
        }
    }
}

// Cargar las variables del archivo .env
loadEnv(dirname(dirname(__DIR__)) . '/.env');

define('SERVER', $_ENV['DB_HOST']);
define('USER', $_ENV['DB_USERNAME']);
define('PASS', $_ENV['DB_PASSWORD']);
define('DB', $_ENV['DB_DATABASE']);

$conn = new mysqli(SERVER, USER, PASS, DB);
$conn->set_charset("utf8mb4");

?>