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
        $action = 'register';
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

    public static function edit(Request $request) {
        if (isset($_SESSION['user_id'])) {
            $user = Application::$user->getUserById($_SESSION['user_id']);
            $action = 'edit';
            (new self())->setOldData($user);
            require_once VIEW_ROOT . 'edit.php';
        } else {
            require_once VIEW_ROOT . '403.php';
        }
    }

    public static function update(Request $request) {
        if (!isset($_SESSION['user_id'])) {
            require_once VIEW_ROOT . '403.php';
            return;
        }
        $user = Application::$user->getUserById($_SESSION['user_id']);
        $update = (new self)->validateEdit($request, $user);
        if ($update) {
            $password = false;
//            @todo: antra karta kreipiuos i ta pacia funkcija
            if ((new self)->updatingPassword($request,'pass0', 'pass1', 'pass2')) {
                $password = $request->getBody()['pass1'];
            }
            $update = $user->update($request->getBody()['email'], $request->getBody()['username'], $password);
            if ($update) {
                Application::$confirmation = 'Vartotojo profilis sekmingai atnaujintas';
            }
        }

        require_once VIEW_ROOT . 'edit.php';
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
//        @todo: perkelti i konstruktoriu?
        $user = false;
        $users = [];
        if (isset($_SESSION['user_id'])) {
//            @todo: duplication
            $user = (new self)->user->getUserById($_SESSION['user_id']);
            $users = (new self)->user->getAllWithPokes($_SESSION['user_id']);
        }

       require_once VIEW_ROOT . 'main.php';
    }

    public function validateEdit(Request $request, User $user): bool
    {
        $this->validateTextInputs($request);
        $this->validateUniqueUsername($request, 'username', 'Vartotojo vardas jau naudojamas', $user->id);
        $this->validateUniqueEmail($request, 'email', 'EL-pastas vardas jau naudojamas', $user->email);

//        If all 3 password imputs are blank - we are not updating and validating password
        $updatingPass = $this->updatingPassword($request,'pass0', 'pass1', 'pass2');
        if ($updatingPass) {
            $this->validateRequired($request, 'pass0', 'pass0 neivestas',true);
            $this->validateRequired($request, 'pass1', 'pass0 neivestas',true);
            $this->validateRequired($request, 'pass2', 'pass2 neivestas',true);
            $this->validateValidPass($request, 'pass1','Slaptazodi turi sudaryti bent 1 skaicius ir bent 1 didzioji raide');
            $this->validatePasswordConfirm($request, 'pass1', 'pass2', 'Slaptazodziai nesutampa');
            $this->validateOldAndNewPass($request, 'pass0', 'pass1', 'Naujas slaptazodis, negali buti toks pats kaip senas');
            if (!$this->isError()) {
                $this->validateCorrectPassword($request, 'pass0', 'Suvestas blogas slaptazodis');
            }
        }

        if (!$this->isError()) {
            $this->validateNewData($request, $user, 'username', 'email', 'Nei vienas laukas nepakeistas', $updatingPass);
        }

        if (!$this->isError()) {
            return true;
        }

        return false;
    }

    public function validateRegister(Request $request, $action = 'register')
    {
        $this->validateTextInputs($request);

        $this->validateRequired($request, 'pass1', 'Slaptazodis neivestas', true);
        $this->validateRequired($request, 'pass2', 'Slaptazodio pakartojimas neivestas', true);

        $this->validateValidPass($request, 'pass1', 'Slaptazodi turi sudaryti bent 1 skaicius ir bent 1 didzioji raide');

        $this->validatePasswordConfirm($request, 'pass1', 'pass2', 'Slaptazodziai nesutampa');


        //        @todo: sitie du turi butu gale
        $this->validateUniqueUsername($request, 'username', 'Vartotojo vardas jau naudojamas');
        $this->validateUniqueEmail($request, 'email', 'El pasta jau naudojamas');

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
        $this->validateValidPass($request, 'pass1', 'Slaptazodi turi sudaryti bent 1 skaicius ir bent 1 didzioji raide');

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

        if (!preg_match('/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/', $request->getBody()[$name])) {
            Application::$errors[$name] = $error;
            return false;
        }

        return true;
    }

    public function validateUniqueUsername(Request $request, $name, string $error, int $current_user_id = 0): bool
    {
        if (isset(Application::$errors[$name])) {
            return false;
        }

        $id = $this->user->existName($request->getBody()[$name]);
        if ($id && $id !== $current_user_id) {
            Application::$errors[$name] = $error;
            return false;
        }

        return true;
    }

    public function validateUniqueEmail(Request $request, $name, string $error, string $current_user_email = ''): bool
    {
        if (isset(Application::$errors[$name])) {
            return false;
        }
        $email = $this->user->existEmail($request->getBody()[$name]);
        if ($email && $email !== $current_user_email) {
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

    public function setOldData(User $user) {
        Application::$old['username'] = $user->name;
        Application::$old['email'] = $user->email;
    }

    public function validateTextInputs(Request $request) {
        $this->validateRequired($request, 'username', 'Vartotojo vardas neivestas');
        $this->validateValidUsername($request, 'username', 'Ivesti neleistini simboliai');
        $this->validateRequired($request, 'email', 'Email neivestas');
        $this->validateValidEmail($request, 'email', 'Ivesti neleistini simboliai');
    }

    public function updatingPassword(Request $request, $pass1, string $pass2, string $pass3): bool
    {
        $updating = false;
        if (isset($request->getBody()[$pass1]) && !empty($request->getBody()[$pass1])) {
            $updating = true;
        }
        if (isset($request->getBody()[$pass2]) && !empty($request->getBody()[$pass2])) {
            $updating = true;
        }
        if (isset($request->getBody()[$pass3]) && !empty($request->getBody()[$pass3])) {
            $updating = true;
        }

        return $updating;
    }

    public function validatePasswordConfirm(Request $request, string $pass1, string $pass2, string $error) {
        if($request->getBody()[$pass1] !== $request->getBody()[$pass2]) {
            Application::$errors[$pass1] = '*';
            Application::$errors[$pass2] = $error;
        }
    }

    public function validateOldAndNewPass(Request $request, string $pass1, string $pass2, string $error )
    {
        if ($request->getBody()[$pass1] === $request->getBody()[$pass2]) {
            Application::$errors[$pass1] = $error;
            Application::$errors[$pass2] = '*';
        }
    }

    public function validateCorrectPassword(Request $request, string $pass, string $error): bool
    {
        if ($this->isNoError()) {
            return false;
        }
        if (!$this->user->isValidPassword(
            $_SESSION['user_id'],
            $request->getBody()[$pass])) {
            Application::$errors[$pass] = $error;
            return true;
        }

        return false;
    }

    public function isError(): bool
    {
        return !empty(Application::$errors);
    }

    public function validateNewData(Request $request, User $user, string $name, string $email, string $error, bool $updatingPass = false): bool
    {
        if ($user->email === $request->getBody()[$email] && $user->name === $request->getBody()[$name] && !$updatingPass) {
            Application::$errors[$name] = $error;
            Application::$errors[$email] = '*';
            return false;
        }

        return true;
    }
}
