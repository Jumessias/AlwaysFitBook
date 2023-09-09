<?php

namespace App\Models;

use App\Repository\User as DatabaseUser;
use App\Helpers\Helper;
use App\Helpers\Response;

class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public string|null $token;
    public string|null $created_at;
    public string|null $updated_at;

    public function __construct()
    {
        $this->created_at = date('Y-m-d H:i:s', time());
        $this->updated_at = date('Y-m-d H:i:s', time());
    }

    public static function find(int $id): User|bool
    {
        $user = self::_find($id);

        if ($user) {
            $user = (new User())->load($user);
            return $user;
        }
        return false;
    }

    public static function findByToken(string $token): User|bool
    {
        $user = (new DatabaseUser())->get_by_token($token);

        if ($user) {
            $user = (new User())->load($user);
            return $user;
        }
        return false;
    }

    public static function findByEmail(string $email): User|bool
    {
        $user = (new DatabaseUser())->get_by_email($email);

        if ($user) {
            $user = (new User())->load($user);
            return $user;
        }
        return false;
    }

    private static function _find(int $id): array|bool
    {
        $user = (new DatabaseUser())->get_by_id($id);

        return $user;
    }

    private function load(array $user): User
    {
        foreach ($user as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    public function toString(): array
    {
        return [
            "id"             => $this->id ?? 0,
            "name"           => $this->name,
            "email"          => $this->email,
            "password"       => $this->password,
            "token"          => $this->token,
            "created_at"     => $this->created_at,
            "updated_at"     => $this->updated_at
        ];
    }

    public function __toString()
    {
        return Response::json($this->toString());
    }

    public static function create(array $data): array
    {
        $data['password'] =  password_hash($data['password'], PASSWORD_DEFAULT);
        $data['token'] = Helper::randomStr(100);
        return DatabaseUser::create($data);
    }
}
