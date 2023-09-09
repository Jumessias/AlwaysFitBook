<?php

namespace App\Helpers;

class Config
{

    public static function database_config(): array
    {
        return [
            "name"        => 'local_gallery',
            "password"    => 'root',
            "user"        => 'root',
            "host"        => 'localhost',
        ];
    }

    public static function dsn(): string
    {
        $database_config = self::database_config();

        $dsn = "mysql:host={$database_config['host']};dbname={$database_config['name']}";
        return $dsn;
    }

    public static function password(): string
    {
        return 'root';
    }

    public static function user(): string
    {
        return 'root';
    }
}
