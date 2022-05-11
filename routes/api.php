<?php


use App\Http\Controllers\UserController;

require_once  $_SERVER['DOCUMENT_ROOT'] . '/app/Core/Router.php';

//echo 'WQE'; die();
\App\Core\Router::post('/register', [UserController::class, 'insert'] );