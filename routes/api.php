<?php


use App\Core\Router;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PokeController;

require_once  $_SERVER['DOCUMENT_ROOT'] . '/app/Core/Router.php';

Router::post('/register', [UserController::class, 'register'] );
Router::post('/login', [UserController::class, 'login'] );
Router::post('/edit', [UserController::class, 'update'] );
Router::post('/poke', [PokeController::class, 'poke'] );