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

// 跨域预检路由
$router->options('/{path:.*}', function ($path) {});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/test', ['uses' => 'ExampleController@test', 'as' => 'example.test', 'name' => '测试']);

    $router->post('/login', ['uses' => 'AuthController@login', 'as' => 'login', 'name' => '登录']);

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/resources', ['uses' => 'ResourceController@index', 'as' => 'resources.index', 'name' => '资源列表']);
        $router->post('/resource', ['uses' => 'ResourceController@store', 'as' => 'resource.store', 'name' => '资源存储']);
        $router->put('/resource/{id}', ['uses' => 'ResourceController@rename', 'as' => 'resource.rename', 'name' => '资源重命名']);
        $router->delete('/resource/{id}', ['uses' => 'ResourceController@destroy', 'as' => 'resource.destroy', 'name' => '资源销毁']);

        $router->get('/markdown/{id}', ['uses' => 'MarkdownController@show', 'as' => 'markdown.show', 'name' => '文档查看']);
        $router->post('/markdown/{id}', ['uses' => 'MarkdownController@save', 'as' => 'markdown.save', 'name' => '文档保存']);
    });
});
