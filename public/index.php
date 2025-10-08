<?php
require '../helpers.php';

// require basePath()'../views/home.php';

// loadView('home');

$routes = [
    '/' => 'controllers/home.php',
    '/listings' => 'controllers/listings/index.php',
    '/listings/create' => 'controllers/listings/create.php',
    '404' => 'controllers/error/404.php'
];

$uri = $_SERVER['REQUEST_URI'];

inspectAndDie($uri);