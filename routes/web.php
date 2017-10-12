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

    $plain = 'Seldaek' . $_ENV['STRING_DELIMITER'] . 'composer';
    $cipher = $_ENV['CIPHER'];
    $options = 0;
    $ivlen = openssl_cipher_iv_length($cipher);
    $key = substr($_ENV['APP_KEY'], '0', $ivlen);

    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypt = openssl_encrypt($plain, $cipher, $key, $options, $key);

    var_dump($encrypt);echo '<br>';

    $decrypt = openssl_decrypt($encrypt, $cipher, $key, $options, $key);

    var_dump($decrypt);

});

$router->group(['middleware' => 'cors'], function () use ($router) {

    $router->get('/', 'MainController@index');

});

$router->group(['prefix' => 'violations', 'middleware' => 'cors'], function () use ($router) {

    $router->get('/next/{hash}', 'TdViolationsController@next');

});
