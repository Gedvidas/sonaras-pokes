<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\User;

class UserController
{
    public User $user;

    public function __construct() {
        $this->user = new User();

    }

    public static function register(Request $request) {
        if ($request->getMethod() === 'get') {
            require_once '../views/user/register.php';
        }
    }

    public static function insert() {
        $a = 1;
        $b=2;
    }



}