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

    public function buscarPorId($id)
    {
        $statement = $this->connection->prepare(
            'SELECT id, nome, email, matricula, turma, criado_em
             FROM alunos
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(array('id' => (int) $id));

        $aluno = $statement->fetch();

        return $aluno === false ? null : $aluno;
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

    public function atualizar($id, $dados)
    {
        $statement = $this->connection->prepare(
            'UPDATE alunos
             SET nome = :nome,
                 email = :email,
                 matricula = :matricula,
                 turma = :turma
             WHERE id = :id'
        );

        $executed = $statement->execute(array(
            'id' => (int) $id,
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'matricula' => $dados['matricula'],
            'turma' => $dados['turma']
        ));

        if (!$executed) {
            return false;
        }

        return $statement->rowCount() > 0 || $this->buscarPorId($id) !== null;
    }

    public function excluir($id)
    {
        $statement = $this->connection->prepare(
            'DELETE FROM alunos WHERE id = :id'
        );
        $statement->execute(array('id' => (int) $id));

        return $statement->rowCount() > 0;
    }

    public function emailExiste($email, $ignorarId = null)
    {
        $sql = 'SELECT 1 FROM alunos WHERE email = :email';
        $params = array('email' => $email);

        if ($ignorarId !== null) {
            $sql .= ' AND id <> :ignorar_id';
            $params['ignorar_id'] = (int) $ignorarId;
        }

        $sql .= ' LIMIT 1';

        $statement = $this->connection->prepare(
            $sql
        );
        $statement->execute($params);

        return $statement->fetchColumn() !== false;
    }

    public function matriculaExiste($matricula, $ignorarId = null)
    {
        $sql = 'SELECT 1 FROM alunos WHERE matricula = :matricula';
        $params = array('matricula' => $matricula);

        if ($ignorarId !== null) {
            $sql .= ' AND id <> :ignorar_id';
            $params['ignorar_id'] = (int) $ignorarId;
        }

        $sql .= ' LIMIT 1';

        $statement = $this->connection->prepare(
            $sql
        );
        $statement->execute($params);

        return $statement->fetchColumn() !== false;
    }

    public static function normalizarDados($source)
    {
        $source = is_array($source) ? $source : array();
        $email = strtolower(self::valorTexto($source, 'email'));

        return array(
            'nome' => self::valorTexto($source, 'nome'),
            'email' => $email,
            'matricula' => self::valorTexto($source, 'matricula'),
            'turma' => self::valorTexto($source, 'turma')
        );
    }

    public static function validarDados($dados)
    {
        $errors = array();

        if ($dados['nome'] === '') {
            $errors['nome'] = 'Informe o nome do aluno.';
        } elseif (self::tamanho($dados['nome']) > 120) {
            $errors['nome'] = 'O nome deve ter no máximo 120 caracteres.';
        }

        if ($dados['email'] === '') {
            $errors['email'] = 'Informe o e-mail do aluno.';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Informe um e-mail válido.';
        } elseif (self::tamanho($dados['email']) > 150) {
            $errors['email'] = 'O e-mail deve ter no máximo 150 caracteres.';
        }

        if ($dados['matricula'] === '') {
            $errors['matricula'] = 'Informe a matrícula.';
        } elseif (self::tamanho($dados['matricula']) > 30) {
            $errors['matricula'] = 'A matrícula deve ter no máximo 30 caracteres.';
        }

        if ($dados['turma'] === '') {
            $errors['turma'] = 'Informe a turma.';
        } elseif (self::tamanho($dados['turma']) > 50) {
            $errors['turma'] = 'A turma deve ter no máximo 50 caracteres.';
        }

        return $errors;
    }

    private static function valorTexto($source, $key)
    {
        if (!isset($source[$key]) || !is_string($source[$key])) {
            return '';
        }

        return trim($source[$key]);
    }

    private static function tamanho($value)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($value, 'UTF-8');
        }

        $length = preg_match_all('/./us', $value, $characters);

        return $length === false ? strlen($value) : $length;
    }
}
