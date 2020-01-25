<?php


class AuthController extends AbstractController
{
    public function authAction()
    {
        var_dump($this->getRequestContent());
    }

    public function validateAction()
    {
        echo 'validate';
    }
}