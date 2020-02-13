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
            header('Location: http://' . $_SERVER['HTTP_HOST']);
            exit;
        }

        $activate = new ActivateModel();
        $activate->init($activateToken);
    }
}