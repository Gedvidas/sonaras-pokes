<?php

namespace App;

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
        $result = $stmt->execute();
        return $result;
    }
}