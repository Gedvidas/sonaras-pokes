<?php

namespace App\Models;

use App\Core\Application;
use PDO;

class Poke
{
    public function canPoke(int $user_from, int $user_to): bool
    {
        $sql = "SELECT `id` FROM `pokes` WHERE `user_from` = :user_from AND `user_to` = :user_to LIMIT 1";
        $stmt = Application::$pdo->prepare($sql);
        $stmt->bindValue(':user_from', $user_from, PDO::PARAM_STR);
        $stmt->bindValue(':user_to', $user_to, PDO::PARAM_STR);
        $stmt->execute();
        $result =  $stmt->fetch();
        if(empty($result)) {
            return true;
        }
        return false;
    }

    public function create(int $user_from, int $user_to): bool
    {
        $sql = "INSERT INTO `pokes` (`user_from`, `user_to`) 
                VALUES (:user_from, :user_to)";
        $stmt = Application::$pdo->prepare($sql);

        $stmt->bindValue(':user_from', $user_from, PDO::PARAM_STR);
        $stmt->bindValue(':user_to', $user_to, PDO::PARAM_STR);
        $result = $stmt->execute();
        return $result;
    }
}