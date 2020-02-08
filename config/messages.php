<?php

return [
    "400" => [
        "enc" => "Email is not correct" // Email is not correct
    ],
    "403" => [
        "nva" => "Voting off" // Not vote access
    ],
    "404" => [
        'nfd' => "Not Found" // Not found
    ],
    "200" => [
        'cem' => "We have send you confirm email, please do it", // Confirm email message
        'mc' => "We remember you, when we sent you an email with a confirmation link, please confirm your email", // Make confirm email
        'sa' => "Successful authorization", // Successful authorization
        'vit' => "Vote is taken", // Vote is taken
        'eic' => "Email is confirmed", // Email is confirmed
    ],
    "500" => [
        'cncu' => "Internal error. Try again later" // Can't to create user
    ]
];
