<?php

namespace App\Http\Controllers;

use App\Core\Application;
use App\Core\Request;
use App\Models\User;

class UserController
{

    public static function register(Request $request) {
        if ($request->getMethod() === 'post') {
            $id = self::store($request);
            if ($id) {
                $_SESSION["user_id"] = $id;;
                header("Location: /");
            }
        }
        require_once VIEW_ROOT . 'register.php';

    }

    public static function login(Request $request) {
        if ($request->getMethod() !== 'post') {
            require_once VIEW_ROOT . 'login.php';
            return;
        }

        $user = self::validateLogin($request);
        if ($user) {
            $user->id = $user->login();
            if ($user->id) {
                $_SESSION["user_id"] = $user->id;;
                header("Location: /");
            } else {
                Application::$errors['email'] = 'Blogi prisijungimo duomenys';
                Application::$errors['pass1'] = '*';
            }
        }
        require_once VIEW_ROOT . 'login.php';
    }

    public static function store(Request $request): int
    {
        $user = self::getUserFromRequest($request);
        if (!$user) {
            return 0;
        }
        if ($user->create()) {
            return $user->getIdByEmail();;
        }
        return 0;
    }

    public static function index() {
        if (isset($_SESSION['user_id'])) {
            $user = Application::$user->getUserById($_SESSION['user_id']);
        } else {
            $user = false;
        }

        require_once VIEW_ROOT . 'main.php';
    }

    public static function getUserFromRequest(Request $request, $action = 'register')
    {
        $user = new User();
        if ($action === 'register') {
            if (!isset($request->getBody()['username']) || empty($request->getBody()['username'])) {
                Application::$errors['username'] = 'Vartotojo vardas neivestas';
                Application::$old['username'] = false;
            } else {
                Application::$old['username'] = $request->getBody()['username'];
            }
            if(!preg_match('/^\w{5,}$/', $request->getBody()['username'])) {
                Application::$errors['username'] = 'Vartotojo vardas blogas';
            } elseif(User::existName($request->getBody()['username'])) {
                Application::$errors['username'] = 'Vartotojo vardas jau naudojamas';
            }
            else {
                $user->name = $request->getBody()['username'];
            }
        }

//        --------------------------------------------
        if (!isset($request->getBody()['email']) || empty($request->getBody()['email'])) {
            Application::$errors['email'] = 'El. pastas neivestas';
            Application::$old['email'] = false;
        } else {
            Application::$old['email'] = $request->getBody()['email'];

        } if(!filter_var($request->getBody()['email'], FILTER_VALIDATE_EMAIL)) {
            Application::$errors['email'] = 'El pastas blogas';
        } elseif($action === 'register' && User::existEmail($request->getBody()['email'])) {
            Application::$errors['email'] = 'El pasta jau naudojamas';
        }
        else {
            $user->email = $request->getBody()['email'];
        }
//        --------------------------------------------
        if (!isset($request->getBody()['pass1']) || empty($request->getBody()['pass1'])) {
            Application::$errors['pass1'] = 'Slaptazodis neivestas';
        } else {
            $user->password = $request->getBody()['pass1'];
        }
        if($action === 'register' && (!preg_match('@[A-Z]@', $request->getBody()['pass1'])) || !preg_match('@[a-z]@', $request->getBody()['pass1'])) {
            Application::$errors['pass1'] = 'Slaptazodi turi sudaryti bent 1 didzioji raide, bent 1 skaicius';
        }

        if ($action === 'register') {
            if (!isset($request->getBody()['pass2']) || empty($request->getBody()['pass2'])) {
                Application::$errors['pass2'] = 'Slaptazodio pakartojimas neivestas';
            } elseif($request->getBody()['pass1'] !== $request->getBody()['pass2']) {
                Application::$errors['pass1'] = '*';
                Application::$errors['pass2'] = 'Nesutampa salptazodziai';
            }
        }

        if (empty(Application::$errors)) {
            return $user;
        }

        return false;
    }

    public static function validateLogin(Request $request) {
        $user = new User();

        if (!isset($request->getBody()['email']) || empty($request->getBody()['email'])) {
            Application::$errors['email'] = 'El. pastas neivestas';
            Application::$old['email'] = false;
        } elseif (!filter_var($request->getBody()['email'], FILTER_VALIDATE_EMAIL)){
            Application::$errors['email'] = 'Blogi prisijungimo duomenys';
            Application::$errors['pass1'] = '*';

        } else {
            Application::$old['email'] = $request->getBody()['email'];
            $user->email = $request->getBody()['email'];
        }

        if (!isset($request->getBody()['pass1']) || empty($request->getBody()['pass1'])) {
            Application::$errors['pass1'] = 'Slaptazodis neivestas';
        } else {
            $user->password = $request->getBody()['pass1'];
        }

        if (empty(Application::$errors)) {
            return $user;
        }

        return false;
    }

    public static function logout() {
        session_start();
        session_destroy();

        header("Location: /");

        exit();
    }
}
