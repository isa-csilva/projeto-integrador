<?php

class Database
{
    private static $connection = null;

    public static function getConnection()
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = require ROOT_PATH . '/config/database.php';
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $config['host'],
            $config['port'],
            $config['name']
        );

        try {
            self::$connection = new PDO(
                $dsn,
                $config['user'],
                $config['pass'],
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch (PDOException $exception) {
            error_log('[Database] Falha na conexão: ' . $exception->getMessage());
            throw new RuntimeException('Não foi possível conectar ao banco de dados.');
        }

        return self::$connection;
    }
}
