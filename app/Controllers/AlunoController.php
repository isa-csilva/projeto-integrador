<?php

class AlunoController extends Controller
{
    private const FLASH_KEY = 'aluno_flash';
    private const CREATE_FORM_KEY = 'aluno_create_form';
    private const EDIT_FORM_KEY = 'aluno_edit_form';

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

        $this->view('alunos.index', array(
            'title' => 'Alunos',
            'alunos' => $alunos,
            'flash' => $this->pullFlash(),
            'loadError' => $loadError,
            'canManage' => Auth::hasAnyProfile(array(
                Usuario::PERFIL_ADMINISTRADOR,
                Usuario::PERFIL_SECRETARIA
            ))
        ));
    }

    public function create()
    {
        $formState = $this->pullFormState(self::CREATE_FORM_KEY);

        $this->view('alunos.create', array_merge(
            array('title' => 'Novo aluno'),
            $this->formData($formState)
        ));
    }

    public function store()
    {
        $dados = Aluno::normalizarDados($_POST);
        $errors = Aluno::validarDados($dados);

        if (!$this->hasValidCsrfToken()) {
            $this->redirectToCreate(
                array(),
                $dados,
                'Sua sessão expirou. Recarregue o formulário e tente novamente.'
            );
        }

        if (!empty($errors)) {
            $this->redirectToCreate($errors, $dados);
        }

        try {
            $alunoModel = new Aluno();
            $errors = $this->duplicateErrors($alunoModel, $dados);

            if (!empty($errors)) {
                $this->redirectToCreate($errors, $dados);
            }

            if (!$alunoModel->cadastrar($dados)) {
                throw new RuntimeException('A inserção do aluno não foi confirmada.');
            }
        } catch (PDOException $exception) {
            error_log('[AlunoController::store] Falha do PDO: ' . $exception->getMessage());

            $message = (string) $exception->getCode() === '23000'
                ? 'E-mail ou matrícula já cadastrados. Confira os dados e tente novamente.'
                : 'Não foi possível cadastrar o aluno agora. Tente novamente em instantes.';

            $this->redirectToCreate(array(), $dados, $message);
        } catch (Throwable $exception) {
            error_log('[AlunoController::store] Erro inesperado: ' . $exception->getMessage());
            $this->redirectToCreate(
                array(),
                $dados,
                'Não foi possível cadastrar o aluno agora. Verifique o banco de dados e tente novamente.'
            );
        }

        $this->setFlash('success', 'Aluno cadastrado com sucesso.');
        $this->redirect('/alunos', 303);
    }

    public function edit($id)
    {
        $id = $this->requireValidId($id, false);

        try {
            $alunoModel = new Aluno();
            $aluno = $alunoModel->buscarPorId($id);
        } catch (Throwable $exception) {
            error_log('[AlunoController::edit] ' . $exception->getMessage());
            $this->setFlash('error', 'Não foi possível carregar o aluno para edição. Tente novamente.');
            $this->redirect('/alunos');
        }

        if ($aluno === null) {
            $this->redirectNotFound(false);
        }

        $formState = $this->pullEditFormState($id);

        $this->view('alunos.edit', array_merge(
            array(
                'title' => 'Editar aluno',
                'aluno' => $aluno
            ),
            $this->formData($formState, $aluno)
        ));
    }

    public function update($id)
    {
        $id = $this->requireValidId($id, true);
        $dados = Aluno::normalizarDados($_POST);
        $errors = Aluno::validarDados($dados);

        if (!$this->hasValidCsrfToken()) {
            $this->redirectToEdit(
                $id,
                array(),
                $dados,
                'Sua sessão expirou. Recarregue o formulário e tente novamente.'
            );
        }

        if (!empty($errors)) {
            $this->redirectToEdit($id, $errors, $dados);
        }

        try {
            $alunoModel = new Aluno();

            if ($alunoModel->buscarPorId($id) === null) {
                $this->redirectNotFound(true);
            }

            $errors = $this->duplicateErrors($alunoModel, $dados, $id);

            if (!empty($errors)) {
                $this->redirectToEdit($id, $errors, $dados);
            }

            if (!$alunoModel->atualizar($id, $dados)) {
                throw new RuntimeException('A atualização do aluno não foi confirmada.');
            }
        } catch (PDOException $exception) {
            error_log('[AlunoController::update] Falha do PDO: ' . $exception->getMessage());

            $message = (string) $exception->getCode() === '23000'
                ? 'E-mail ou matrícula já cadastrados. Confira os dados e tente novamente.'
                : 'Não foi possível atualizar o aluno agora. Tente novamente em instantes.';

            $this->redirectToEdit($id, array(), $dados, $message);
        } catch (Throwable $exception) {
            error_log('[AlunoController::update] Erro inesperado: ' . $exception->getMessage());
            $this->redirectToEdit(
                $id,
                array(),
                $dados,
                'Não foi possível atualizar o aluno agora. Verifique o banco de dados e tente novamente.'
            );
        }

        $this->setFlash('success', 'Aluno atualizado com sucesso.');
        $this->redirect('/alunos', 303);
    }

    public function confirmDelete($id)
    {
        $id = $this->requireValidId($id, false);

        try {
            $alunoModel = new Aluno();
            $aluno = $alunoModel->buscarPorId($id);
        } catch (Throwable $exception) {
            error_log('[AlunoController::confirmDelete] ' . $exception->getMessage());
            $this->setFlash('error', 'Não foi possível carregar o aluno para exclusão. Tente novamente.');
            $this->redirect('/alunos');
        }

        if ($aluno === null) {
            $this->redirectNotFound(false);
        }

        $this->view('alunos.delete', array(
            'title' => 'Excluir aluno',
            'aluno' => $aluno
        ));
    }

    public function destroy($id)
    {
        $id = $this->requireValidId($id, true);

        if (!$this->hasValidCsrfToken()) {
            $this->setFlash('error', 'Sua sessão expirou. Recarregue a página e tente novamente.');
            $this->redirect('/alunos', 303);
        }

        try {
            $alunoModel = new Aluno();

            if (!$alunoModel->excluir($id)) {
                $this->redirectNotFound(true);
            }
        } catch (PDOException $exception) {
            error_log('[AlunoController::destroy] Falha do PDO: ' . $exception->getMessage());
            $this->setFlash(
                'error',
                'Não foi possível excluir o aluno. Verifique se existem dados vinculados e tente novamente.'
            );
            $this->redirect('/alunos', 303);
        } catch (Throwable $exception) {
            error_log('[AlunoController::destroy] Erro inesperado: ' . $exception->getMessage());
            $this->setFlash('error', 'Não foi possível excluir o aluno agora. Tente novamente em instantes.');
            $this->redirect('/alunos', 303);
        }

        $this->setFlash('success', 'Aluno excluído com sucesso.');
        $this->redirect('/alunos', 303);
    }

    private function duplicateErrors($alunoModel, $dados, $ignorarId = null)
    {
        $errors = array();

        if ($alunoModel->emailExiste($dados['email'], $ignorarId)) {
            $errors['email'] = 'Já existe um aluno cadastrado com este e-mail.';
        }

        if ($alunoModel->matriculaExiste($dados['matricula'], $ignorarId)) {
            $errors['matricula'] = 'Já existe um aluno cadastrado com esta matrícula.';
        }

        return $errors;
    }

    private function formData($formState, $fallback = array())
    {
        return array(
            'errors' => isset($formState['errors']) && is_array($formState['errors'])
                ? $formState['errors']
                : array(),
            'old' => isset($formState['old']) && is_array($formState['old'])
                ? $formState['old']
                : $fallback,
            'formError' => isset($formState['formError'])
                ? (string) $formState['formError']
                : null
        );
    }

    private function pullFormState($key)
    {
        $formState = array();

        if (isset($_SESSION[$key]) && is_array($_SESSION[$key])) {
            $formState = $_SESSION[$key];
        }

        unset($_SESSION[$key]);

        return $formState;
    }

    private function pullFlash()
    {
        $flash = null;

        if (isset($_SESSION[self::FLASH_KEY]) && is_array($_SESSION[self::FLASH_KEY])) {
            $flash = $_SESSION[self::FLASH_KEY];
        }

        unset($_SESSION[self::FLASH_KEY]);

        return $flash;
    }

    private function setFlash($type, $message)
    {
        $_SESSION[self::FLASH_KEY] = array(
            'type' => $type === 'error' ? 'error' : 'success',
            'message' => $message
        );
    }

    private function redirectToCreate($errors, $old, $formError = null)
    {
        $_SESSION[self::CREATE_FORM_KEY] = array(
            'errors' => $errors,
            'old' => $old,
            'formError' => $formError
        );

        $this->redirect('/alunos/criar', 303);
    }

    private function redirectToEdit($id, $errors, $old, $formError = null)
    {
        if (!isset($_SESSION[self::EDIT_FORM_KEY]) || !is_array($_SESSION[self::EDIT_FORM_KEY])) {
            $_SESSION[self::EDIT_FORM_KEY] = array();
        }

        $_SESSION[self::EDIT_FORM_KEY][(string) (int) $id] = array(
            'errors' => $errors,
            'old' => $old,
            'formError' => $formError
        );

        $this->redirect('/alunos/' . (int) $id . '/editar', 303);
    }

    private function pullEditFormState($id)
    {
        $key = (string) (int) $id;
        $formState = array();

        if (
            isset($_SESSION[self::EDIT_FORM_KEY][$key])
            && is_array($_SESSION[self::EDIT_FORM_KEY][$key])
        ) {
            $formState = $_SESSION[self::EDIT_FORM_KEY][$key];
        }

        unset($_SESSION[self::EDIT_FORM_KEY][$key]);

        if (isset($_SESSION[self::EDIT_FORM_KEY]) && empty($_SESSION[self::EDIT_FORM_KEY])) {
            unset($_SESSION[self::EDIT_FORM_KEY]);
        }

        return $formState;
    }

    private function requireValidId($id, $afterPost)
    {
        $validatedId = filter_var(
            $id,
            FILTER_VALIDATE_INT,
            array('options' => array('min_range' => 1))
        );

        if ($validatedId === false) {
            $this->redirectNotFound($afterPost);
        }

        return (int) $validatedId;
    }

    private function redirectNotFound($afterPost)
    {
        $this->setFlash('error', 'Aluno não encontrado.');
        $this->redirect('/alunos', $afterPost ? 303 : 302);
    }

    private function hasValidCsrfToken()
    {
        $token = isset($_POST['_token']) && is_string($_POST['_token'])
            ? $_POST['_token']
            : null;

        return csrfIsValid($token);
    }
}
