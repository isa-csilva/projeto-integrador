<?php

class Controller
{
    protected function view($view, $data = array())
    {
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';

        if (!is_file($viewFile)) {
            error_log('[Controller] View não encontrada: ' . $viewFile);
            http_response_code(500);
            $viewFile = VIEW_PATH . '/errors/500.php';
            $data = array('title' => 'Erro interno');

            if (!is_file($viewFile)) {
                echo 'Não foi possível exibir esta página.';
                return;
            }
        }

        extract($data, EXTR_SKIP);
        require VIEW_PATH . '/layouts/main.php';
    }

    protected function redirect($path, $status = 302)
    {
        header('Location: ' . url($path), true, $status);
        exit;
    }
}
