<?php

// @todo: why this shit isn't working
use App\Core\Router;

use App\Http\Controllers\UserController;

//require_once  $_SERVER['DOCUMENT_ROOT'] . '/app/Core/Router.php';

//echo 'WQE'; die();
\App\Core\Router::get('/register', [UserController::class, 'register'] );
Router::get('/register', [UserController::class, 'register'] );
Router::get('/', [UserController::class, 'index'] );