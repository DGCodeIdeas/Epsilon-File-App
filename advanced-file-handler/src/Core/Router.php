<?php

namespace App\Core;

use Exception;

/**
 * A simple router that maps URIs to controller actions.
 */
class Router
{
    /**
     * The array of registered routes.
     *
     * @var array
     */
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Register a GET route.
     *
     * @param string $uri The URI pattern.
     * @param string $controller The 'Controller@method' action.
     */
    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * Register a POST route.
     *
     * @param string $uri The URI pattern.
     * @param string $controller The 'Controller@method' action.
     */
    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    /**
     * Dispatch the request to the appropriate controller action.
     *
     * @param string $uri The request URI.
     * @param string $method The request method.
     * @return mixed
     * @throws Exception
     */
    public function dispatch($uri, $method)
    {
        if (array_key_exists($uri, $this->routes[$method])) {
            return $this->callAction(
                ...explode('@', $this->routes[$method][$uri])
            );
        }

        // If the route is not found, dispatch to the ErrorController
        return $this->callAction('ErrorController', 'notFound');
    }

    /**
     * Call the given controller action.
     *
     * @param string $controller The name of the controller class.
     * @param string $action The name of the method to call.
     * @return mixed
     * @throws Exception if the controller class or method does not exist.
     */
    protected function callAction($controller, $action)
    {
        // Prepend the namespace to the controller name
        $controller = "App\\Controllers\\{$controller}";

        if (!class_exists($controller)) {
            throw new Exception("Controller class {$controller} not found.");
        }

        $controllerInstance = new $controller;

        if (!method_exists($controllerInstance, $action)) {
            throw new Exception(
                "{$controller} does not respond to the {$action} action."
            );
        }

        return $controllerInstance->$action();
    }
}
