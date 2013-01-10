<?php
require_once 'core/CoreInitializer.php';


$modules = new ModuleChain();
$modules->AddModule(new ConfigModule('config/config.php'));
$modules->AddModule(new DatabaseInitModule());
$modules->AddModule(new DatabaseTableInitModule());
$modules->AddModule(new AuthorizationModule());
$modules->Process();
?>
