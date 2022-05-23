<?php

namespace App\Http\Controllers;

use App\Core\Application;
use App\Core\Request;
use App\Models\User;

class UserController
{
//    public static User $user;
//
//    public function __construct() {
//        self::$user = new User();
//
//    }

    public static function register(Request $request) {
        if ($request->getMethod() === 'get') {
            require_once VIEW_ROOT . 'main.php';
        }
    }

    public static function insert(Request $request): bool
    {
        $username = $request->getBody()['username'];
        $email = $request->getBody()['email'];
        $pass1 =$request->getBody()['username'];
//        @todo: senas variantas su javascript
//        $data = json_decode(file_get_contents('php://input', true));
//        $username = $data->data->username;
//        $email = $data->data->email;
//        $pass1 =$data->data->pass1;

//        @todo: implement validation
        // Nesukuriamas user objektas
        $inserted = Application::$user->create($username, $pass1, $email);
        if ($inserted) {
            $_SESSION["user_id"] = Application::$user->getIdByEmail($email);;

            header("Location: /");

//            Application::$response['confirmation'] = 'User was crated successfully';
        }
    }

    public static function index() {
        if (isset($_SESSION['user_id'])) {
            $user = Application::$user->getUserById($_SESSION['user_id']);
            var_dump($user);die();
        }
    }
}
