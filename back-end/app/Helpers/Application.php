<?php

namespace App\Helpers;

class Application
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run()
    {
        return $this->router->resolve();
    }
}
