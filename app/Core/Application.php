<?php

namespace App\Core;

use App\Models\User;
use PDO;

class Application
{
    public static Application $app;
    public static User $user;
    public static PDO $pdo;
    public static array $response;
    public Router $router;
    public Request $request;
    public static array $errors;
    public static array $old;

    public function __construct() {
        session_start();
        self::$app = $this;
        //    @todo User objektas tiek Apllication klaseje tiek User Kontroleryje
        self::$user = new User();
        $this->request = new Request();
        $this->router = new Router($this->request);

        //Connecting to database
        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);

        self::initResponse();

    }

    public function run() {
        $this->router->resolve();
    }

    public function initResponse(): array
    {
        self::$response['email'] = '';
        self::$response['error'] = '';
        self::$response['confirmation'] = false;

        return self::$response;
    }
}
