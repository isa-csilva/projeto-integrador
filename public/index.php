<?php

session_start();

define('ROOT_PATH', dirname(__DIR__));
define('VIEW_PATH', ROOT_PATH . '/app/Views');

require_once ROOT_PATH . '/core/helpers.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Router.php';
require_once ROOT_PATH . '/core/Database.php';

require_once ROOT_PATH . '/app/Models/Aluno.php';

require_once ROOT_PATH . '/app/Controllers/ErrorController.php';
require_once ROOT_PATH . '/app/Controllers/HomeController.php';
require_once ROOT_PATH . '/app/Controllers/DashboardController.php';
require_once ROOT_PATH . '/app/Controllers/AuthController.php';
require_once ROOT_PATH . '/app/Controllers/AlunoController.php';
require_once ROOT_PATH . '/app/Controllers/ModuloController.php';

$router = new Router();

$router->get('/', array('HomeController', 'index'));
$router->get('/dashboard', array('DashboardController', 'index'));

$router->get('/login', array('AuthController', 'login'));
$router->post('/login', array('AuthController', 'authenticate'));
$router->get('/logout', array('AuthController', 'logout'));

$router->get('/alunos', array('AlunoController', 'index'));
$router->get('/alunos/criar', array('AlunoController', 'create'));
$router->post('/alunos/salvar', array('AlunoController', 'store'));

$router->get('/professores', array('ModuloController', 'professores'));
$router->get('/turmas', array('ModuloController', 'turmas'));
$router->get('/disciplinas', array('ModuloController', 'disciplinas'));
$router->get('/matriculas', array('ModuloController', 'matriculas'));
$router->get('/usuarios', array('ModuloController', 'usuarios'));

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']);
} catch (Throwable $exception) {
    error_log('[Aplicação] Erro não tratado: ' . $exception->getMessage());
    http_response_code(500);

    $controller = new ErrorController();
    $controller->internalServerError();
}
