<?php

class AuthController extends Controller
{
    private const FORM_KEY = 'auth_login_form';
    private const DUMMY_HASH = '$2y$12$dFIs8DvS.Ro.p.7iqhL7reFVcHQpjANC4UB4uqef11BiCoe1Bj3CC';

    public function login()
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }

        $formState = $this->pullFormState();

        $this->view('auth.login', array(
            'title' => 'Entrar',
            'errors' => isset($formState['errors']) && is_array($formState['errors'])
                ? $formState['errors']
                : array(),
            'old' => isset($formState['old']) && is_array($formState['old'])
                ? $formState['old']
                : array(),
            'formError' => isset($formState['formError'])
                ? (string) $formState['formError']
                : null,
            'flash' => Auth::pullFlash()
        ));
    }

    public function authenticate()
    {
        if (Auth::check()) {
            $this->redirect('/dashboard', 303);
        }

        $email = isset($_POST['email']) && is_string($_POST['email'])
            ? strtolower(trim($_POST['email']))
            : '';
        $senha = isset($_POST['senha']) && is_string($_POST['senha'])
            ? $_POST['senha']
            : '';
        $errors = $this->validate($email, $senha);

        if (!$this->hasValidCsrfToken()) {
            $this->redirectToLogin(
                array(),
                $email,
                'Sua sessão expirou. Recarregue o formulário e tente novamente.'
            );
        }

        if (!empty($errors)) {
            $this->redirectToLogin($errors, $email);
        }

        try {
            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->buscarPorEmail($email);
            $hash = $usuario !== null
                && isset($usuario['senha_hash'])
                && is_string($usuario['senha_hash'])
                ? $usuario['senha_hash']
                : self::DUMMY_HASH;
            $senhaValida = password_verify($senha, $hash);

            if (
                $usuario === null
                || !$senhaValida
                || !isset($usuario['ativo'])
                || (int) $usuario['ativo'] !== 1
                || !Usuario::perfilValido($usuario['perfil'] ?? null)
            ) {
                $this->redirectToLogin(
                    array(),
                    $email,
                    'E-mail ou senha inválidos.'
                );
            }

            $destination = Auth::pullIntended('/dashboard');
            $this->rehashPasswordIfNeeded($usuarioModel, $usuario, $senha, $hash);
            Auth::login($usuario);
        } catch (Throwable $exception) {
            error_log('[AuthController::authenticate] ' . $exception->getMessage());
            $this->redirectToLogin(
                array(),
                $email,
                'Não foi possível entrar agora. Verifique o banco de dados e tente novamente.'
            );
        }

        $this->redirect($destination, 303);
    }

    public function logout()
    {
        if (!$this->hasValidCsrfToken()) {
            Auth::setFlash('error', 'Não foi possível encerrar a sessão. Tente novamente.');
            $this->redirect('/dashboard', 303);
        }

        Auth::logout();
        Auth::setFlash('success', 'Sessão encerrada com sucesso.');
        $this->redirect('/login', 303);
    }

    private function validate($email, $senha)
    {
        $errors = array();

        if ($email === '') {
            $errors['email'] = 'Informe o e-mail.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Informe um e-mail válido.';
        } elseif (strlen($email) > 150) {
            $errors['email'] = 'O e-mail deve ter no máximo 150 caracteres.';
        }

        if ($senha === '') {
            $errors['senha'] = 'Informe a senha.';
        } elseif (strlen($senha) > 255) {
            $errors['senha'] = 'A senha informada é muito longa.';
        }

        return $errors;
    }

    private function rehashPasswordIfNeeded($usuarioModel, $usuario, $senha, $hash)
    {
        if (!password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            return;
        }

        $newHash = password_hash($senha, PASSWORD_DEFAULT);

        if (!is_string($newHash)) {
            return;
        }

        try {
            $usuarioModel->atualizarSenha((int) $usuario['id'], $newHash);
        } catch (Throwable $exception) {
            error_log('[AuthController::rehashPasswordIfNeeded] ' . $exception->getMessage());
        }
    }

    private function hasValidCsrfToken()
    {
        $token = isset($_POST['_token']) && is_string($_POST['_token'])
            ? $_POST['_token']
            : null;

        return csrfIsValid($token);
    }

    private function redirectToLogin($errors, $email, $formError = null)
    {
        $_SESSION[self::FORM_KEY] = array(
            'errors' => $errors,
            'old' => array('email' => $email),
            'formError' => $formError
        );

        $this->redirect('/login', 303);
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
}
