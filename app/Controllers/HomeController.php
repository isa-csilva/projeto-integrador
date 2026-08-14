<?php

class HomeController extends Controller
{
    public function index()
    {
        $cards = array(
            array('label' => 'Entrega Parcial 2', 'value' => 'Estrutura MVC e rotas'),
            array('label' => 'Entrega Parcial 3', 'value' => 'Cadastro e listagem de alunos'),
            array('label' => 'Entrega Parcial 4', 'value' => 'CRUD completo de alunos'),
            array('label' => 'Entrega Parcial 5', 'value' => 'Autenticação e controle de acesso')
        );

        $this->view('home.index', array(
            'title' => 'Início',
            'cards' => $cards,
            'authenticated' => Auth::check(),
            'canManageStudents' => Auth::hasAnyProfile(array(
                Usuario::PERFIL_ADMINISTRADOR,
                Usuario::PERFIL_SECRETARIA
            )),
            'canManageUsers' => Auth::hasAnyProfile(Usuario::PERFIL_ADMINISTRADOR)
        ));
    }
}
