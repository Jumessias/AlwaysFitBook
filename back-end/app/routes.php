<?php
if (!defined("LOADED")) exit;

use App\Controllers\AuthController;
use App\Controllers\NotFoundController;
use App\Controllers\GalleryController;
use App\Helpers\Router;

//registro de user e auth
Router::post("/api/login", [AuthController::class, "login"]);
Router::post("/api/register", [AuthController::class, "register"]);


//galeria
Router::get("/api/galleries", [GalleryController::class, "index"]);
Router::get("/api/galleries/{id}", [GalleryController::class, "show"]);
Router::post("/api/galleries", [GalleryController::class, "store"]);
Router::put("/api/galleries/{id}", [GalleryController::class, "update"]);
Router::delete("/api/galleries/{id}", [GalleryController::class, "destroy"]);

//not found
Router::not_found([NotFoundController::class, "not_found"]);
