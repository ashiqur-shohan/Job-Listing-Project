<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';

use Framework\Router;

// Instantiate the Router
$router = new Router();

// Load the routes
$routes = require basePath('routes.php');

// Get Current URI without query string 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ;

$router->route($uri);