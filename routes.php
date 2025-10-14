<?php

// Create routes using the $router object

$router->get('/','HomeController@index');
$router->get('/listings','ListingController@index');
$router->get('/listings/create','ListingController@create');
// {id} is a route parameter
$router->get('/listing/{id}','ListingController@show');
$router->post('/listings', 'ListingController@store');