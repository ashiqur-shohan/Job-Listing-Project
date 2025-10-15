<?php

/**
 * get the base path
 * 
 * @param string $path
 * @return string 
 */

function basePath($path = ''){
    // __DIR__ is a magic constant
    // always gives the directory path of the file in which it is written (not the file that called it)
    return __DIR__ . '/' . $path;
}


/**
 * Load View
 * 
 * @param string $name
 * @return void
 */

function loadView($name, $data = []) {
    $viewPath = basePath("App/views/{$name}.php");

    if(file_exists($viewPath)){
        // Extract variables from the data array to be used in the view
        extract($data);
        require $viewPath;
    }
    else{
        echo "View {$viewPath} not found";
    }
}


/**
 * Load Partials
 * 
 * @param string $name
 * @return void
 */

function loadPartials($name, $data = []) {
    $partialPath = basePath("App/views/partials/{$name}.php");

    if(file_exists($partialPath)){
        extract($data);
        require $partialPath;
    }
    else{
        echo "Partial {$partialPath} not found";
    }
}

/**
 * Inspect a Value(s)
 * 
 * @param mixed $value
 * @return void
 */

function inspect($value){
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

/**
 * Inspect a Value(s) and Die
 * 
 * @param mixed $value
 * @return void
 */

function inspectAndDie($value){
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    
    // immediately terminate the execution of the current script
    die();
}

/**
 * Format Salary
 * 
 * @param string $salary
 * @return string Formatted Salary
 */
function formatSalary($salary){
    return '$' . number_format(($salary));
}

/**
 * Sanitize Input
 * 
 * @param string $input
 * @return string 
 */

function sanitize($input){
    return filter_var(trim($input), FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Redirect to a given URL
 * 
 * @param string $url
 * @return void
 */
function redirect($url){
    header("Location: {$url}");
    exit;
}