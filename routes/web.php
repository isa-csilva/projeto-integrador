<?php

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
