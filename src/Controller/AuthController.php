<?php


class AuthController extends CommonController
{
    /**
     * file messages.php contains (errCode, code) messages for response
     */
    public function authAction()
    {
        $data = $this->getRequestContent();
        if (!CommonController::isEmailRequestValidate($data)) {
            CommonController::sendJSONResponse(false, "400", "enc");
        }

        $user = new UserModel();
        $user->init($data);
    }

    public function validateAction()
    {
//        $db_conf = include "../config/db_conf.php";
//        R::setup($this->db_conf["dsn"], $this->db_conf["user"], $this->db_conf["pass"]);
//
//        $result = R::load('user', )
    }
}