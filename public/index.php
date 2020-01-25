<?php

require_once "../src/loader.php";

try {
    $kernel = new Kernel();
    $kernel->handleRequest();
} catch (Exception $exception) {
    http_response_code($exception->getCode());
    echo $exception->getMessage();
}

