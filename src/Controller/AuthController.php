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



//        kirill@mail.com

//        $user  = R::findOne( 'user', ' email = ? ', [ 'kirill@mail.com' ] );
//        $user = R::dispense( 'user' );
//        $user->email = 'sssss@mail.com';
//        $user->cookie_hash = 'tetertertert';
//        $user->confirm_token = 'vbvbvbvbvb';
//        $id = R::store( $user );
//
//        var_dump($id);
    }

    public function validateAction()
    {
        echo 'validate';
    }
}