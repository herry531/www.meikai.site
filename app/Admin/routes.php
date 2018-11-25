<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
//    $router->resource('client', ClientController::class);
    $router->resource('movies', MovieController::class);//客户信息
    $router->resource('archives', ArchivesController::class);//学员档案
    $router->resource('visiting', VisitingController::class);//来访信息
    $router->resource('channel', ChannelController::class);//来源渠道
    $router->resource('tasks', TaskController::class);//任务
    $router->resource('foos', FooController::class);//测试


    $router->resource('spending', SpendingController::class);//财务支出
    $router->resource('financial', FinancialController::class);//财务收入


    //接口
//    $router->get('api/city', 'ApiController@index');

    $router->get('api/city', 'ApiController@city');
    $router->get('api/district', 'ApiController@district');


});
