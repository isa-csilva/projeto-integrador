<?php

// Gera SQL no computador local, sem conectar ao banco e sem publicar instalador web.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/app/Models/Usuario.php';

$filename = $argv[1] ?? 'admin-inicial.sql';
if ($argc > 2 || strlen($filename) > 120 || !preg_match('/\A[a-zA-Z0-9][a-zA-Z0-9._-]*\.sql\z/', $filename)) {
    fwrite(STDERR, "Uso: php database/export_admin.php [nome-do-arquivo.sql]\n");
    fwrite(STDERR, "Use apenas um nome de arquivo; a pasta de saída é database/backups/.\n");
    exit(1);
}

$env = function ($name, $default = '') {
    $value = getenv($name);
    return $value === false ? $default : $value;
};
$dados = Usuario::normalizarDados(array(
    'nome' => $env('APP_ADMIN_NAME', 'Administrador'),
    'email' => $env('APP_ADMIN_EMAIL'),
    'senha' => $env('APP_ADMIN_PASSWORD'),
    'perfil' => Usuario::PERFIL_ADMINISTRADOR
));
$errors = Usuario::validarDados($dados);

if (!empty($errors)) {
    fwrite(STDERR, "Não foi possível exportar o administrador:\n");
    foreach ($errors as $field => $message) {
        fwrite(STDERR, '- ' . $field . ': ' . $message . PHP_EOL);
    }
    fwrite(STDERR, "Defina APP_ADMIN_NAME, APP_ADMIN_EMAIL e APP_ADMIN_PASSWORD no terminal.\n");
    $dados['senha'] = '';
    exit(1);
}

$directory = __DIR__ . '/backups';
$path = $directory . '/' . $filename;
$handle = null;
$created = false;
$success = false;
$previousUmask = umask(0077);

try {
    $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);
    if (!is_string($senhaHash)) {
        throw new RuntimeException('Falha ao gerar o hash da senha.');
    }

    // Literais hexadecimais preservam UTF-8, aspas e barras em qualquer sql_mode.
    $literal = function ($value) {
        return "CONVERT(X'" . bin2hex($value) . "' USING utf8mb4)";
    };
    $sql = "-- Importe no banco selecionado do phpMyAdmin, depois de schema-hosted.sql.\n"
        . "-- Contém um hash de senha. Não publique este arquivo no site ou no Git.\n"
        . "-- Um e-mail já cadastrado causa erro; nenhuma conta existente será alterada.\n"
        . "INSERT INTO usuarios (nome, email, senha_hash, perfil, ativo)\nVALUES ("
        . $literal($dados['nome']) . ', ' . $literal($dados['email']) . ', '
        . $literal($senhaHash) . ", 'administrador', 1);\n";

    if (!is_dir($directory) && !@mkdir($directory, 0700, true) && !is_dir($directory)) {
        throw new RuntimeException('Não foi possível criar database/backups/.');
    }
    // Modo exclusivo: uma nova execução nunca substitui uma exportação existente.
    $handle = @fopen($path, 'xb');
    if ($handle === false) {
        throw new RuntimeException('Arquivo já existe ou a pasta não permite escrita. Use outro nome .sql.');
    }
    $created = true;
    $offset = 0;
    $length = strlen($sql);
    while ($offset < $length) {
        $written = fwrite($handle, substr($sql, $offset));
        if ($written === false || $written === 0) {
            throw new RuntimeException('Não foi possível concluir a gravação do SQL.');
        }
        $offset += $written;
    }
    if (!fflush($handle)) {
        throw new RuntimeException('Não foi possível concluir a gravação do SQL.');
    }
    $success = true;
} catch (Throwable $exception) {
    fwrite(STDERR, 'Falha na exportação: ' . $exception->getMessage() . PHP_EOL);
} finally {
    if (is_resource($handle)) {
        fclose($handle);
    }
    if ($created && !$success) {
        unlink($path);
    }
    umask($previousUmask);
    $dados['senha'] = '';
    unset($senhaHash, $sql);
}

if (!$success) {
    exit(1);
}

fwrite(STDOUT, 'SQL gerado em database/backups/' . $filename . ".\n");
fwrite(STDOUT, "Importe pelo phpMyAdmin. A conta só será criada após a importação.\n");
