<?php


class ActivateModel extends CommonModel
{
    private $params;
    private $user;
    private $votesCount;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $params
     */
    public function init(Array $params)
    {
        $this->params = $params;
        $this->user = $this->findUserByTokenInBD();

        if (!$this->user) {
            header('Location: ' . $this->additional_conf["protocol"] . '://' . $_SERVER['HTTP_HOST']);
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
        $user = R::findOne('user', ' confirm_token = ? ', [$this->params[0]]);
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

        if (count($this->params) == 2) {
            $this->addVote();
        }

        CommonController::sendJSONResponse(
            true,
            "200",
            "eic",
            ["voteCount" => $this->votesCount >= 0 ? $this->votesCount : 0],
            $this->user["cookie_hash"],
            false,
            false
        );

        header('Location: ' . $this->additional_conf["protocol"] . '://' . $_SERVER['HTTP_HOST']);
    }

    private function addVote()
    {

        if ($this->votesCount > 0) {
            $vote = R::dispense('vote');
            $vote->user_id = $this->user["id"];
            $vote->product_id = $this->params[1];
            R::store($vote);
        }

    }
}