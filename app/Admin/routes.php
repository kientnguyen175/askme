<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    // $router->get('/', 'HomeController@index')->name('home');
    $router->resource('manage/users', UserController::class);
    $router->resource('manage/tags', TagController::class);
    $router->resource('manage/questions', QuestionController::class);
    $router->resource('manage/answers', AnswerController::class);
    $router->resource('manage/comments', CommentController::class);
    $router->get('/dashboard', 'ChartjsController@index');
    $router->get('/', 'ChartjsController@index');
});
