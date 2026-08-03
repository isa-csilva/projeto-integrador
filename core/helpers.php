<?php

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url($path = '/')
{
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    $path = '/' . ltrim((string) $path, '/');

    if ($basePath == '' || $basePath == '.') {
        return $path;
    }

    if ($path == '/') {
        return $basePath . '/';
    }

    return $basePath . $path;
}

function isActive($path)
{
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

    if (
        $basePath !== ''
        && $basePath !== '/'
        && ($currentPath === $basePath || strpos($currentPath, $basePath . '/') === 0)
    ) {
        $currentPath = substr($currentPath, strlen($basePath));
    }

    if ($currentPath == '') {
        $currentPath = '/';
    }

    if ($path === '/') {
        return $currentPath === '/' ? 'active' : '';
    }

    return $currentPath === $path || strpos($currentPath, rtrim($path, '/') . '/') === 0
        ? 'active'
        : '';
}
