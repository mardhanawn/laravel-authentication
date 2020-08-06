<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');



$api->version('v1', function($api){

    $api->post('/login' ,'App\Http\Controllers\AuthControllers@login');
    
    $api->post('/register' ,'App\Http\Controllers\AuthControllers@register');

    $api->group(['middleware' => 'auth.jwt'], function ($api) {
        $api->get('/me', 'App\Http\Controllers\AuthControllers@show');
    
        $api->delete('/logout' ,'App\Http\Controllers\AuthControllers@logout');
    });
});
