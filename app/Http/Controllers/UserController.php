<?php

namespace App\Http\Controllers;

use App\Core\Application;
use App\Core\Request;
use App\Models\User;

class UserController
{

    public User $user;

    public function __construct()
    {
        $this->user = Application::$user;
    }

    public static function register(Request $request) {
        if ($request->getMethod() === 'post') {
            $user_id = (new self)->store($request);
            if ($user_id) {
                $_SESSION["user_id"] = $user_id;;
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

        $user = (new self())->validateLogin($request);
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

    public function store(Request $request): int
    {
        $user = $this->validateRegister($request);
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

    public function validateRegister(Request $request, $action = 'register')
    {

        $this->validateRequired($request, 'username', 'Vartotojo vardas neivestas');
        $this->validateRequired($request, 'email', 'Slaptazodis neivestas');
        $this->validateRequired($request, 'pass1', 'Slaptazodis neivestas', true);
        $this->validateRequired($request, 'pass2', 'Slaptazodio pakartojimas neivestas', true);

        $this->validateValidUsername($request, 'username', 'Ivesti neleistini simboliai');
        $this->validateValidEmail($request, 'email', 'Ivesti neleistini simboliai');
        $this->validateValidPass($request, 'pass1', 'Ivesti neleistini simboliai');

        if($request->getBody()['pass1'] !== $request->getBody()['pass2']) {
            Application::$errors['pass1'] = '*';
            Application::$errors['pass2'] = 'Nesutampa salptazodziai';
        }


        //        @todo: sitie du turi butu gale
        $this->validateExistUsername($request, 'username', 'Vartotojo vardas jau naudojamas');
        $this->validateExistEmail($request, 'email', 'El pasta jau naudojamas');

        if (empty(Application::$errors)) {
            $this->user->name = $request->getBody()['username'];
            $this->user->email =  $request->getBody()['email'];
            $this->user->password =  $request->getBody()['pass1'];
            return $this->user;
        }

        return false;
    }

    public function validateLogin(Request $request) {
        $this->validateRequired($request, 'email', 'Neivestas el-pastas');
        $this->validateRequired($request, 'pass1', 'Neivestas slaptazodis', true);

        $this->validateValidEmail($request, 'email', 'Ivesti neleistini simboliai');
        $this->validateValidPass($request, 'pass1', 'Blogas slaptazodis');

        if (empty(Application::$errors)) {
            $this->user->email = $request->getBody()['email'];
            $this->user->password = $request->getBody()['pass1'];
            return $this->user;
        }

        return false;
    }

    public function validateRequired(Request $request, $name, string $error, bool $empty= false): bool
    {
        if (!isset($request->getBody()[$name]) || empty($request->getBody()[$name])) {
            Application::$errors[$name] = $error;
            Application::$old[$name] = false;
            return false;
        }

        if (!$empty) {
            Application::$old[$name] = $request->getBody()[$name];
        } else {
            Application::$old[$name] = '';

        }

        return true;
    }

    public function validateValidEmail(Request $request, $name, string $error): bool
    {
        if (isset(Application::$errors[$name])) {
            return false;
        }

        if (!filter_var($request->getBody()[$name], FILTER_VALIDATE_EMAIL)) {
            Application::$errors[$name] = $error;
            return false;
        }

        return true;
    }

    public function validateValidUsername(Request $request, $name, string $error): bool
    {
        if (isset(Application::$errors[$name])) {
            return false;
        }

        if (!preg_match('/^\w{2,20}$/', $request->getBody()[$name])) {
            Application::$errors[$name] = $error;
            return false;
        }

        return true;
    }

    public function validateValidPass(Request $request, $name, string $error): bool
    {
        if (isset(Application::$errors[$name])) {
            return false;
        }

        if ((!preg_match('@[A-Z]@', $request->getBody()[$name])) || !preg_match('@[a-z]@', $request->getBody()[$name])) {
            Application::$errors[$name] = $error;
            return false;
        }

        return true;
    }

    public function validateExistUsername(Request $request, $name, string $error): bool
    {
        if (isset(Application::$errors[$name])) {
            return false;
        }

        if ($this->user->existName($request->getBody()[$name])) {
            Application::$errors[$name] = $error;
            return false;
        }

        return true;
    }

    public function validateExistEmail(Request $request, $name, string $error): bool
    {
        if (isset(Application::$errors[$name])) {
            return false;
        }
        if ($this->user->existEmail($request->getBody()[$name])) {
            Application::$errors[$name] = $error;
            return false;
        }

        return true;
    }

    public static function logout() {
        session_start();
        session_destroy();

        header("Location: /");

        exit();
    }
}
