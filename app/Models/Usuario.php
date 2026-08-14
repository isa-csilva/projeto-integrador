<?php

class Usuario
{
    public const PERFIL_ADMINISTRADOR = 'administrador';
    public const PERFIL_SECRETARIA = 'secretaria';
    public const PERFIL_CONSULTA = 'consulta';

    private $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?: Database::connect();
    }

    public function listarTodos()
    {
        $statement = $this->connection->prepare(
            'SELECT id, nome, email, perfil, ativo, criado_em
             FROM usuarios
             ORDER BY nome ASC, id ASC'
        );
        $statement->execute();

        return $statement->fetchAll();
    }

    public function buscarPorEmail($email)
    {
        $statement = $this->connection->prepare(
            'SELECT id, nome, email, senha_hash, perfil, ativo
             FROM usuarios
             WHERE email = :email
             LIMIT 1'
        );
        $statement->execute(array('email' => strtolower(trim((string) $email))));

        $usuario = $statement->fetch();

        return $usuario === false ? null : $usuario;
    }

    public function emailExiste($email)
    {
        $statement = $this->connection->prepare(
            'SELECT 1 FROM usuarios WHERE email = :email LIMIT 1'
        );
        $statement->execute(array('email' => strtolower(trim((string) $email))));

        return $statement->fetchColumn() !== false;
    }

    public function cadastrar($dados)
    {
        $statement = $this->connection->prepare(
            'INSERT INTO usuarios (nome, email, senha_hash, perfil, ativo)
             VALUES (:nome, :email, :senha_hash, :perfil, 1)'
        );

        return $statement->execute(array(
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'senha_hash' => $dados['senha_hash'],
            'perfil' => $dados['perfil']
        ));
    }

    public function atualizarSenha($id, $senhaHash)
    {
        $statement = $this->connection->prepare(
            'UPDATE usuarios SET senha_hash = :senha_hash WHERE id = :id'
        );

        return $statement->execute(array(
            'id' => (int) $id,
            'senha_hash' => $senhaHash
        ));
    }

    public function atualizarAdministradorInicial($id, $nome, $senhaHash)
    {
        $statement = $this->connection->prepare(
            'UPDATE usuarios
             SET nome = :nome,
                 senha_hash = :senha_hash,
                 perfil = :perfil,
                 ativo = 1
             WHERE id = :id'
        );

        return $statement->execute(array(
            'id' => (int) $id,
            'nome' => $nome,
            'senha_hash' => $senhaHash,
            'perfil' => self::PERFIL_ADMINISTRADOR
        ));
    }

    public static function perfis()
    {
        return array(
            self::PERFIL_ADMINISTRADOR => 'Administrador',
            self::PERFIL_SECRETARIA => 'Secretaria',
            self::PERFIL_CONSULTA => 'Consulta'
        );
    }

    public static function perfilValido($perfil)
    {
        return is_string($perfil) && array_key_exists($perfil, self::perfis());
    }

    public static function perfilLabel($perfil)
    {
        $perfis = self::perfis();

        return isset($perfis[$perfil]) ? $perfis[$perfil] : 'Perfil desconhecido';
    }

    public static function normalizarDados($source)
    {
        $source = is_array($source) ? $source : array();

        return array(
            'nome' => self::valorTexto($source, 'nome'),
            'email' => strtolower(self::valorTexto($source, 'email')),
            'senha' => self::valorSenha($source, 'senha'),
            'perfil' => strtolower(self::valorTexto($source, 'perfil'))
        );
    }

    public static function validarDados($dados)
    {
        $errors = array();

        if ($dados['nome'] === '') {
            $errors['nome'] = 'Informe o nome do usuário.';
        } elseif (self::tamanho($dados['nome']) > 120) {
            $errors['nome'] = 'O nome deve ter no máximo 120 caracteres.';
        }

        if ($dados['email'] === '') {
            $errors['email'] = 'Informe o e-mail do usuário.';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Informe um e-mail válido.';
        } elseif (self::tamanho($dados['email']) > 150) {
            $errors['email'] = 'O e-mail deve ter no máximo 150 caracteres.';
        }

        if ($dados['senha'] === '') {
            $errors['senha'] = 'Informe uma senha.';
        } elseif (strpos($dados['senha'], "\0") !== false) {
            $errors['senha'] = 'A senha contém um caractere inválido.';
        } elseif (self::tamanho($dados['senha']) < 8) {
            $errors['senha'] = 'A senha deve ter pelo menos 8 caracteres.';
        } elseif (strlen($dados['senha']) > 72) {
            $errors['senha'] = 'A senha deve ter no máximo 72 bytes.';
        }

        if (!self::perfilValido($dados['perfil'])) {
            $errors['perfil'] = 'Selecione um perfil válido.';
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

    private static function valorSenha($source, $key)
    {
        if (!isset($source[$key]) || !is_string($source[$key])) {
            return '';
        }

        return $source[$key];
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
