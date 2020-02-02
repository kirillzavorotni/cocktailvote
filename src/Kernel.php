<?php


class Kernel
{
    private $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    /**
     * @throws NotFoundException
     * @throws NotVoteAccessException
     */
    public function handleRequest() {

        list($controllerClass, $actionName) = $this->router->getControllerAction(); // get controller name and method of it
        $controller = new $controllerClass();

        CommonController::checkTimeInterval($controllerClass);

        $controller->$actionName();
    }
}