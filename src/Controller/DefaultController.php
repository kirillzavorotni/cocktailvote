<?php


class DefaultController extends CommonController
{
    public $html;

    public function __construct()
    {
        $this->html = include "../public/index.html";
    }

    public function indexAction()
    {
//        setcookie("id", hash("sha256", "qwerty"), time() + 1500, "/");
        echo $this->html;
    }
}