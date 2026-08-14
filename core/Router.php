<?php

class Router
{
    private $routes = array();

    public function get($path, $handler, $middleware = array())
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post($path, $handler, $middleware = array())
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    private function add($method, $path, $handler, $middleware)
    {
        if (!is_array($middleware)) {
            throw new InvalidArgumentException('A lista de middleware da rota deve ser um array.');
        }

        $this->routes[] = array(
            'method' => strtoupper($method),
            'path' => $this->formatPath($path),
            'handler' => $handler,
            'middleware' => $middleware
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

            if (!$this->authorize($route['middleware'], $method, $path)) {
                return;
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

    private function authorize($middleware, $method, $path)
    {
        $requiresAuthentication = in_array('auth', $middleware, true);
        $allowedProfiles = array();

        foreach ($middleware as $rule) {
            if (!is_string($rule)) {
                throw new RuntimeException('Middleware de rota inválido.');
            }

            if (strpos($rule, 'perfil:') !== 0) {
                continue;
            }

            $requiresAuthentication = true;
            $profiles = explode(',', substr($rule, strlen('perfil:')));

            foreach ($profiles as $profile) {
                $profile = trim($profile);

                if ($profile !== '') {
                    $allowedProfiles[] = $profile;
                }
            }
        }

        if (!$requiresAuthentication) {
            return true;
        }

        if (!Auth::check()) {
            if ($method === 'GET') {
                Auth::rememberIntended($path);
            }

            Auth::setFlash('error', 'Faça login para continuar.', false);
            $status = $method === 'GET' ? 302 : 303;
            http_response_code($status);

            if (!headers_sent()) {
                header('Location: ' . url('/login'), true, $status);
            }

            return false;
        }

        $allowedProfiles = array_values(array_unique($allowedProfiles));

        if (!empty($allowedProfiles) && !Auth::hasAnyProfile($allowedProfiles)) {
            http_response_code(403);
            $controller = new ErrorController();
            $controller->forbidden();

            return false;
        }

        return true;
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
