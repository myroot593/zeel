<?php 


spl_autoload_register(function ($class) {

    $prefix = '';   
    $base_dir = 
    array(
        __DIR__ . '/../',         
        __DIR__.'/../../',          
    );
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    
    foreach($base_dir as $list)
    {
        $file = $list . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});
