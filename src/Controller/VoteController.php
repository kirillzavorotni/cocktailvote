<?php


class VoteController extends CommonController
{
    /**
     * @throws NotFoundException
     */
    public function voteAction()
    {

        $data = $this->getRequestContent();

        if (!CommonController::isCookieRequestValidate() || !CommonController::isVoteDataRequestValidate($data)) {
            CommonController::setCookie();
            throw new NotFoundException();
        }

        $vote = new VoteModel();
        $vote->init($data);
    }
}