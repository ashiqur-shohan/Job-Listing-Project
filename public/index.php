<?php
require '../helpers.php';
require basePath('Router.php');
require basePath('Database.php');

// Instantiate the Router
$router = new Router();

// Load the routes
$routes = require basePath('routes.php');

// Get Current URI and http Method
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);