<?php

class UsuarioController extends Controller
{
    private const FLASH_KEY = 'usuario_flash';
    private const FORM_KEY = 'usuario_create_form';

    public function index()
    {
        $usuarios = array();
        $loadError = null;

        try {
            $usuarioModel = new Usuario();
            $usuarios = $usuarioModel->listarTodos();

            foreach ($usuarios as &$usuario) {
                $usuario['perfil_label'] = Usuario::perfilLabel($usuario['perfil'] ?? null);
                $usuario['status_label'] = isset($usuario['ativo']) && (int) $usuario['ativo'] === 1
                    ? 'Ativo'
                    : 'Inativo';
            }
            unset($usuario);
        } catch (Throwable $exception) {
            error_log('[UsuarioController::index] ' . $exception->getMessage());
            $loadError = 'Não foi possível carregar os usuários. Verifique o banco de dados e tente novamente.';
        }

        $this->view('usuarios.index', array(
            'title' => 'Usuários',
            'usuarios' => $usuarios,
            'flash' => $this->pullFlash(),
            'loadError' => $loadError
        ));
    }

    public function create()
    {
        $formState = $this->pullFormState();

        $this->view('usuarios.create', array(
            'title' => 'Novo usuário',
            'errors' => isset($formState['errors']) && is_array($formState['errors'])
                ? $formState['errors']
                : array(),
            'old' => isset($formState['old']) && is_array($formState['old'])
                ? $formState['old']
                : array('perfil' => Usuario::PERFIL_CONSULTA),
            'formError' => isset($formState['formError'])
                ? (string) $formState['formError']
                : null,
            'perfis' => Usuario::perfis()
        ));
    }

    public function store()
    {
        $dados = Usuario::normalizarDados($_POST);
        $errors = Usuario::validarDados($dados);
        $old = array(
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'perfil' => $dados['perfil']
        );

        if (!$this->hasValidCsrfToken()) {
            $this->redirectToForm(
                array(),
                $old,
                'Sua sessão expirou. Recarregue o formulário e tente novamente.'
            );
        }

        if (!empty($errors)) {
            $this->redirectToForm($errors, $old);
        }

        try {
            $usuarioModel = new Usuario();

            if ($usuarioModel->emailExiste($dados['email'])) {
                $this->redirectToForm(
                    array('email' => 'Já existe um usuário cadastrado com este e-mail.'),
                    $old
                );
            }

            $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);

            if (!is_string($senhaHash)) {
                throw new RuntimeException('Não foi possível gerar o hash da senha.');
            }

            if (!$usuarioModel->cadastrar(array(
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'senha_hash' => $senhaHash,
                'perfil' => $dados['perfil']
            ))) {
                throw new RuntimeException('O cadastro do usuário não foi confirmado.');
            }
        } catch (PDOException $exception) {
            error_log('[UsuarioController::store] Falha do PDO: ' . $exception->getMessage());
            $message = (string) $exception->getCode() === '23000'
                ? 'Já existe um usuário cadastrado com este e-mail.'
                : 'Não foi possível cadastrar o usuário agora. Tente novamente em instantes.';
            $this->redirectToForm(array(), $old, $message);
        } catch (Throwable $exception) {
            error_log('[UsuarioController::store] ' . $exception->getMessage());
            $this->redirectToForm(
                array(),
                $old,
                'Não foi possível cadastrar o usuário agora. Verifique o banco de dados e tente novamente.'
            );
        }

        $this->setFlash('success', 'Usuário cadastrado com sucesso.');
        $this->redirect('/usuarios', 303);
    }

    private function hasValidCsrfToken()
    {
        $token = isset($_POST['_token']) && is_string($_POST['_token'])
            ? $_POST['_token']
            : null;

        return csrfIsValid($token);
    }

    private function redirectToForm($errors, $old, $formError = null)
    {
        $_SESSION[self::FORM_KEY] = array(
            'errors' => $errors,
            'old' => $old,
            'formError' => $formError
        );

        $this->redirect('/usuarios/criar', 303);
    }

    private function pullFormState()
    {
        $formState = array();

        if (isset($_SESSION[self::FORM_KEY]) && is_array($_SESSION[self::FORM_KEY])) {
            $formState = $_SESSION[self::FORM_KEY];
        }

        unset($_SESSION[self::FORM_KEY]);

        return $formState;
    }

    private function setFlash($type, $message)
    {
        $_SESSION[self::FLASH_KEY] = array(
            'type' => $type === 'error' ? 'error' : 'success',
            'message' => (string) $message
        );
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
}
