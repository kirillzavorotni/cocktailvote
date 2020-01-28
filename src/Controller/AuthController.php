<?php


class AuthController extends CommonController
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