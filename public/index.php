<?php

$secureCookies = getenv('SESSION_SECURE_COOKIE') === '1' || (isset($_SERVER['HTTPS'])
    && is_string($_SERVER['HTTPS'])
    && $_SERVER['HTTPS'] !== ''
    && strtolower($_SERVER['HTTPS']) !== 'off');

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
session_set_cookie_params(array(
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $secureCookies,
    'httponly' => true,
    'samesite' => 'Lax'
));
session_start();

define('ROOT_PATH', dirname(__DIR__));
define('VIEW_PATH', ROOT_PATH . '/app/Views');

require_once ROOT_PATH . '/core/helpers.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';

require_once ROOT_PATH . '/app/Models/Aluno.php';
require_once ROOT_PATH . '/app/Models/Usuario.php';

require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/core/Router.php';

require_once ROOT_PATH . '/app/Controllers/ErrorController.php';
require_once ROOT_PATH . '/app/Controllers/HomeController.php';
require_once ROOT_PATH . '/app/Controllers/DashboardController.php';
require_once ROOT_PATH . '/app/Controllers/AuthController.php';
require_once ROOT_PATH . '/app/Controllers/AlunoController.php';
require_once ROOT_PATH . '/app/Controllers/ModuloController.php';
require_once ROOT_PATH . '/app/Controllers/UsuarioController.php';

Auth::boot();

$router = new Router();

try {
    require ROOT_PATH . '/routes/web.php';
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']);
} catch (Throwable $exception) {
    error_log('[Aplicação] Erro não tratado: ' . $exception->getMessage());
    http_response_code(500);

    $controller = new ErrorController();
    $controller->internalServerError();
}
