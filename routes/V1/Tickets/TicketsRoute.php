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

$router->group(
    array(
        'prefix' => 'tickets',
    ),
    function () use ($router) {
        $router->get('/', array('uses' => 'TicketController@index'));
        $router->get('{id}', array('uses' => 'TicketController@show'));
        $router->post('/', array('uses' => 'TicketController@store'));
        $router->put('{id}', array('uses' => 'TicketController@update'));
        $router->delete('{id}', array('uses' => 'TicketController@destroy'));
    }
);
