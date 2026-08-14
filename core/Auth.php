<?php

class Auth
{
    private const USER_KEY = 'auth_user';
    private const LAST_ACTIVITY_KEY = 'auth_last_activity';
    private const REGENERATED_AT_KEY = 'auth_regenerated_at';
    private const FLASH_KEY = 'auth_flash';
    private const INTENDED_KEY = 'auth_intended';
    private const DEFAULT_IDLE_TIMEOUT = 1800;
    private const REGENERATE_INTERVAL = 900;

    public static function boot()
    {
        if (!isset($_SESSION[self::USER_KEY])) {
            return;
        }

        if (!self::validUser($_SESSION[self::USER_KEY])) {
            self::forgetUser();
            return;
        }

        $now = time();
        $lastActivity = isset($_SESSION[self::LAST_ACTIVITY_KEY])
            ? (int) $_SESSION[self::LAST_ACTIVITY_KEY]
            : 0;

        if ($lastActivity > 0 && ($now - $lastActivity) > self::idleTimeout()) {
            self::forgetUser();
            self::setFlash('error', 'Sua sessão expirou por inatividade. Entre novamente.');
            return;
        }

        $regeneratedAt = isset($_SESSION[self::REGENERATED_AT_KEY])
            ? (int) $_SESSION[self::REGENERATED_AT_KEY]
            : 0;

        if (
            $regeneratedAt <= 0
            || ($now - $regeneratedAt) >= self::REGENERATE_INTERVAL
        ) {
            self::regenerateSessionId();
            $_SESSION[self::REGENERATED_AT_KEY] = $now;
        }

        $_SESSION[self::LAST_ACTIVITY_KEY] = $now;
    }

    public static function login($usuario)
    {
        if (!self::validDatabaseUser($usuario)) {
            throw new InvalidArgumentException('Usuário inválido para autenticação.');
        }

        $_SESSION = array();
        self::regenerateSessionId();

        $_SESSION[self::USER_KEY] = array(
            'id' => (int) $usuario['id'],
            'nome' => (string) $usuario['nome'],
            'email' => strtolower((string) $usuario['email']),
            'perfil' => (string) $usuario['perfil']
        );
        $_SESSION[self::LAST_ACTIVITY_KEY] = time();
        $_SESSION[self::REGENERATED_AT_KEY] = time();
        unset($_SESSION['csrf_token']);
        csrfToken();
    }

    public static function logout()
    {
        $_SESSION = array();
        self::regenerateSessionId();
        unset($_SESSION['csrf_token']);
        csrfToken();
    }

    public static function check()
    {
        return isset($_SESSION[self::USER_KEY])
            && self::validUser($_SESSION[self::USER_KEY]);
    }

    public static function user()
    {
        return self::check() ? $_SESSION[self::USER_KEY] : null;
    }

    public static function hasAnyProfile($profiles)
    {
        if (!self::check()) {
            return false;
        }

        $profiles = is_array($profiles) ? $profiles : array($profiles);

        return in_array($_SESSION[self::USER_KEY]['perfil'], $profiles, true);
    }

    public static function profileLabel($profile = null)
    {
        if ($profile === null && self::check()) {
            $profile = $_SESSION[self::USER_KEY]['perfil'];
        }

        return Usuario::perfilLabel($profile);
    }

    public static function rememberIntended($path)
    {
        $path = is_string($path) ? parse_url($path, PHP_URL_PATH) : null;

        if (
            !is_string($path)
            || strpos($path, '/') !== 0
            || strpos($path, '//') === 0
        ) {
            return;
        }

        $_SESSION[self::INTENDED_KEY] = $path;
    }

    public static function pullIntended($fallback = '/dashboard')
    {
        $path = isset($_SESSION[self::INTENDED_KEY]) && is_string($_SESSION[self::INTENDED_KEY])
            ? $_SESSION[self::INTENDED_KEY]
            : $fallback;

        unset($_SESSION[self::INTENDED_KEY]);

        if (strpos($path, '/') !== 0 || strpos($path, '//') === 0) {
            return $fallback;
        }

        return $path;
    }

    public static function setFlash($type, $message, $replace = true)
    {
        if (!$replace && isset($_SESSION[self::FLASH_KEY])) {
            return;
        }

        $_SESSION[self::FLASH_KEY] = array(
            'type' => $type === 'success' ? 'success' : 'error',
            'message' => (string) $message
        );
    }

    public static function pullFlash()
    {
        $flash = null;

        if (isset($_SESSION[self::FLASH_KEY]) && is_array($_SESSION[self::FLASH_KEY])) {
            $flash = $_SESSION[self::FLASH_KEY];
        }

        unset($_SESSION[self::FLASH_KEY]);

        return $flash;
    }

    private static function validDatabaseUser($usuario)
    {
        return is_array($usuario)
            && isset($usuario['id'], $usuario['nome'], $usuario['email'], $usuario['perfil'])
            && (int) $usuario['id'] > 0
            && is_string($usuario['nome'])
            && $usuario['nome'] !== ''
            && is_string($usuario['email'])
            && filter_var($usuario['email'], FILTER_VALIDATE_EMAIL)
            && Usuario::perfilValido($usuario['perfil']);
    }

    private static function validUser($usuario)
    {
        return self::validDatabaseUser($usuario)
            && is_int($usuario['id']);
    }

    private static function forgetUser()
    {
        unset(
            $_SESSION[self::USER_KEY],
            $_SESSION[self::LAST_ACTIVITY_KEY],
            $_SESSION[self::REGENERATED_AT_KEY],
            $_SESSION[self::INTENDED_KEY],
            $_SESSION['csrf_token']
        );

        self::regenerateSessionId();
        csrfToken();
    }

    private static function regenerateSessionId()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    private static function idleTimeout()
    {
        $configured = getenv('AUTH_IDLE_TIMEOUT');

        if ($configured !== false && ctype_digit((string) $configured)) {
            $timeout = (int) $configured;

            if ($timeout >= 60) {
                return $timeout;
            }
        }

        return self::DEFAULT_IDLE_TIMEOUT;
    }
}
