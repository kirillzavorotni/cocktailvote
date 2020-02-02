<?php


class AuthController extends CommonController
{
    /**
     * file messages.php contains (errCode, code) messages for response
     */
    public function authAction()
    {
        if (!CommonController::isEmailRequestValidate($this->getRequestContent())) {
            CommonController::sendJSONResponse(false, "400", "enc");
        }



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