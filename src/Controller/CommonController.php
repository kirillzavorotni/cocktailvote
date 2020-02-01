<?php


class CommonController
{
    static private $timeConfig;

    static public function beforeAction()
    {

    }

    public function getRequestContent()
    {
        $postData = file_get_contents('php://input');
        $content = json_decode($postData, true);
        return $content;
    }

    static public function checkTimeInterval()
    {
        self::$timeConfig = include "../config/permittedDate.php";

        $times = [
            date_create(gmdate("Y-m-d H:i:s", strtotime(self::$timeConfig[0] . self::$timeConfig[2]))),
            date_create(gmdate("Y-m-d H:i:s", strtotime(self::$timeConfig[1] . self::$timeConfig[2])))
        ];

        $currentDate = date_create(gmdate("Y-m-d H:i:s", strtotime(self::$timeConfig[2])));

        if ($currentDate < $times[0] || $currentDate > $times[1]) {
            throw new NotVoteAccessException();
        }
    }
}