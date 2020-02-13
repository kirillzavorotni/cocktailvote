<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../lib/mailer/src/Exception.php';
require_once '../lib/mailer/src/PHPMailer.php';
require_once '../lib/mailer/src/SMTP.php';


class MallerService
{
    private $mail;
    private $message;

    public function __construct()
    {
        $this->message = include "../config/confirmMessageConfig.php";
        $this->mail = new PHPMailer(true);

    }

    /**
     * @param $to
     * @param $content
     */
    public function send($to, $content)
    {

        var_dump($content);
        die;
        try {
            $this->mail->SMTPDebug = false;
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.spaceweb.ru';
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = 'noreply@altosmargaritaweek.by';
            $this->mail->Password   = 'electroC88';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port       = 465;

            //Recipients
            $this->mail->setFrom('noreply@altosmargaritaweek.by', 'altosmargaritaweek.by');
            $this->mail->addAddress($to);

            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = $this->message["heading"];
            $this->mail->Body = $content;

            $this->mail->send();

        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }

}

