<?php


class EmailModel
{
    private $data;
    private $messageConfig;

    public function __construct()
    {
        $this->messageConfig = include "../config/confirmMessageConfig.php";
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
        if (!isset($_SERVER["HTTP_REFERER"])) {
            throw new NotFoundException();
        }

        $httpDomain = $_SERVER["HTTP_REFERER"];
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
        return $this->messageConfig["message"] . "<br><br>" . "<a href='" . $linktext . "'>Click here</a>" ;
    }



    private function sendEmail(String $mailText)
    {
//        var_dump($this->data);
//        echo $mailText;
    }
}