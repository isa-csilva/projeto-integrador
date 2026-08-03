<?php

class ErrorController extends Controller
{
    public function notFound()
    {
        $this->view('errors.404', array('title' => 'Página não encontrada'));
    }

    public function methodNotAllowed()
    {
        $this->view('errors.405', array('title' => 'Método não permitido'));
    }

    public function internalServerError()
    {
        $this->view('errors.500', array('title' => 'Erro interno'));
    }
}
