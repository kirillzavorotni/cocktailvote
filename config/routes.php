<?php

return [
    'GET /' => ['DefaultController', 'indexAction'],

    'POST /auth' => ['AuthController', 'authAction'],
    'GET /validate' => ['AuthController', 'validateAction'],

    'POST /vote' => ['VoteController', 'voteAction']
];