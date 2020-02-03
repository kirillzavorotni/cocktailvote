<?php

require_once '../src/Exception/NotFoundException.php';

class Router
{

    private $routes;
    private $db_conf;

    public function __construct()
    {
        $this->routes = include "../config/routes.php";
    }

    public function getControllerAction()
    {
        $route = $this->cleanUpRoute($_SERVER['REQUEST_URI']);
        $method = $_SERVER['REQUEST_METHOD'];

        $key = $method . " " . $route;

        if (!isset($this->routes[$key])) {
            throw new NotFoundException();
        }

        return $this->routes[$key];
    }

    private function cleanUpRoute($route)
    {
        return explode('?', $route)[0];
    }
}