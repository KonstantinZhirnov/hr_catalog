<?php
require_once 'core/coreInitializer.php';


$modules = new ModuleChain();
$modules->AddModule(new ConfigModule('config/config.php'));
$modules->AddModule(new DatabaseInitModule());
$modules->AddModule(new DatabaseTableInitModule());
$modules->Process();
?>
