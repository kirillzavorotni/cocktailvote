<?php


class VoteModel extends CommonModel
{
    private $user;
    private $data;
    private $votesCount;

    public function __construct()
    {
        parent::__construct();
    }

    public function init($data)
    {
        $this->data = $data;
        $this->user = $this->findUserByCookie();

        if (!$this->user) {
            CommonController::setCookie();
            throw new NotFoundException();
        }

        if (!$this->getConfirmedStatusFromUser($this->user)) {
            CommonController::sendJSONResponse(
                true,
                "200",
                "mc",
                [],
                null,
                false
            );
        }

        $this->votesCount = $this->getLeftVoteCount($this->user);

        if ($this->votesCount >= $this->additional_conf["allowVoteCount"]) {
            CommonController::sendJSONResponse(
                true,
                "200",
                "vit",
                ["voteCount" => 0],
                null,
                false
            );
        }

        $this->insertVoteInDB();
        $this->sendResponse();
    }

    private function insertVoteInDB()
    {
        $vote = R::dispense('vote');
        $vote->user_id = $this->user["id"];
        $vote->product_id = $this->data["product_id"];
        R::store($vote);
    }

    private function sendResponse()
    {
        $this->votesCount = $this->additional_conf["allowVoteCount"] - $this->getLeftVoteCount($this->user);

        CommonController::sendJSONResponse(
            true,
            "200",
            "vit",
            ["voteCount" => $this->votesCount >= 0 ? $this->votesCount : 0],
            null,
            false
        );
    }
}