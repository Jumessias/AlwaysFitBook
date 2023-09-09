<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\User;

class AuthController
{
    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if(!isset($data['email']) || !isset($data['password'])){
            return Response::json(['msg'=>'Os campos: E-mail e Senha são obrigatórios'], 400);
        }

        $user = User::findByEmail($data["email"]);

        if(!$user){
            return Response::json(['msg'=>'Usuário não encontrado!'], 400);
        }
        if(!password_verify($data['password'], $user->password)){
            return Response::json(['msg'=>'A senha informada é invalida!'], 400);
        }
        unset($user->password);
        return Response::json([
            "user" => $user
        ]);
    }

    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if(!isset($data['email']) || !isset($data['password']) || !isset($data['name'])){
            return Response::json(['msg'=>'Os campos: Nome, E-mail e Senha são obrigatórios'], 400);
        }

        if(User::findByEmail($data['email'])){
            return Response::json(['msg'=>'O e-mail informado já esta em uso!'], 400);
        }

        $user = User::create($data);
        unset($user['password']);
        return Response::json($user);
    }
}
