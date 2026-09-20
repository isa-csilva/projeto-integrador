<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

define('ROOT_PATH', dirname(__DIR__));
define('VIEW_PATH', ROOT_PATH . '/app/Views');
session_start();
foreach (array('helpers', 'Controller', 'Database', 'Auth', 'Router') as $file) {
    require_once ROOT_PATH . '/core/' . $file . '.php';
}
foreach (glob(ROOT_PATH . '/app/Models/*.php') as $file) {
    require_once $file;
}
foreach (glob(ROOT_PATH . '/app/Controllers/*.php') as $file) {
    require_once $file;
}

$checks = 0;
function check($condition, $message)
{
    global $checks;
    if (!$condition) {
        throw new RuntimeException($message);
    }
    $checks++;
}

function requestRoute($method, $path, $base = '')
{
    $_SERVER['SCRIPT_NAME'] = $base . '/index.php';
    $_SERVER['REQUEST_URI'] = $base . $path;
    $router = new Router();
    require ROOT_PATH . '/routes/web.php';
    http_response_code(200);
    ob_start();
    $router->dispatch($method, $_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']);
    $body = ob_get_clean();
    return array(http_response_code(), $body);
}

try {
    // Isola a configuração do processo de teste das credenciais do ambiente.
    foreach (array('DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_SSL_CA') as $key) {
        putenv($key);
    }
    $config = require ROOT_PATH . '/config/database.php';
    check($config['host'] === '127.0.0.1' && $config['name'] === 'sistema_escolar', 'Padrões locais');
    check($config['pass'] === '' && $config['ssl_ca'] === '', 'TLS local opcional');
    putenv('DB_SSL_CA=' . __DIR__ . '/missing-ca.pem');
    try {
        Database::connect();
        throw new LogicException('CA ausente foi aceita');
    } catch (RuntimeException $error) {
        check($error->getMessage() === 'Certificado CA do banco indisponível.', 'TLS deve falhar sem CA');
    }
    putenv('DB_SSL_CA');
    check(csrfIsValid(csrfToken()) && !csrfIsValid('incorreto'), 'CSRF');
    check(requestRoute('GET', '/')[0] === 200, 'Página inicial');
    check(requestRoute('GET', '/login')[0] === 200, 'Login');
    check(requestRoute('GET', '/alunos')[0] === 302, 'Autenticação obrigatória');
    check(requestRoute('POST', '/alunos/salvar')[0] === 303, 'Escrita sem login');
    check(requestRoute('GET', '/logout')[0] === 405, 'Logout exige POST');
    check(requestRoute('GET', '/inexistente')[0] === 404, 'Rota inexistente');
    check(requestRoute('GET', '/login', '/projeto-integrador/public')[0] === 200, 'Login em subpasta');
    check(url('/alunos') === '/projeto-integrador/public/alunos', 'URL em subpasta');

    foreach (Usuario::perfis() as $perfil => $label) {
        Auth::login(array('id' => 1, 'nome' => 'Teste', 'email' => 'teste@example.test', 'perfil' => $perfil));
        check(requestRoute('GET', '/dashboard')[0] === 200, 'Dashboard: ' . $perfil);
        check(requestRoute('GET', '/usuarios/criar')[0] === ($perfil === 'administrador' ? 200 : 403), 'Usuários: ' . $perfil);
        check(requestRoute('GET', '/alunos/criar')[0] === ($perfil === 'consulta' ? 403 : 200), 'Alunos: ' . $perfil);
    }
    putenv('AUTH_IDLE_TIMEOUT=60');
    $_SESSION['auth_last_activity'] = time() - 61;
    Auth::boot();
    check(!Auth::check(), 'Expiração por inatividade');
    check(Aluno::validarDados(Aluno::normalizarDados(array())) !== array(), 'Validação de aluno');
    check(strpos(e('<script>'), '<script>') === false, 'Escape HTML');
    fwrite(STDOUT, $checks . " verificações aprovadas (sem acesso ao banco).\n");
} catch (Throwable $error) {
    fwrite(STDERR, 'Falha: ' . $error->getMessage() . PHP_EOL);
    exit(1);
}
