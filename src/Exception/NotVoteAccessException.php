<?php


class NotVoteAccessException extends Exception
{
    public function __construct()
    {
        parent::__construct("Voting off", 404);
    }
}