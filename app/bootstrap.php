<?php
// Load Config
require_once '../config/config.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    if (file_exists('../core/' . $className . '.php')) {
        require_once '../core/' . $className . '.php';
    } elseif (file_exists('../app/models/' . $className . '.php')) {
        require_once '../app/models/' . $className . '.php';
    }
});
