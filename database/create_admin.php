<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/app/Models/Usuario.php';

$env = function ($name, $default = null) {
    $value = getenv($name);

    return $value === false ? $default : $value;
};

$dados = Usuario::normalizarDados(array(
    'nome' => $env('APP_ADMIN_NAME', 'Administrador'),
    'email' => $env('APP_ADMIN_EMAIL', 'admin@escola.test'),
    'senha' => $env('APP_ADMIN_PASSWORD', ''),
    'perfil' => Usuario::PERFIL_ADMINISTRADOR
));
$errors = Usuario::validarDados($dados);

if (!empty($errors)) {
    fwrite(STDERR, "Não foi possível criar o administrador:\n");

    foreach ($errors as $field => $message) {
        fwrite(STDERR, '- ' . $field . ': ' . $message . PHP_EOL);
    }

    fwrite(STDERR, "Defina APP_ADMIN_PASSWORD com uma senha de pelo menos 8 caracteres.\n");
    exit(1);
}

try {
    $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);

    if (!is_string($senhaHash)) {
        throw new RuntimeException('Falha ao gerar o hash da senha.');
    }

    $usuarioModel = new Usuario();
    $existente = $usuarioModel->buscarPorEmail($dados['email']);

    if ($existente === null) {
        $success = $usuarioModel->cadastrar(array(
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'senha_hash' => $senhaHash,
            'perfil' => Usuario::PERFIL_ADMINISTRADOR
        ));
        $action = 'criado';
    } else {
        $success = $usuarioModel->atualizarAdministradorInicial(
            (int) $existente['id'],
            $dados['nome'],
            $senhaHash
        );
        $action = 'atualizado';
    }

    if (!$success) {
        throw new RuntimeException('A gravação do administrador não foi confirmada.');
    }
} catch (Throwable $exception) {
    error_log('[create_admin] ' . $exception->getMessage());
    fwrite(STDERR, "Não foi possível gravar o administrador. Verifique o banco e tente novamente.\n");
    exit(1);
} finally {
    $dados['senha'] = '';
    unset($senhaHash);
}

fwrite(STDOUT, 'Administrador ' . $action . ' com sucesso para ' . $dados['email'] . ".\n");
