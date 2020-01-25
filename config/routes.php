<?php

return [
    'POST /auth' => ['AuthController', 'authAction'],
    'GET /validate' => ['AuthController', 'validateAction'],

    'GET /votes' => ['VoteController' => 'votesAction'],
    'POST /vote' => ['VoteController' => 'voteAction']
];