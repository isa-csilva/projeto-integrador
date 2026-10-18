<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$root = dirname(__DIR__);
$filename = 'test-admin-' . bin2hex(random_bytes(8)) . '.sql';
$path = $root . '/database/backups/' . $filename;
$checks = 0;
$exitCode = 0;

function checkExport($condition, $message)
{
    global $checks;
    if (!$condition) {
        throw new RuntimeException($message);
    }
    $checks++;
}

function runExport($filename, $overrides = array())
{
    global $root;
    $env = array_merge(getenv(), array(
        'APP_ADMIN_NAME' => "  João D'Ávila \\ Escola  ",
        'APP_ADMIN_EMAIL' => "  o'hara@Example.Test  ",
        'APP_ADMIN_PASSWORD' => 'senha-teste-segura-2026',
        'DB_HOST' => 'banco-inexistente.invalid',
        'DB_SSL_CA' => '/certificado/inexistente.pem'
    ), $overrides);
    // -n comprova que não é necessário configurar extensões/credenciais MySQL.
    $process = proc_open(
        array(PHP_BINARY, '-n', $root . '/database/export_admin.php', $filename),
        array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')),
        $pipes,
        $root,
        $env
    );
    if (!is_resource($process)) {
        throw new RuntimeException('Não foi possível iniciar o exportador.');
    }
    fclose($pipes[0]);
    $output = stream_get_contents($pipes[1]);
    $error = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    return array(proc_close($process), $output . $error);
}

try {
    $result = runExport($filename);
    checkExport($result[0] === 0 && is_file($path), 'Exportação offline: ' . $result[1]);
    $sql = file_get_contents($path);
    checkExport(strpos($sql . $result[1], 'senha-teste-segura-2026') === false, 'Senha em texto puro exposta');
    preg_match_all("/CONVERT\(X'([0-9a-f]+)' USING utf8mb4\)/", $sql, $matches);
    $values = array_map('hex2bin', $matches[1]);
    checkExport(count($values) === 3, 'Nome, e-mail e hash devem ser literais hexadecimais');
    checkExport($values[0] === "João D'Ávila \\ Escola" && $values[1] === "o'hara@example.test", 'UTF-8, aspas, barras e normalização');
    checkExport(password_verify('senha-teste-segura-2026', $values[2]), 'Hash deve autenticar a senha informada');
    checkExport(substr_count($sql, 'INSERT INTO usuarios') === 1 && !preg_match('/\b(UPDATE|REPLACE|IGNORE)\b/i', $sql), 'Importação deve apenas inserir, sem alterar conta existente');
    $repeat = runExport($filename, array('APP_ADMIN_PASSWORD' => 'outra-senha-segura'));
    checkExport($repeat[0] === 1 && file_get_contents($path) === $sql, 'Reexportação não pode sobrescrever SQL existente');
    unlink($path);
    $invalid = runExport($filename, array('APP_ADMIN_EMAIL' => 'invalido', 'APP_ADMIN_PASSWORD' => 'curta'));
    checkExport($invalid[0] === 1 && !file_exists($path), 'Dados inválidos não devem gerar SQL');
    $traversal = runExport('../' . $filename);
    checkExport($traversal[0] === 1 && !file_exists($root . '/database/' . $filename), 'Arquivo deve permanecer na pasta ignorada');

    $local = file_get_contents($root . '/database/schema.sql');
    $hosted = file_get_contents($root . '/database/schema-hosted.sql');
    checkExport(!preg_match('/\bCREATE\s+DATABASE\b|\bUSE\s+\w+/i', $hosted), 'Schema hospedado não deve escolher/criar banco');
    preg_match_all('/CREATE TABLE\b.*?;/is', $local, $localTables);
    preg_match_all('/CREATE TABLE\b.*?;/is', $hosted, $hostedTables);
    $normalize = function ($statement) {
        $statement = preg_replace('/\s+CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci/i', '', $statement);
        return preg_replace('/\s+/', ' ', trim($statement));
    };
    checkExport(count($hostedTables[0]) === 2 && array_map($normalize, $localTables[0]) === array_map($normalize, $hostedTables[0]), 'Schemas local e hospedado devem ter as mesmas tabelas');
    fwrite(STDOUT, $checks . " verificações de exportação aprovadas (sem acesso ao banco).\n");
} catch (Throwable $error) {
    fwrite(STDERR, 'Falha: ' . $error->getMessage() . PHP_EOL);
    $exitCode = 1;
} finally {
    if (is_file($path)) {
        unlink($path);
    }
}
exit($exitCode);
