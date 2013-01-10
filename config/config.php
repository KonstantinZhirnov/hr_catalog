<?php

$config = array(
    'database' => array(
        'server' => '192.168.246.180',
        'name' => 'ilogos_ua_hr',
        'user' => 'hr_u',
        'password' => '6dhxYQyeZvJz3vsF'
    ),
    'loginExpTime' => 60 * 10,
);

if(file_exists('config.local.php')) {
  require_once 'config.local.php';
}
?>
