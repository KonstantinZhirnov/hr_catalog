<?php
require_once 'config/config.php';


$modules = new ModuleChain();
$modules->AddModule(new ErrorModule());
$modules->Process();
$_POST = array_merge($_POST, array('test1'=>'asd#ert'));

$_REQUEST['test1'] = 'qwerty';
Log::Show($_REQUEST);
?>
<form method="POST">
	<input name="test" value="asd#fgh" />
	<input type="button" onclick="this.form.submit()" />
</form>
