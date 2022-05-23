<?php

use App\Controllers\UserController;
use App\Core\Application;

// Autoload and config
require_once '../vendor/autoload.php';
require_once '../config/config.php';

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->safeLoad();

$app = new Application();
$app->run();
