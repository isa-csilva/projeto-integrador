<?php

class HomeController extends Controller
{
    public function index()
    {
        $cards = array(
            array('label' => 'Entrega Parcial 2', 'value' => 'Estrutura MVC e rotas'),
            array('label' => 'Entrega Parcial 3', 'value' => 'Cadastro e listagem de alunos'),
            array('label' => 'Próxima etapa', 'value' => 'Edição e exclusão')
        );

        $this->view('home.index', array(
            'title' => 'Início',
            'cards' => $cards
        ));
    }
}
