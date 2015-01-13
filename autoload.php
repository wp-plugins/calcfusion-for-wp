<?php

/*
 * autoload classes  
 */

spl_autoload_register(function ($className) {
    $file=__DIR__.DIRECTORY_SEPARATOR."core".DIRECTORY_SEPARATOR.$className.".class.php";
     if (is_readable($file)) require_once $file;
});

?>