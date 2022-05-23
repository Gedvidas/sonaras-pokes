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

    public function create($username, $password, $email): bool
    {
        $sql = "INSERT INTO `users` (`username`, `password`, `email`) VALUES (:username, :password, :email)";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password', sha1($password), PDO::PARAM_STR); // uzhesuojam password
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function getIdByEmail(string $email) {
        $sql = "SELECT `id` FROM `users` WHERE email = :email LIMIT 1";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_INT);
        $stmt->execute();
        return  $stmt->fetchColumn();
    }

    public function getUserById(int $id) {
        $sql = "SELECT * FROM `users` WHERE id = :id LIMIT 1";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return  $stmt->fetch();
    }
}