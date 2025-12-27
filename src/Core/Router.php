<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    protected function addRoute($method, $path, $handler)
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Normalize URI (remove trailing slash unless it's root)
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            $this->callAction($handler);
        } else {
            // 404 Not Found
            http_response_code(404);
            echo "404 - Not Found";
        }
    }

    protected function callAction($handler)
    {
        list($controllerName, $action) = explode('@', $handler);
        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                die("Method {$action} not found in controller {$controllerName}");
            }
        } else {
            die("Controller {$controllerName} not found");
        }
    }
}
