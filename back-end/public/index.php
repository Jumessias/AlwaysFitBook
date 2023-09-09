<?php

header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

require_once dirname(__DIR__) . "/vendor/autoload.php";

define("LOADED", true);

use App\Helpers\Application;
$app = new Application();
include_once dirname(__DIR__) . "/app/routes.php";
echo $app->run();
