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

$router->get('test', function () {



});

$router->group(['middleware' => 'cors'], function () use ($router) {

    $router->get('/', 'MainController@index');

});

$router->group(['prefix' => 'violations', 'middleware' => 'cors'], function () use ($router) {

    $router->get('/next', 'TdViolationsController@next');

    $router->post('/evaluate', 'TdViolationsController@evaluate');

});
