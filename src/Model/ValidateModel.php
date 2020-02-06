<?php


class ValidateModel extends CommonModel
{

    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        $user = $this->findUserByCookie();

        if (!$user) {
            CommonController::setCookie();
            throw new NotFoundException();
        }

        if (!$this->getConfirmedStatusFromUser($user)) {
            CommonController::sendJSONResponse(
                true,
                "200",
                "mc",
                [],
                null,
                false
            );
        }

        $votesLeft = $this->additional_conf["allowVoteCount"] - $this->getLeftVoteCount($user);

        CommonController::sendJSONResponse(
            true,
            "200",
            "sa",
            ["voteCount" => $votesLeft >= 0 ? $votesLeft : 0],
            $this->getCookieHashFromUser($user)
        );
    }

    private function findUserByCookie()
    {
        return R::findOne('user', 'cookie_hash = ?', [$_COOKIE["id"]]);
    }
//
//
//    ////////////////////////////////////////
//    /////////   Check user in DB   /////////
//    ////////////////////////////////////////
//
//    /**
//     * @return NULL|\RedBeanPHP\OODBBean
//     */
//    private function checkUserInDB()
//    {
//        $user = R::findOne('user', ' email = ? ', [$this->data["email"]]);
//        return $user ? $user : null;
//    }
//
//    /**
//     * @param \RedBeanPHP\OODBBean $user
//     * @return int
//     */
//    private function getLeftVoteCount(\RedBeanPHP\OODBBean $user): int
//    {
//        return R::count( 'vote', 'user_id = ?', [$user["id"]]);
//    }
//
//    ///////////////////////////////////////
//    /////////   Create new user   /////////
//    ///////////////////////////////////////
//
//    /**
//     * @return NULL|\RedBeanPHP\OODBBean
//     */
//    private function createNewRecord()
//    {
//        $uniqToken = CommonModel::generateToken();
//        $cookieHash = CommonModel::generateCookieHash();
//
//        if (isset($uniqToken) && $cookieHash) {
//            return $this->insertUser($this->data["email"], $uniqToken, $cookieHash);
//        }
//
//        return null;
//    }
//
//    /**
//     * @param $email
//     * @param $uniqToken
//     * @param $cookieHash
//     * @return NULL|\RedBeanPHP\OODBBean
//     */
//    private function insertUser($email, $uniqToken, $cookieHash): \RedBeanPHP\OODBBean
//    {
//        $user = R::dispense('user');
//
//        $user["email"] = $email;
//        $user["confirm_token"] = $uniqToken;
//        $user["cookie_hash"] = $cookieHash;
//
//        $id = R::store($user);
//        return R::findOne('user', ' id = ? ', [$id]);
//    }
//
//
//    //////////////////////////////////////////
//    /////////   Send confirm email   /////////
//    //////////////////////////////////////////
//
//    private function sendConfirmEmail(\RedBeanPHP\OODBBean $recordData)
//    {
//        $emailClass = new EmailModel();
//        $emailClass->init($recordData);
//    }
}