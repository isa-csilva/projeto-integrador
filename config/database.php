<?php

$env = function ($name, $default) {
    $value = getenv($name);

    return $value === false ? $default : $value;
};

return array(
    'host' => (string) $env('DB_HOST', '127.0.0.1'),
    'port' => (int) $env('DB_PORT', 3306),
    'name' => (string) $env('DB_NAME', 'sistema_escolar'),
    'user' => (string) $env('DB_USER', 'root'),
    'pass' => (string) $env('DB_PASS', '')
);
