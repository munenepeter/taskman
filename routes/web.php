<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['namespace' => 'API', 'prefix' => 'api/v1/tasks'], function () use ($router) {
    $router->get('/', 'TaskController@index');
    $router->get('/{task}', 'TaskController@show');
    $router->post('/', 'TaskController@store');
    $router->put('/{task}', 'TaskController@update');
    $router->delete('/{task}', 'TaskController@delete');
});
