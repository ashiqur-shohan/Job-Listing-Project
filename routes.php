<?php

// Create routes using the $router object

$router->get('/','HomeController@index');
$router->get('/listings','ListingController@index');
$router->get('/listings/create','ListingController@create');
// {id} is a route parameter
$router->get('/listings/{id}','ListingController@show');
$router->post('/listings', 'ListingController@store');
$router->delete('/listings/{id}', 'ListingController@destroy');