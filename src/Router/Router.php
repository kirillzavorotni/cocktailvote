<?php

require_once '../src/Exception/NotFoundException.php';

class Router
{

    private $routes;
    private $db_conf;

    public function __construct()
    {
        $this->routes = include "../config/routes.php";
        $this->db_conf = include "../config/db_conf.php";

        R::setup( $this->db_conf["dsn"], $this->db_conf["user"], $this->db_conf["pass"]);
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