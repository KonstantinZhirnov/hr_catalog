<?php
require_once ("core/coreInitializer.php");

$initializer = new CoreInitializer();
//$initializer->AddExcludeDirs('logger');
//$initializer->AddExcludeFiles('IModule.php');
//$initializer->AddExcludeExtensions('php, log');
$initializer->Init();


?>
