<?php
require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';

use Framework\Router;

// // Autoload classes
// spl_autoload_register(function ($class){

//     // Define the path to the Framework directory where classes are stored
//     $path = basePath('Framework/' . $class . '.php');
//     if(file_exists($path)){
        
//         // If the class file exists, require it
//         require $path;
//     }
// });

// Instantiate the Router
$router = new Router();

// Load the routes
$routes = require basePath('routes.php');

// Get Current URI without query string 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ;

//Get http Method
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);