<?php

$config = array(
    'database' => array(
        'server' => 'localhost',
        'name' => 'hr_database',
        'user' => 'root',
        'password' => 'root'
    ),
    'loginExpTime' => 60 * 10,
);

if(file_exists('config.local.php')) {
  require_once 'config.local.php';
}
?>
