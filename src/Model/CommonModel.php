<?php


class CommonModel
{
    public $db_conf;
    protected $additional_conf;

    protected function __construct()
    {
        $this->db_conf = include "../config/db_conf.php";
        $this->additional_conf = include "../config/additionalConfig.php";

        R::setup($this->db_conf["dsn"], $this->db_conf["user"], $this->db_conf["pass"]);
    }

    /**
     * @return false|string
     */
    static function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(50));
    }

    /**
     * @return string
     */
    static function generateCookieHash()
    {
        return hash("sha256", bin2hex(openssl_random_pseudo_bytes(20)));
    }
}