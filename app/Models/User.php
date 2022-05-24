<?php

namespace App\Models;

use App\Core\Application;
use PDO;

class User
{
    public string $name;
    public string $email;
    public string $password;
    public int $pokes;
    public $id;

    public function create(): bool
    {
        $sql = "INSERT INTO `users` (`username`, `password`, `email`) 
                VALUES (:username, :password, :email)";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':username', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':password', sha1($this->password), PDO::PARAM_STR); // uzhesuojam password
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $result = $stmt->execute();
        return $result;
    }

    public function getIdByEmail() {
        $sql = "SELECT `id` FROM `users` WHERE email = :email LIMIT 1";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_INT);
        $stmt->execute();
        $result =   $stmt->fetchColumn();
        return $result;
    }

    public function getUserById(int $id): User
    {
        $sql = "SELECT * FROM `users` WHERE id = :id LIMIT 1";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result =  $stmt->fetch();
        $this->id = $result['id'];
        $this->email = $result['email'];
        $this->password = $result['password'];
        $this->name = $result['username'];

        return $this;
    }

    public function login() {
        $sql = "SELECT `id` FROM `users` WHERE `email` = :email AND `password` = :password LIMIT 1";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':password', sha1($this->password), PDO::PARAM_STR);
        $stmt->execute();
        $result =  $stmt->fetch();
        if(empty($result)) {
            return false;
        }
        return $result['id'];
    }
}