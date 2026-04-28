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

$router->options('{any:.*}', function () {
    return response('', 200);
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//Auth Route
$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');

//Concert Route
$router->group(['prefix' => 'concerts'], function () use ($router) {
    $router->get('/', 'ConcertController@index');
    $router->get('/{id}', 'ConcertController@show');
});

$router->group(['prefix' => 'concerts'], function () use ($router) {
    $router->post('/', 'ConcertController@store');
    $router->put('/{id}', 'ConcertController@update');
    $router->delete('/{id}', 'ConcertController@destroy');
});

//Ticket Route
$router->group(['prefix' => 'tickets'], function () use ($router) {
    $router->get('/', 'TicketController@index');
    $router->get('/{id}', 'TicketController@show');
    $router->post('/', 'TicketController@store');
});

$router->group(['prefix' => 'tickets', 'middleware' => ['auth', 'admin']], function () use ($router) {
    $router->put('/{id}', 'TicketController@update');
    $router->delete('/{id}', 'TicketController@destroy');
});

//Order Detail Route
$router->group(['prefix' => 'orders', 'middleware' => ['auth']], function () use ($router) {
    $router->get('/me', 'OrderDetailController@me');
    $router->get('/{id}', 'OrderDetailController@show');
});

$router->group(['prefix' => 'orders', 'middleware' => ['auth', 'admin']], function () use ($router) {
    $router->get('/', 'OrderDetailController@index');
    $router->put('/{id}', 'OrderDetailController@update');
    $router->delete('/{id}', 'OrderDetailController@destroy');
});

//Ticket Order Route
$router->group(['prefix' => 'ticket-orders', 'middleware' => 'auth'], function () use ($router) {
    $router->get('/{id}', 'TicketOrderController@show');
    $router->post('/', 'TicketOrderController@store');
});

$router->group(['prefix' => 'ticket-orders', 'middleware' => ['auth', 'admin']], function () use ($router) {
    $router->get('/', 'TicketOrderController@index');
    $router->put('/{id}', 'TicketOrderController@update');
    $router->delete('/{id}', 'TicketOrderController@destroy');
});

//Genre Route
$router->group(['prefix' => 'genres'], function () use ($router) {
    $router->get('/', 'GenreController@index');
    $router->get('/{id}', 'GenreController@show');
});

$router->group(['prefix' => 'genres', 'middleware' => ['auth', 'admin']], function () use ($router) {
    $router->post('/', 'GenreController@store');
    $router->put('/{id}', 'GenreController@update');
    $router->delete('/{id}', 'GenreController@destroy');
});

//Callback route
$router->post('/midtrans/callback', 'PaymentCallbackController@handle');

//Cities Route
$router->group(['prefix' => 'cities'], function () use ($router) {
    $router->get('/', 'CityController@index');
    $router->get('/{id}', 'CityController@show');
});

$router->group(['prefix' => 'cities', 'middleware' => ['auth', 'admin']], function () use ($router) {
    $router->post('/', 'CityController@store');
    $router->put('/{id}', 'CityController@update');
    $router->delete('/{id}', 'CityController@destroy');
});

// Route untuk venue
$router->group(['prefix' => 'venues'], function () use ($router) {
    $router->get('/', 'VenueController@index');
    $router->get('/{id}', 'VenueController@show');
});

$router->group(['prefix' => 'venues', 'middleware' => ['auth', 'admin']], function () use ($router) {
    $router->post('/', 'VenueController@store');
    $router->put('/{id}', 'VenueController@update');
    $router->delete('/{id}', 'VenueController@destroy');
});

// Route OAuth Google
$router->get('/auth/redirect', 'AuthController@redirectToGoogle');
$router->get('/auth/callback', 'AuthController@handleGoogleCallback');
$router->get('/login-google', function () {
    return view('oauth');
});
