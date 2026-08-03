<?php

class AlunoController extends Controller
{
    public function index()
    {
        $alunos = array();
        $loadError = null;

        try {
            $alunoModel = new Aluno();
            $alunos = $alunoModel->listarTodos();
        } catch (Throwable $exception) {
            error_log('[AlunoController::index] ' . $exception->getMessage());
            $loadError = 'Não foi possível carregar os alunos. Verifique o banco de dados e tente novamente.';
        }

        $flash = null;

        if (isset($_SESSION['aluno_flash']) && is_array($_SESSION['aluno_flash'])) {
            $flash = $_SESSION['aluno_flash'];
        }

        unset($_SESSION['aluno_flash']);

        $this->view('alunos.index', array(
            'title' => 'Alunos',
            'alunos' => $alunos,
            'flash' => $flash,
            'loadError' => $loadError
        ));
    }

    public function create()
    {
        $formState = array();

        if (isset($_SESSION['aluno_form']) && is_array($_SESSION['aluno_form'])) {
            $formState = $_SESSION['aluno_form'];
        }

        unset($_SESSION['aluno_form']);

        $this->view('alunos.create', array(
            'title' => 'Novo aluno',
            'errors' => isset($formState['errors']) && is_array($formState['errors'])
                ? $formState['errors']
                : array(),
            'old' => isset($formState['old']) && is_array($formState['old'])
                ? $formState['old']
                : array(),
            'formError' => isset($formState['formError'])
                ? (string) $formState['formError']
                : null
        ));
    }

    public function store()
    {
        $old = $this->normalize($_POST);
        $errors = $this->validate($old);

        if (!empty($errors)) {
            $this->redirectToForm($errors, $old);
        }

        try {
            $alunoModel = new Aluno();

            if ($alunoModel->emailExiste($old['email'])) {
                $errors['email'] = 'Já existe um aluno cadastrado com este e-mail.';
            }

            if ($alunoModel->matriculaExiste($old['matricula'])) {
                $errors['matricula'] = 'Já existe um aluno cadastrado com esta matrícula.';
            }

            if (!empty($errors)) {
                $this->redirectToForm($errors, $old);
            }

            if (!$alunoModel->cadastrar($old)) {
                throw new RuntimeException('A inserção do aluno não foi confirmada.');
            }
        } catch (PDOException $exception) {
            error_log('[AlunoController::store] Falha do PDO: ' . $exception->getMessage());

            if ((string) $exception->getCode() === '23000') {
                $this->redirectToForm(
                    array(),
                    $old,
                    'E-mail ou matrícula já cadastrados. Confira os dados e tente novamente.'
                );
            }

            $this->redirectToForm(
                array(),
                $old,
                'Não foi possível cadastrar o aluno agora. Tente novamente em instantes.'
            );
        } catch (Throwable $exception) {
            error_log('[AlunoController::store] Erro inesperado: ' . $exception->getMessage());
            $this->redirectToForm(
                array(),
                $old,
                'Não foi possível cadastrar o aluno agora. Verifique o banco de dados e tente novamente.'
            );
        }

        $_SESSION['aluno_flash'] = array(
            'type' => 'success',
            'message' => 'Aluno cadastrado com sucesso.'
        );

        $this->redirect('/alunos', 303);
    }

    private function normalize($source)
    {
        $email = strtolower($this->stringValue($source, 'email'));

        return array(
            'nome' => $this->stringValue($source, 'nome'),
            'email' => $email,
            'matricula' => $this->stringValue($source, 'matricula'),
            'turma' => $this->stringValue($source, 'turma')
        );
    }

    private function stringValue($source, $key)
    {
        if (!isset($source[$key]) || !is_string($source[$key])) {
            return '';
        }

        return trim($source[$key]);
    }

    private function validate($data)
    {
        $errors = array();

        if ($data['nome'] === '') {
            $errors['nome'] = 'Informe o nome do aluno.';
        } elseif ($this->length($data['nome']) > 120) {
            $errors['nome'] = 'O nome deve ter no máximo 120 caracteres.';
        }

        if ($data['email'] === '') {
            $errors['email'] = 'Informe o e-mail do aluno.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Informe um e-mail válido.';
        } elseif ($this->length($data['email']) > 150) {
            $errors['email'] = 'O e-mail deve ter no máximo 150 caracteres.';
        }

        if ($data['matricula'] === '') {
            $errors['matricula'] = 'Informe a matrícula.';
        } elseif ($this->length($data['matricula']) > 30) {
            $errors['matricula'] = 'A matrícula deve ter no máximo 30 caracteres.';
        }

        if ($data['turma'] === '') {
            $errors['turma'] = 'Informe a turma.';
        } elseif ($this->length($data['turma']) > 50) {
            $errors['turma'] = 'A turma deve ter no máximo 50 caracteres.';
        }

        return $errors;
    }

    private function length($value)
    {
        return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
    }

    private function redirectToForm($errors, $old, $formError = null)
    {
        $_SESSION['aluno_form'] = array(
            'errors' => $errors,
            'old' => $old,
            'formError' => $formError
        );

        $this->redirect('/alunos/criar', 303);
    }
}
