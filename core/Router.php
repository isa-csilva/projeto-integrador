<?php

class Router
{
    private $routes = array();

    public function get($path, $handler)
    {
        $this->add('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->add('POST', $path, $handler);
    }

    private function add($method, $path, $handler)
    {
        $this->routes[] = array(
            'method' => strtoupper($method),
            'path' => $this->formatPath($path),
            'handler' => $handler
        );
    }

    public function dispatch($method, $uri, $scriptName)
    {
        $path = $this->getPath($uri, $scriptName);
        $pathFound = false;
        $allowedMethods = array();
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            $params = $this->match($route['path'], $path);

            if ($params === false) {
                continue;
            }

            $pathFound = true;
            $allowedMethods[] = $route['method'];

            if ($route['method'] !== $method) {
                continue;
            }

            $this->run($route['handler'], $params);
            return;
        }

        if ($pathFound) {
            http_response_code(405);
            $allowedMethods = array_values(array_unique($allowedMethods));

            if (!headers_sent()) {
                header('Allow: ' . implode(', ', $allowedMethods));
            }

            $controller = new ErrorController();
            $controller->methodNotAllowed();
            return;
        }

        http_response_code(404);
        $controller = new ErrorController();
        $controller->notFound();
    }

    private function run($handler, $params)
    {
        if (!is_array($handler) || count($handler) !== 2) {
            throw new RuntimeException('Configuração de rota inválida.');
        }

        $controllerName = $handler[0];
        $methodName = $handler[1];

        if (!class_exists($controllerName)) {
            throw new RuntimeException('Controller de rota não encontrado.');
        }

        $controller = new $controllerName();

        if (!is_callable(array($controller, $methodName))) {
            throw new RuntimeException('Ação de rota não encontrada.');
        }

        call_user_func_array(array($controller, $methodName), $params);
    }

    private function getPath($uri, $scriptName)
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

        if (
            $basePath !== ''
            && $basePath !== '/'
            && ($path === $basePath || strpos($path, $basePath . '/') === 0)
        ) {
            $path = substr($path, strlen($basePath));
        }

        return $this->formatPath($path);
    }

    private function formatPath($path)
    {
        $path = '/' . trim($path, '/');

        return $path;
    }

    private function match($routePath, $requestPath)
    {
        if (strpos($routePath, '{id}') === false) {
            return $routePath == $requestPath ? array() : false;
        }

        $pattern = str_replace('{id}', '([0-9]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestPath, $matches)) {
            return array($matches[1]);
        }

        return false;
    }
}
