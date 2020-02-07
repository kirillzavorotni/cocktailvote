<?php


class CommonController
{
    static private $timeConfig;
    static private $messages;

    static private $defaultControllerName = "DefaultController";
    static private $indexActionName = "indexAction";

    public function getRequestContent()
    {
        $postData = file_get_contents('php://input');
        $content = json_decode($postData, true);
        return $content;
    }

    /**
     * @param String $controllerClass
     * @param String $actionName
     */
    static public function checkTimeInterval(String $controllerClass, String $actionName)
    {
        self::$timeConfig = include "../config/permittedDate.php";
        self::$messages = include "../config/messages.php";

        $currentDate = date("Y-m-d H:i:s");

        if (
            ($currentDate < self::$timeConfig[0] || $currentDate > self::$timeConfig[1]) &&
            ($controllerClass !== self::$defaultControllerName && $actionName !== self::$indexActionName)
        ) {
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


    static public function sendJSONResponse(Bool $status, String $code, String $msgCode, Array $data = [], String $cookieId = null, Bool $changeCookie = true)
    {
        $res = json_encode([
            "status" => $status,
            "msgCode" => $msgCode,
            "msg" => self::getResponseMessage($code, $msgCode),
            "data" => $data
        ]);

        self::setCookie($cookieId, $changeCookie);

        header("Content-type: application/json; charset=UTF-8");
        header("Content-Length: " . strlen($res));
        http_response_code($code);
        echo $res;
        die;
    }

    static public function setCookie($cookieId = null, $changeCookie = true)
    {
        if (!isset($cookieId) && $changeCookie) {
            setcookie("id", "", time() - 3600 * 24 * 365 * 10, "/");
        }

        if (isset($cookieId)) {
            setcookie("id", $cookieId, time() + 3600 * 24 * 365 * 10, "/");
        }
    }

    /**
     * @return bool
     */
    static public function isCookieRequestValidate(): bool
    {
        if (isset($_COOKIE) && isset($_COOKIE["id"]) && strlen($_COOKIE["id"]) > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $data
     * @return bool
     */
    static public function isVoteDataRequestValidate($data): bool
    {
        if (isset($data) && count($data) === 1 && isset($data["product_id"])) {
            return true;
        }
        return false;
    }
}