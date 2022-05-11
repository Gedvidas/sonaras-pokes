<?php

use App\Controllers\UserController;
use App\Core\Application;

// Autoload and config
require_once '../vendor/autoload.php';
require_once '../config/config.php';

$app = new Application();
$app->run();
