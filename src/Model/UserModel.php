<?php


class UserModel extends CommonModel
{
    private $data;

    public function __construct()
    {
        parent::__construct();
    }

    public function init(Array $data)
    {
        $this->data = $data;

        if (!$this->checkUserInDB()) {
            $this->createNewRecord();
//            $this->sendConfirmEmail();
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    private function checkUserInDB(): bool
    {
        $user = R::findOne( 'user', ' email = ? ', [ $this->data["email"] ] );
        return !isset($user) ? false : true;
    }

    private function createNewRecord()
    {
        $uniqToken = CommonModel::generateToken();
        $cookieHash = CommonModel::generateCookieHash();

        if (isset($uniqToken) && $cookieHash) {
            $returnedUserData = $this->insertUser($this->data["email"], $uniqToken, $cookieHash);
            $this->sendConfirmEmail($returnedUserData);
        }
    }

    /**
     * @param $email
     * @param $uniqToken
     * @param $cookieHash
     * @return NULL|\RedBeanPHP\OODBBean
     */
    private function insertUser($email, $uniqToken, $cookieHash): \RedBeanPHP\OODBBean
    {
        $user = R::dispense( 'user' );

        $user["email"] = $email;
        $user["confirm_token"] = $uniqToken;
        $user["cookie_hash"] = $cookieHash;

        $id = R::store( $user );
        return R::findOne( 'user', ' id = ? ', [ $id ] );
    }

    private function sendConfirmEmail($returnedUserData)
    {
        $emailClass = new EmailModel();
        $emailClass->init($returnedUserData);

    }
}