<?php


class EmailModel
{
    private $data;
    private $messageConfig;

    public function __construct()
    {
        $this->messageConfig = include "../config/confirmMessageConfig.php";
    }

    public function init($data)
    {
        $this->data = $data;
        $this->prepareSending();
    }

    private function prepareSending()
    {
        $httpDomain = $_SERVER["HTTP_REFERER"];
        $activateLink = $this->getActivateLink($httpDomain);

        $messageTemplate = $this->getMessageTemplate($activateLink);
    }

    private function getActivateLink(String $httpDomain): string
    {
        return $httpDomain . "activate?token=" . $this->data["confirm_token"];
    }

    private function getMessageTemplate(String $linktext): string
    {
        return $this->messageConfig["message"] . "<br><br>" . "<a href='" . $linktext . "'>Click here</a>" ;
    }

    private function sendEmail(String $mailText)
    {

    }
}