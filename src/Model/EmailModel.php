<?php


class EmailModel extends CommonModel
{
    private $data;
    private $messageConfig;
//    private $messageTemplate;

    public function __construct()
    {
        $this->messageConfig = include "../config/confirmMessageConfig.php";
//        $this->messageTemplate = include "../public/templates/emailTemplate.php";
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
//        $httpDomain = $this->additional_conf["protocol"] . "://" . $_SERVER['HTTP_HOST'] . "/";
        $httpDomain = "http://" . $_SERVER['HTTP_HOST'] . "/";
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

        $template = "
            <!DOCTYPE html>
            <html lang=\"ru\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
                <title>Подтверждение голосования Margaritaweek</title>
            </head>
            <body>
                Спасибо что приняли участие в голосовании. Для подтверждения вашего выбора, пожалуйста, перейдите по ссылке.
                <br/>
                <br/>
                <a href=\"{$linktext}\">{$linktext }</a>
            </body>
            </html>";

        return $template;

    }



    private function sendEmail(String $mailText)
    {
        $email = new MallerService();
        $email->send($this->data["email"], $mailText);
    }
}