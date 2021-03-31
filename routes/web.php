<?php

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


// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
   $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('profile/update', 'UserController@updateProfile');
    $router->post('profile/update-password', 'UserController@updatePassword');
    $router->get('profile', 'UserController@profile');
    $router->get('users/{id}', 'UserController@singleUser');
    $router->get('users', 'UserController@allUsers');

});
$router->group(['prefix' => 'api/product'], function () use ($router) {
    $router->get('/', 'ProductController@listData');
    $router->post('/store', 'ProductController@storeData');
    $router->post('/update/{id}', 'ProductController@updateData');
    $router->post('/delete/{id}', 'ProductController@deleteData');
    $router->post('/review-product/{id}', 'ProductController@reviewProduct');
});

