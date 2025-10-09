<?php
require '../helpers.php';

// grab the URI
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];


require basePath('router.php');