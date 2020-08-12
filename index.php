<?php

// Import global app config
require 'config/config.php';
require DB_CREDENTIALS;

// Import MVC classes 
$dirs = [
    LIB . '/*.php',
    MODELS . '/*.php',
    MODELS . '/games/*.php',
    MODELS . '/games/ttt/*.php',
    CONTROLLERS . '/interfaces/*.php',
    CONTROLLERS . '/helpers/*.php',
    CONTROLLERS . '/*.php'
];

foreach ($dirs as $dir) {
    foreach (glob($dir) as $file) { 
        require $file; 
    }
}

// Route request to the correct controller
require 'config/routes.php';

?>
