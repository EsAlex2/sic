<?php
/**
 * Mini-Router HTTP para el SIC
 * Soporta GET/POST, parámetros dinámicos {id}, y middleware por ruta
 */
class Router
{
    private array $routes = [];
    private static ?Router $instance = null;

    public static function getInstance(): Router
    {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    /**
     * Registra una ruta GET
     */
    public function get(string $path, array $callback, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $callback, $middleware);
    }

    /**
     * Registra una ruta POST
     */
    public function post(string $path, array $callback, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $callback, $middleware);
    }

    private function addRoute(string $method, string $path, array $callback, array $middleware): void
    {
        $this->routes[] = [
            'method'     => $method,
            'path'       => '/' . trim($path, '/'),
            'controller' => $callback[0],
            'action'     => $callback[1],
            'middleware'  => $middleware,
        ];
    }

    /**
     * Despacha la petición actual a la ruta correspondiente
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->matchRoute($route['path'], $uri);

            if ($params !== false) {
                // Ejecutar middleware
                foreach ($route['middleware'] as $mw) {
                    if (is_array($mw)) {
                        call_user_func($mw);
                    } elseif (is_string($mw) && is_callable($mw)) {
                        call_user_func($mw);
                    }
                }

                // Cargar controlador
                $controllerFile = ROOT_PATH . '/controllers/' . $route['controller'] . '.php';
                if (!file_exists($controllerFile)) {
                    $this->error404("Controlador no encontrado: {$route['controller']}");
                    return;
                }

                require_once $controllerFile;

                $controllerClass = $route['controller'];
                $action = $route['action'];

                if (!class_exists($controllerClass)) {
                    $this->error404("Clase no encontrada: {$controllerClass}");
                    return;
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $action)) {
                    $this->error404("Método no encontrado: {$controllerClass}::{$action}");
                    return;
                }

                call_user_func_array([$controller, $action], array_values($params));
                return;
            }
        }

        $this->error404();
    }

    /**
     * Obtiene la URI relativa al URL_BASE
     */
    private function getUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = URL_BASE;

        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = '/' . trim($uri, '/');
        return $uri === '/' ? '/' : $uri;
    }

    /**
     * Intenta hacer match de una ruta con la URI actual
     * Retorna array de parámetros o false si no coincide
     */
    private function matchRoute(string $routePath, string $requestUri): array|false
    {
        $routeParts = $routePath === '/' ? [''] : explode('/', trim($routePath, '/'));
        $uriParts = $requestUri === '/' ? [''] : explode('/', trim($requestUri, '/'));

        if (count($routeParts) !== count($uriParts)) {
            return false;
        }

        $params = [];

        for ($i = 0; $i < count($routeParts); $i++) {
            if (preg_match('/^\{(\w+)\}$/', $routeParts[$i], $matches)) {
                $params[$matches[1]] = urldecode($uriParts[$i]);
            } elseif ($routeParts[$i] !== $uriParts[$i]) {
                return false;
            }
        }

        return $params;
    }

    /**
     * Muestra página 404
     */
    private function error404(string $message = 'Página no encontrada'): void
    {
        http_response_code(404);
        if (file_exists(VIEWS_PATH . '/errors/404.php')) {
            renderViewNoLayout('errors/404', ['message' => $message]);
        } else {
            echo "<h1>404</h1><p>{$message}</p>";
        }
    }
}
