<?php
require '../helpers.php';
require basePath('Framework/Router.php');
require basePath('Framework/Database.php');

// Instantiate the Router
$router = new Router();

// Load the routes
$routes = require basePath('routes.php');

// Get Current URI without query string 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ;

//Get http Method
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);