<?php

namespace App\Repository;

use App\Helpers\Response;
use Exception;
use PDO;

class User extends Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function get_by_token(string $token): array|bool
    {
        $sql = self::setToken("SELECT * FROM users WHERE `token` = '{token}';", $token);

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return Response::json(['msg'=>$e->getMessage()], 400);
        }

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected static function setToken(string $sql, string $token): string
    {
        return str_replace("{token}", $token, $sql);
    }

    public static function get_by_id(int $id): array|bool
    {
        $sql = self::setId("SELECT * FROM users WHERE `id` = {id};", $id);

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return Response::json(['msg'=>$e->getMessage()], 400);
        }

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function get_by_email(string $email): array|bool
    {
        $sql = self::setEmail("SELECT * FROM users WHERE `email` = '{email}';", $email);

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return Response::json(['msg'=>$e->getMessage()], 400);
        }
               
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected static function setEmail(string $sql, string $email): string
    {
        return str_replace("{email}", $email, $sql);
    }


    public static function create(array $data): array
    {
        new self;
        unset($data["id"]);
        $sql = "INSERT INTO users ({columns}) VALUES ({values});";
        $sql = self::setColumns($sql, array_keys($data));
        $sql = self::setValues($sql, array_values($data));

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return Response::json(['msg'=>$e->getMessage()], 400);
        }

        return self::get_by_id(self::$db->lastInsertId());
    }
}
