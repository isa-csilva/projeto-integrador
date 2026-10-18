<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

// Cópias isoladas: nunca escreve nem lê as credenciais locais reais.
$fixture = sys_get_temp_dir() . '/escolar-config-' . bin2hex(random_bytes(8));
mkdir($fixture);
define('ROOT_PATH', $fixture);
mkdir($fixture . '/config');
copy(dirname(__DIR__) . '/config/database.php', $fixture . '/config/database.php');
require dirname(__DIR__) . '/core/Database.php';

function expectConfig($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function loadConfig()
{
    return require ROOT_PATH . '/config/database.php';
}

try {
    foreach (array('DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_SSL_CA') as $key) {
        putenv($key);
    }
    $config = loadConfig();
    expectConfig($config['host'] === '127.0.0.1' && $config['name'] === 'sistema_escolar', 'Padrão XAMPP');
    expectConfig($config['user'] === 'root' && $config['pass'] === '' && $config['ssl_ca'] === '', 'Credenciais padrão');

    putenv('DB_HOST=old.example.test');
    putenv('DB_SSL_CA=/obsolete/ca.pem');
    expectConfig(loadConfig()['host'] === 'old.example.test', 'Variáveis de ambiente');

    $localConfig = array('host' => 'sql.example.test', 'name' => 'if0_test', 'user' => 'if0_user', 'pass' => "quote'\\test", 'ssl_ca' => '');
    file_put_contents($fixture . '/config/database.local.php', '<?php return ' . var_export($localConfig, true) . ';');
    $config = loadConfig();
    expectConfig($config['host'] === 'sql.example.test' && $config['pass'] === $localConfig['pass'], 'Arquivo da hospedagem');
    expectConfig($config['ssl_ca'] === '' && $config['port'] === 3306, 'Arquivo limpa CA antigo e preserva padrão de porta');

    file_put_contents($fixture . '/config/database.local.php', '<?php return false;');
    try {
        loadConfig();
        throw new LogicException('Arquivo inválido aceito');
    } catch (RuntimeException $error) {
        expectConfig($error->getMessage() === 'Configuração local do banco inválida.', 'Erro de configuração');
    }

    unlink($fixture . '/config/database.local.php');
    try {
        Database::connect();
        throw new LogicException('CA ausente aceito');
    } catch (RuntimeException $error) {
        expectConfig($error->getMessage() === 'Certificado CA do banco indisponível.', 'TLS externo permanece validado');
    }
    fwrite(STDOUT, "Configuração local, ambiente, hospedagem e CA: aprovados.\n");
} finally {
    foreach (array('database.local.php', 'database.php') as $file) {
        if (is_file($fixture . '/config/' . $file)) {
            unlink($fixture . '/config/' . $file);
        }
    }
    rmdir($fixture . '/config');
    rmdir($fixture);
}
