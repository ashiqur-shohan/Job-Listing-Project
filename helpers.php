<?php

/**
 * get the base path
 * 
 * @param string $path
 * @return string 
 */

function basePath($path = '')
{
    // __DIR__ is a magic constant
    // always gives the directory path of the file in which it is written (not the file that called it)
    return __DIR__ . '/' . $path;
}
