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
    public string $table = 'users';

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

    public static function existEmail(string  $email){
        $sql = "SELECT `email` FROM `users` WHERE email = :email LIMIT 1";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_INT);
        $stmt->execute();
        $result =   $stmt->fetchColumn();
        return $result;
    }

        public function existName(string $name) {
        $sql = "SELECT `id` FROM `users` WHERE username = :username LIMIT 1";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':username', $name, PDO::PARAM_INT);
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

    public function isValidPassword(int $id, string $password): bool
    {
        $sql = "SELECT `id` FROM `users` WHERE `id` = :id AND `password` = :password LIMIT 1";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':password', sha1($password), PDO::PARAM_STR);
        $stmt->execute();
        $result =  $stmt->fetch();
        if(empty($result)) {
            return false;
        }
        return true;
    }

    public function update(string $email, string $name, $password = false): bool
    {
        $id = $this->id;
        $sql1 = "UPDATE `users` SET email = :email, username = :name ";
        $sql2 = "";
        if ($password) {
            $sql2 = ", password = :password";
        }
        $sql3 = " WHERE id = :id";
        $sql = $sql1 . $sql2 . $sql3;

        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_INT);
        if ($password) {
            $stmt->bindValue(':password', sha1($password), PDO::PARAM_INT);
        }
        $stmt->bindValue(':name', $name, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getAllExceptOne(int $id) {
        $sql = "SELECT * FROM `users` WHERE id != :id";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($result)) {
            return array();
        }
        return $result;
    }

    public function getAllWithPokes(int $id) {
        $sql = "
                SELECT users.id, users.username, users.email, 
                       ( SELECT COUNT(*) FROM pokes WHERE users.id = pokes.user_to ) as pokes 
                FROM `users` 
                WHERE users.id != :id
                ORDER BY pokes DESC
                ";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($result)) {
            return array();
        }
        return $result;
    }
}
