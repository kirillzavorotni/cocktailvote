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
}