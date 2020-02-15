<?php


class EmailModel
{
    private $data;
    private $messageConfig;
    protected $additional_conf;

    public function __construct()
    {
        $this->messageConfig = include "../config/confirmMessageConfig.php";
        $this->additional_conf = include "../config/additionalConfig.php";
    }

    /**
     * @param \RedBeanPHP\OODBBean $data
     * @throws NotFoundException
     */
    public function init(\RedBeanPHP\OODBBean $data)
    {
        $this->data = $data;
        $this->prepareSending();
    }

    private function prepareSending()
    {
        $httpDomain = $this->additional_conf["protocol"] . "://" . $_SERVER['HTTP_HOST'] . "/";
        $activateLink = $this->getActivateLink($httpDomain);
        $messageTemplate = $this->getMessageTemplate($activateLink);

        $this->sendEmail($messageTemplate);

        CommonController::sendJSONResponse(true, "200", "cem", [], $this->data["cookie_hash"]);
    }


    //////////////////////////////////////////
    /////////   Generate text   //////////////
    //////////////////////////////////////////

    /**
     * @param String $httpDomain
     * @return string
     */
    private function getActivateLink(String $httpDomain): string
    {
        return $httpDomain . "activate?token=" . $this->data["confirm_token"];
    }

    /**
     * @param String $linktext
     * @return string
     */
    private function getMessageTemplate(String $linktext): string
    {
        $template = '
            <!DOCTYPE html>
            <html lang="ru">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>' . $this->messageConfig["heading"] . '</title>
            </head>
            <body>
                ' . $this->messageConfig["message"] . '
                <br/>
                <br/>
                <a href="' . $linktext . '">' . $linktext . '</a>
            </body>
            </html>
        ';

        return $template;
    }

    private function sendEmail(String $mailText)
    {
        $email = new MallerService();
        $email->send($this->data["email"], $mailText);
    }
}