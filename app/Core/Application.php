<?php

namespace App\Core;

use App\User;
use PDO;

class Application
{
    public static Application $app;
    public static User $user;
    public static PDO $pdo;
    public Router $router;
    public Request $request;

    public function __construct() {
        self::$app = $this;
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
    }

    public function run() {
        $this->router->resolve();
    }
}
