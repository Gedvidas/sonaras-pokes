<?php

namespace App\Http\Controllers;

use App\Core\Application;
use App\Core\Request;
use App\Models\User;

class UserController
{

    public static function register(Request $request) {
        if ($request->getMethod() === 'get') {
            require_once VIEW_ROOT . 'register.php';
        }

        else {
            $id = self::store($request);
            if ($id) {
                 $_SESSION["user_id"] = $id;;
                header("Location: /");
            }
            else {
                echo 'Registration failed';
            }
        }
    }

    public static function login(Request $request) {
        if ($request->getMethod() === 'get') {
            require_once VIEW_ROOT . 'login.php';
        } else {
            $user = self::getUserFromRequest($request);
            $user->id = $user->login();
            if ($user->id) {
                $_SESSION["user_id"] = $user->id;;
                header("Location: /");
            }
            echo 'BAD EMAIL OR PASSWORD';
        }
    }

    public static function store(Request $request): int
    {
        $user = self::getUserFromRequest($request);
        if ($user->create()) {
            return $user->getIdByEmail();;
        }
        return 0;
    }

    public static function index() {
//        var_dump($_SESSION);die();
        if (isset($_SESSION['user_id'])) {
            $user = Application::$user->getUserById($_SESSION['user_id']);
        } else {
            $user = false;
        }

        require_once VIEW_ROOT . 'main.php';
    }

    public static function getUserFromRequest(Request $request): User
    {
        //        @todo: validate
        $user = new User();
        if (isset($request->getBody()['username'])) {
            $user->name = $request->getBody()['username'];
        } else {
            $user->name = '';
        }

        if (isset($request->getBody()['email'])) {
            $user->email = $request->getBody()['email'];
        } else {
            $user->email = '';
        }

        if (isset($request->getBody()['pass1'])) {
            $user->password = $request->getBody()['pass1'];
        } else {
            $user->password = '';
        }

        return $user;
    }

    public static function logout() {
        session_start();
        session_destroy();

        header("Location: /");

        exit();
    }
}
