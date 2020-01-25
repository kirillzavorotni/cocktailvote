<?php


abstract class AbstractController
{
    static public function beforeAction()
    {

    }

    public function getRequestContent()
    {
        $postData = file_get_contents('php://input');
        $content = json_decode($postData, true);
        return $content;
    }
}