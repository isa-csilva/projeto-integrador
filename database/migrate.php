<?php

// Execute explicitamente por CLI; nunca durante uma requisição ou a cada deploy.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/core/Database.php';

try {
    $connection = Database::connect();
    $schema = file_get_contents(__DIR__ . '/schema.sql');

    if ($schema === false) {
        throw new RuntimeException('Schema indisponível.');
    }

    // O banco é selecionado por DB_NAME: não exige CREATE DATABASE no provedor.
    $schema = preg_replace('/CREATE DATABASE\b.*?;|USE\s+\w+\s*;/is', '', $schema);
    $connection->exec($schema);
    fwrite(STDOUT, "Tabelas criadas/verificadas no banco configurado em DB_NAME.\n");
} catch (Throwable $exception) {
    error_log('[migrate] ' . $exception->getMessage());
    fwrite(STDERR, "Falha na preparação do banco. Confira configuração, TLS e permissões.\n");
    exit(1);
}
