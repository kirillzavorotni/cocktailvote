<?php


class ActivateModel extends CommonModel
{
    private $token;
    private $user;
    private $votesCount;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $token
     */
    public function init(String $token)
    {
        $this->token = $token;
        $this->user = $this->findUserByTokenInBD();

        if (!$this->user) {
            header('Location: http://' . $_SERVER['HTTP_HOST']);
            exit;
        }

        if (!$this->getConfirmedStatusFromUser($this->user)) {
            $this->updateUserStatus(1);
        }

        $this->sendConfirmedResponse();
    }

    /**
     * @return NULL|\RedBeanPHP\OODBBean
     */
    private function findUserByTokenInBD()
    {
        $user = R::findOne('user', ' confirm_token = ? ', [$this->token]);
        return $user ? $user : null;
    }

    /**
     * @param Int $status
     */
    private function updateUserStatus(Int $status)
    {
        $user = R::load('user', $this->user["id"]);
        $user->confirm_status = $status;
        R::store($user);
    }

    private function sendConfirmedResponse()
    {
        $this->votesCount = $this->additional_conf["allowVoteCount"] - $this->getLeftVoteCount($this->user);

        CommonController::sendJSONResponse(
            true,
            "200",
            "eic",
            ["voteCount" => $this->votesCount >= 0 ? $this->votesCount : 0],
            $this->user["cookie_hash"],
            false,
            false
        );

        header('Location: http://' . $_SERVER['HTTP_HOST']);
    }
}