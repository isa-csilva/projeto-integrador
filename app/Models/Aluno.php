<?php

class Aluno
{
    private $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?: Database::connect();
    }

    public function listarTodos()
    {
        $statement = $this->connection->prepare(
            'SELECT id, nome, email, matricula, turma, criado_em
             FROM alunos
             ORDER BY nome ASC, id ASC'
        );
        $statement->execute();

        return $statement->fetchAll();
    }

    public function cadastrar($dados)
    {
        $statement = $this->connection->prepare(
            'INSERT INTO alunos (nome, email, matricula, turma)
             VALUES (:nome, :email, :matricula, :turma)'
        );

        return $statement->execute(array(
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'matricula' => $dados['matricula'],
            'turma' => $dados['turma']
        ));
    }

    public function emailExiste($email)
    {
        $statement = $this->connection->prepare(
            'SELECT 1 FROM alunos WHERE email = :email LIMIT 1'
        );
        $statement->execute(array('email' => $email));

        return $statement->fetchColumn() !== false;
    }

    public function matriculaExiste($matricula)
    {
        $statement = $this->connection->prepare(
            'SELECT 1 FROM alunos WHERE matricula = :matricula LIMIT 1'
        );
        $statement->execute(array('matricula' => $matricula));

        return $statement->fetchColumn() !== false;
    }
}
