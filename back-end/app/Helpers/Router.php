<?php

namespace App\Helpers;

class Router
{
    private static array $map = [
        "get"      => [],
        "put"      => [],
        "post"     => [],
        "delete"   => [],
        "not_found" => null
    ];

    public static function get(string $path, array $callback)
    {
        $path = Helper::escape($path);
        self::$map["get"][$path] = $callback;
    }

    public static function post(string $path, array $callback)
    {
        $path = Helper::escape($path);

        self::$map["post"][$path] = $callback;
    }

    public static function put(string $path, array $callback)
    {
        $path = Helper::escape($path);

        self::$map["put"][$path] = $callback;
    }

    public static function delete(string $path, array $callback)
    {
        $path = Helper::escape($path);

        self::$map["delete"][$path] = $callback;
    }

    public static function not_found(array $callback)
    {
        self::$map["not_found"] = $callback;
    }

    public function resolve()
    {
        $method = Request::method();
        $path = Request::path();

        foreach (array_keys(self::$map[$method]) as $route) {
            $params = Helper::match($path, $route) ?? [];

            if ($params) {
                $instance = new self::$map[$method][$route][0];
                $methodOfController = self::$map[$method][$route][1];
                return call_user_func([$instance, $methodOfController], $params);
            }
        }

        Response::statusCode(404);

        if (self::$map["not_found"] !== null) return call_user_func(self::$map["not_found"]);
    }
}
