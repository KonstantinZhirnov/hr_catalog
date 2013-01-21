<?php
require_once 'core/CoreInitializer.php';
session_start();


$modules = new ModuleChain();
$modules->AddModule(new ConfigModule('config/config.php'));
$modules->AddModule(new DatabaseInitModule());
$modules->AddModule(new DatabaseTableInitModule());
//$modules->AddModule(new AuthorizationModule());
$modules->Process();
?>
