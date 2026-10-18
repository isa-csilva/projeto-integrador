<?php

ini_set('display_errors', '0');
ini_set('log_errors', '1');

// O diretório privado é bloqueado pelo .htaccess e nunca deve ser acessado por URL.
define('ROOT_PATH', __DIR__ . '/private');
require ROOT_PATH . '/front-controller.php';
