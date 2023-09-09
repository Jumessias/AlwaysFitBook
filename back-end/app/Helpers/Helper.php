<?php

namespace App\Helpers;

use App\Models\User;

class Helper
{
    public static function escape(string $path): string
    {
        $escapedPath = preg_quote($path, '/');

        return str_replace("\{id\}", "([^\/]+)", $escapedPath);
    }


    public static function match(string $path, string $route): ?string
    {
        preg_match('/^' . $route . '$/', $path, $matches);

        if (count($matches) === 0) return null;
        else if (isset($matches[1])) return $matches[1];
        else return $matches[0];
    }

    public static function randomStr(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function urlEncode_format(array $formFields): string
    {
        return http_build_query($formFields);
    }

    public static function UserAuth()
    {
        $user = null;
        $token = Request::token();
        if($token != null){
            $user = User::findByToken($token);
        }
        
        if($user == null || $token == null){
            return null;
        }
        return $user;
    }
}
