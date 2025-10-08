<?php
require '../helpers.php';

// define the routes
$routes = [
    '/' => 'controllers/home.php',
    '/listings' => 'controllers/listings/index.php',
    '/listings/create' => 'controllers/listings/create.php',
    '404' => 'controllers/error/404.php'
];

// grab the URI
$uri = $_SERVER['REQUEST_URI'];

// check if the route exists, else load 404
if(array_key_exists($uri, $routes)){
    require basePath($routes[$uri]);
}
else{
    require basePath($routes['404']);
}