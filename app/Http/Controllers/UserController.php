<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\User;

class UserController
{
    public static User $user;

    public function __construct() {
        self::$user = new User();

    }

    public static function register(Request $request) {
        if ($request->getMethod() === 'get') {
            require_once VIEW_ROOT . 'main.php';
        }
    }

    public static function insert() {

//        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);
        $data = json_decode(file_get_contents('php://input', true));
        $username = $data->data->username;
        $email = $data->data->email;
        $data->data->pass1;

        return self::$user->create($username, $pass, $email);
    }
}