<?php

class AuthController extends Controller
{
    public function login($errors = array(), $old = array())
    {
        $this->view('auth.login', array(
            'title' => 'Login',
            'errors' => $errors,
            'old' => $old
        ));
    }

    public function authenticate()
    {
        $email = isset($_POST['email']) && is_string($_POST['email'])
            ? trim($_POST['email'])
            : '';
        $senha = isset($_POST['senha']) && is_string($_POST['senha'])
            ? $_POST['senha']
            : '';
        $errors = array();

        if ($email == '') {
            $errors['email'] = 'Informe o e-mail.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Informe um e-mail válido.';
        }

        if ($senha == '') {
            $errors['senha'] = 'Informe a senha.';
        }

        if (!empty($errors)) {
            $this->login($errors, array('email' => $email));
            return;
        }

        session_regenerate_id(true);
        $_SESSION['usuario'] = array(
            'nome' => 'Administrador',
            'email' => $email,
            'perfil' => 'Administrador'
        );

        $this->redirect('/dashboard');
    }

    public function logout()
    {
        $_SESSION = array();

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        $this->redirect('/login');
    }
}
