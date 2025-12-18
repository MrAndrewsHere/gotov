<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('v1')->group(function (Router $router) {
    $router->get('/', function () {
        return response()->json(['message' => 'API v1']);
    });
});
