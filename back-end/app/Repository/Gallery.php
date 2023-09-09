<?php

namespace App\Repository;

use App\Helpers\Response;
use Exception;
use PDO;

class Gallery extends Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function all(): array|bool
    {
        new self;
        $sql = "SELECT * FROM galleries;";

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return Response::json(['msg'=>$e->getMessage()], 400);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_by_id(int $id): array|bool
    {
        $sql = self::setId("SELECT * FROM galleries WHERE `id` = {id};", $id);

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return Response::json(['msg'=>$e->getMessage()], 400);
        }

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete(int $id): string
    {
        new self;
        $sql = "DELETE FROM galleries WHERE `id` = {id};";
        $sql = self::setId($sql, $id);

        $stmt = self::$db->prepare($sql);

        try {
            if ($stmt->execute()) return Response::success("Galeria deletada com sucesso");
        } catch (Exception $e) {
            return Response::json(['msg'=>$e->getMessage()], 400);
        }
    }

    public static function create(array $data): string
    {
        new self;
        unset($data["id"]);
        $sql = "INSERT INTO galleries ({columns}) VALUES ({values});";
        $sql = self::setColumns($sql, array_keys($data));
        $sql = self::setValues($sql, array_values($data));

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return Response::json(['msg'=>$e->getMessage()], 400);
        }

        return Response::success("Galeria criada com sucesso!");
    }

    public static function update(int $id, array $data): array|bool
    {
        new self;

        $sql = self::setId("UPDATE galleries SET {sets} WHERE `id` = {id};", $id);
        $sql = self::setParams($sql, $data);


        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            return Response::json(['msg'=>$e->getMessage()], 400);
        }

        return self::get_by_id($id);
    }
}
