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
        echo $this->html;
    }
}