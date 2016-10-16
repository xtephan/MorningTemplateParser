<?php
/**
 * PHP Autoloader
 * autoloader.php
 * @author Stefan Fodor
 * @year 2016
 */
spl_autoload_register(function($class_name){

    $base_dir = realpath(__DIR__ . DIRECTORY_SEPARATOR . '../src');
    $filepath = $base_dir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';

    if( $filepath ) {
        require_once $filepath;
    }

});