<?php
declare(strict_types=1);

use App\Api;

require __DIR__ . '/../../vendor/autoload.php';

$api = new Api();
try {

    $api->run();
}
catch(\Slim\Exception\HttpMethodNotAllowedException $e) {
    header("HTTP/1.1 405 Method Not Allowed");
}
