<?php

$authenticated = array('auth');
$studentReaders = array(
    'auth',
    'perfil:administrador,secretaria,consulta'
);
$studentManagers = array(
    'auth',
    'perfil:administrador,secretaria'
);
$administrators = array(
    'auth',
    'perfil:administrador'
);

$router->get('/', array('HomeController', 'index'));
$router->get('/dashboard', array('DashboardController', 'index'), $authenticated);

$router->get('/login', array('AuthController', 'login'));
$router->post('/login', array('AuthController', 'authenticate'));
$router->post('/logout', array('AuthController', 'logout'), $authenticated);

$router->get('/alunos', array('AlunoController', 'index'), $studentReaders);
$router->get('/alunos/criar', array('AlunoController', 'create'), $studentManagers);
$router->post('/alunos/salvar', array('AlunoController', 'store'), $studentManagers);
$router->get('/alunos/{id}/editar', array('AlunoController', 'edit'), $studentManagers);
$router->post('/alunos/{id}/atualizar', array('AlunoController', 'update'), $studentManagers);
$router->get('/alunos/{id}/excluir', array('AlunoController', 'confirmDelete'), $studentManagers);
$router->post('/alunos/{id}/excluir', array('AlunoController', 'destroy'), $studentManagers);

$router->get('/professores', array('ModuloController', 'professores'), $studentReaders);
$router->get('/turmas', array('ModuloController', 'turmas'), $studentReaders);
$router->get('/disciplinas', array('ModuloController', 'disciplinas'), $studentReaders);
$router->get('/matriculas', array('ModuloController', 'matriculas'), $studentReaders);

$router->get('/usuarios', array('UsuarioController', 'index'), $administrators);
$router->get('/usuarios/criar', array('UsuarioController', 'create'), $administrators);
$router->post('/usuarios/salvar', array('UsuarioController', 'store'), $administrators);
