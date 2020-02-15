<?php


class CommonModel
{
    public $db_conf;
    protected $additional_conf;

    protected function __construct()
    {
        $this->db_conf = include "../config/db_conf.php";
        $this->additional_conf = include "../config/additionalConfig.php";

        if (!R::testConnection()) {
            R::setup($this->db_conf["dsn"], $this->db_conf["user"], $this->db_conf["pass"]);
        }
    }

    /**
     * @return false|string
     */
    protected function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(50));
    }

    /**
     * @return string
     */
    protected function generateCookieHash()
    {
        return hash("sha256", bin2hex(openssl_random_pseudo_bytes(20)));
    }

    /**
     * @param \RedBeanPHP\OODBBean $user
     * @return bool|mixed
     */
    protected function getConfirmedStatusFromUser(\RedBeanPHP\OODBBean $user): bool
    {
        return $user['confirm_status'] ? $user['confirm_status'] : false;
    }

    /**
     * @param \RedBeanPHP\OODBBean $user
     * @return string
     */
    protected function getCookieHashFromUser(\RedBeanPHP\OODBBean $user): string
    {
        return $user['cookie_hash'] ? $user['cookie_hash'] : '';
    }

    /**
     * @param \RedBeanPHP\OODBBean $user
     * @return int
     */
    protected function getLeftVoteCount(\RedBeanPHP\OODBBean $user): int
    {
        return R::count( 'vote', 'user_id = ?', [$user["id"]]);
    }

    /**
     * @return NULL|\RedBeanPHP\OODBBean
     */
    protected function findUserByCookie()
    {
        return R::findOne('user', 'cookie_hash = ?', [$_COOKIE["id"]]);
    }
}