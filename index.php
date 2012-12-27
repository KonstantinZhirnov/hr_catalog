<?php
require_once 'core/coreInitializer.php';


$modules = new ModuleChain();
//$modules->AddModule(new ErrorModule());
$modules->AddModule(new ConfigModule('config/config.php'));
$modules->Process();
$_POST = array_merge($_POST, array('test1'=>'asd#ert'));

$_REQUEST['test1'] = 'qwerty';

$sys = System::getInstance();
System::hello('hello');
$_REQUEST['test2'] = System::hello();
$sys->hello = 'tryam';
$_REQUEST['test3'] = $sys->hello;
Log::Show(System::basePath());
?>

