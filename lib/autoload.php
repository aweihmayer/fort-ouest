<?php
spl_autoload_register(function($className) {
    $className = str_replace('\\', '/', $className);
    $namespace = str_replace('\\', '/', __NAMESPACE__);
    require LIB_PATH . $namespace . $className . '.php';
});