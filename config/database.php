<?php

$env = function ($name, $default) {
    $value = getenv($name);

    return $value === false ? $default : $value;
};

$config = array(
    'host' => (string) $env('DB_HOST', '127.0.0.1'),
    'port' => (int) $env('DB_PORT', 3306),
    'name' => (string) $env('DB_NAME', 'sistema_escolar'),
    'user' => (string) $env('DB_USER', 'root'),
    'pass' => (string) $env('DB_PASS', ''),
    'ssl_ca' => (string) $env('DB_SSL_CA', '')
);

// Hospedagem compartilhada: credenciais fora do Git, sem depender de SetEnv.
// Os valores explícitos do arquivo local prevalecem sobre o ambiente.
$localFile = __DIR__ . '/database.local.php';
if (is_file($localFile)) {
    $local = require $localFile;

    if (!is_array($local)) {
        throw new RuntimeException('Configuração local do banco inválida.');
    }

    $config = array_replace($config, array_intersect_key($local, $config));
}

return $config;
