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

function loadView($name) {
    $viewPath = basePath("views/{$name}.php");

    if(file_exists($viewPath)){
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

function loadPartials($name) {
    $partialPath = basePath("views/partials/{$name}.php");

    if(file_exists($partialPath)){
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