<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Api\V1\Files\FilesController;

/** @var Illuminate\Routing\Router $router */
$router->group(['middleware' => ['guest'], 'prefix' => 'auth'], function () use ($router) {
    $router->post('/login', [\App\Http\Api\V1\Auth\AuthController::class, 'login']);
});

$router->group(['middleware' => ['auth:user-api', 'scopes:user']], function () use ($router) {
    $router->get('files', [FilesController::class, 'index']);
    $router->get('files/{file}/inserted-data', [FilesController::class, 'insertedData']);
    $router->post('files', [FilesController::class, 'store']);
});
