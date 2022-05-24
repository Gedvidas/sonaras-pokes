<?php

use App\Core\Router;
use App\Http\Controllers\UserController;


Router::get('/', [UserController::class, 'index'] );
Router::get('/register', [UserController::class, 'register'] );
Router::get('/login', [UserController::class, 'login'] );
Router::get('/logout', [UserController::class, 'logout'] );
