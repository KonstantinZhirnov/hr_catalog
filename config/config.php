<?php

$config = array(
    'database' => array(
        'server' => 'localhost',
        'name' => 'hr_database',
        'user' => 'root',
        'password' => 'root'
    ),
    'memcache' => array(
        'server' => 'localhost',
        'port' => '11211'
    )
);

if(file_exists('config.local.php')) {
  require_once 'config.local.php';
}
?>
