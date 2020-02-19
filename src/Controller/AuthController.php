<?php


class AuthController extends CommonController
{

    private $additional_conf;

    public function __construct()
    {
        $this->additional_conf = include "../config/additionalConfig.php";
    }

    /**
     * @throws NotFoundException
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

    /**
     * @throws NotFoundException
     */
    public function validateAction()
    {
        if (!CommonController::isCookieRequestValidate()) {
            CommonController::setCookie();
            throw new NotFoundException();
        }

        $validate = new ValidateModel();
        $validate->init();
    }

    public function activateAction()
    {
        $activateToken = CommonController::checkGetActivateToken();

        if (!$activateToken) {
            header('Location: ' . $this->additional_conf["protocol"] . '://' . $_SERVER['HTTP_HOST']);
            exit;
        }

        $activate = new ActivateModel();
        $activate->init($activateToken);
    }
}