<?php

class ModuloController extends Controller
{
    public function professores()
    {
        $this->module('Professores', 'Cadastro e consulta de professores.');
    }

    public function turmas()
    {
        $this->module('Turmas', 'Organização de alunos por ano, série ou período.');
    }

    public function disciplinas()
    {
        $this->module('Disciplinas', 'Cadastro das matérias oferecidas pela escola.');
    }

    public function matriculas()
    {
        $this->module('Matrículas', 'Vínculo entre alunos, turmas e período letivo.');
    }

    public function usuarios()
    {
        $this->module('Usuários', 'Controle de acesso e perfis do sistema.');
    }

    private function module($title, $description)
    {
        $this->view('modulos.show', array(
            'title' => $title,
            'description' => $description
        ));
    }
}
