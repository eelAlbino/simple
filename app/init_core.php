<?php
use Main\App as App;

spl_autoload_register(function ($class_name) {

    $class_name = strtolower( 
        str_replace(
            array('/_', '\\_', '\\','/D_B'),
            array('/', '/', '/', '/db'),
            preg_replace("/(?!^)([A-Z])/", '_$1', $class_name )
        )
    );
    include __DIR__.'/model/'. $class_name . '.php';
});
    
$app = App::getInstance();
?>