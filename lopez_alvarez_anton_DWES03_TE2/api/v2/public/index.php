<?php


//phpinfo();

use app\core\Router;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

// echo "HOLAAAAAAAA";

// echo __DIR__;

$url = $_SERVER['REQUEST_URI'];

$router = new Router();
$router->match($url);

?>