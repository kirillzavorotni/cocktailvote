<?php

return [
    "400" => [
        "enc" => "Введите корректный email адрес" // Email is not correct
    ],
    "403" => [
        "nva" => "Голосование отключено" // Not vote access
    ],
    "404" => [
        'nfd' => "Not Found" // Not found
    ],
    "200" => [
        'cem' => "Мы отправили письмо на ваш email со сылкой для подтверждения голоса. Подтвердите голос", // Confirm email message
        'mc' => "Мы вас помним, пожалуйста подтвердите свой email", // Make confirm email
        'sa' => "Successful authorization", // Successful authorization
        'vit' => "Голос принят", // Vote is taken
        'eic' => "Email подтвержден", // Email is confirmed
    ],
    "500" => [
        'cncu' => "Внутренняя ошибка, повторите еще раз" // Can't to create user
    ],
    "501" => [
        'end' => "Email не был доставлен, повторите снова" // Email wasn't delivery
    ]
];
