<?php


class CommonController
{
    static private $timeConfig;
    static private $messages;

    static private $defaultControllerName = "DefaultController";

    public function getRequestContent()
    {
        $postData = file_get_contents('php://input');
        $content = json_decode($postData, true);
        return $content;
    }

    /**
     * @param $controllerClass
     */
    static public function checkTimeInterval(String $controllerClass)
    {
        self::$timeConfig = include "../config/permittedDate.php";
        self::$messages = include "../config/messages.php";
//        $times = [
//            date_create(gmdate("Y-m-d H:i:s", strtotime(self::$timeConfig[0] . self::$timeConfig[2]))),
//            date_create(gmdate("Y-m-d H:i:s", strtotime(self::$timeConfig[1] . self::$timeConfig[2])))
//        ];
//
//        $currentDate = date_create(gmdate("Y-m-d H:i:s", strtotime(self::$timeConfig[2])));
//
//        if ($currentDate < $times[0] || $currentDate > $times[1]) {
//            throw new NotVoteAccessException();
//        }
        $currentDate = date("Y-m-d H:i:s");

        if ($controllerClass !== self::$defaultControllerName && $currentDate < self::$timeConfig[0] || $currentDate > self::$timeConfig[1]) {
            self::sendJSONResponse(false, "403", "nva");
        }
    }

    static public function isEmailRequestValidate(Array $data)
    {
        return (is_array($data) && isset($data["email"]) && filter_var($data["email"], FILTER_VALIDATE_EMAIL)) ? true : false;
    }

    static public function getResponseMessage(String $code, String $errCode)
    {
        self::$messages = include "../config/messages.php";

        if (!isset(self::$messages[$code]) || !isset(self::$messages[$code][$errCode])) {
            return "Something went wrong";
        }

        return self::$messages[$code][$errCode];
    }

    /**
     * @param bool $status
     * @param String $code
     * @param String $errCode
     * @param array $data
     */
    static public function sendJSONResponse(Bool $status, String $code, String $errCode, Array $data = [])
    {
        $res = json_encode([
            "status" => $status,
            "errorCode" => $errCode,
            "msg" => self::getResponseMessage($code, $errCode),
            "data" => $data
        ]);
        header("Content-type: application/json; charset=UTF-8");
        header("Content-Length: " . strlen($res));
        http_response_code($code);
        echo $res;
        die;
    }

    static public function setCookie()
    {

    }
}