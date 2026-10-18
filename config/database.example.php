<?php

// Copie como database.local.php somente no ambiente que deseja configurar.
// No pacote InfinityFree: htdocs/private/config/database.local.php.
if (!defined('ROOT_PATH')) {
    http_response_code(404);
    exit;
}

return array(
    'host' => 'sqlXXX.infinityfree.com', // MySQL Hostname do painel, não localhost.
    'port' => 3306,
    'name' => 'if0_XXXXXXXX_escolar', // Nome COMPLETO, com prefixo da conta.
    'user' => 'if0_XXXXXXXX',
    'pass' => 'PREENCHA_A_SENHA_MYSQL',
    'ssl_ca' => '' // MySQL interno do InfinityFree; não usa o CA da Aiven.
);
