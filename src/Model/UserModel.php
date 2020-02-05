<?php


class UserModel extends CommonModel
{
    private $data;
    private $createdRecord;

    public function __construct()
    {
        parent::__construct();
    }

    public function init(Array $data)
    {
        $this->data = $data;

        $user = $this->checkUserInDB();

        if (!$user) {
            $this->createdRecord = $this->createNewRecord();
            if (!$this->createdRecord) {
                CommonController::sendJSONResponse(false, "500", "cncu");
            }
            $this->sendConfirmEmail($this->createdRecord);
        }


//        $usrs = R::dispense('user');
//        var_dump($usrs);
//        $vote = R::load('vote', 5);
//        $usrs = $vote->sharedProductList;
//        var_dump($usrs);
//        $product = $result->fetchAs('')

        $category = R::load('product', 9);
        $numBook = $category->countOwn('vote');
        var_dump($numBook);
    }


    ////////////////////////////////////////
    /////////   Check user in DB   /////////
    ////////////////////////////////////////

    /**
     * @return NULL|\RedBeanPHP\OODBBean
     */
    private function checkUserInDB()
    {
        $user = R::findOne('user', ' email = ? ', [$this->data["email"]]);
        return $user ? $user : null;
    }


    ///////////////////////////////////////
    /////////   Create new user   /////////
    ///////////////////////////////////////

    /**
     * @return NULL|\RedBeanPHP\OODBBean
     */
    private function createNewRecord()
    {
        $uniqToken = CommonModel::generateToken();
        $cookieHash = CommonModel::generateCookieHash();

        if (isset($uniqToken) && $cookieHash) {
            return $this->insertUser($this->data["email"], $uniqToken, $cookieHash);
        }

        return null;
    }

    /**
     * @param $email
     * @param $uniqToken
     * @param $cookieHash
     * @return NULL|\RedBeanPHP\OODBBean
     */
    private function insertUser($email, $uniqToken, $cookieHash): \RedBeanPHP\OODBBean
    {
        $user = R::dispense('user');

        $user["email"] = $email;
        $user["confirm_token"] = $uniqToken;
        $user["cookie_hash"] = $cookieHash;

        $id = R::store($user);
        return R::findOne('user', ' id = ? ', [$id]);
    }


    //////////////////////////////////////////
    /////////   Send confirm email   /////////
    //////////////////////////////////////////

    private function sendConfirmEmail(\RedBeanPHP\OODBBean $recordData)
    {
        $emailClass = new EmailModel();
        $emailClass->init($recordData);
    }
}