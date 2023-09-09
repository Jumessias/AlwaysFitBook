<?php

namespace App\Controllers;

use App\Helpers\Response;

class NotFoundController
{
    public function fallback()
    {
        return Response::json([
            "msg" => "route not found"
        ]);
    }
}
