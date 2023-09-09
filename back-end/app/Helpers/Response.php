<?php

namespace App\Helpers;

class Response
{
    public static function statusCode(int $statusCode = 200): void
    {
        http_response_code($statusCode);
    }

    public static function json(array $data, int $status = 200): string
    {
        self::statusCode($status);
        header("Content-Type: application/json");

        return json_encode($data);
    }

    public static function notFound(): string
    {
        self::statusCode(404);

        return self::json([
            "msg" => "not found"
        ]);
    }

    public static function notAuth(): string
    {
        self::statusCode(400);

        return self::json([
            "msg" => "autenticação obrigatória"
        ]);
    }


    public static function success(string $detail = "success"): string
    {
        return Response::json([
            "detail" => $detail
        ]);
    }

    public static function json_header(): void
    {
        header("Content-Type: application/json");
    }
}
